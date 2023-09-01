<?php

namespace app\api\controller;

use think\facade\Db;

class User extends Base
{
    /**
     * 个人中心资料
     */
    public function getInfo()
    {
        $now = time();
        $user = Db::name('user')
            ->where('id', self::$user['id'])
            ->find();
        // 分销入口
        $commissionSetting = getSystemSetting(self::$site_id, 'commission');
        $commissionIsOpen = empty($commissionSetting['is_open']) ? 0 : 1;
        // 审核模式
        $wxapp = getSystemSetting(self::$site_id, 'wxapp');
        $is_check = empty($wxapp['is_check']) ? 0 : 1;

        return successJson([
            'id' => $user['id'],
            'avatar' => $user['avatar'],
            'nickname' => $user['nickname'],
            'phone' => $user['phone'] ? substr_replace($user['phone'], '****', 3, 4) : '',
            'commission_is_open' => $commissionIsOpen,
            'is_commission' => $user['commission_level'] ? 1 : 0,
            'balance' => $user['balance'] ?? 0,
            'balance_draw' => $user['balance_draw'] ?? 0,
            'vip_expire_time' => $user['vip_expire_time'] > $now ? date('Y-m-d', $user['vip_expire_time']) : '',
            'is_check' => $is_check
        ]);
    }

    /**
     * 退出登录
     */
    public function logout()
    {
        $_SESSION['user'] = null;
        self::$user = null;
        return successJson('', '已退出登录');
    }

    /**
     * 意见反馈
     */
    public function feedback()
    {
        $type = input('type', '', 'trim');
        $content = input('content', '', 'trim');
        $phone = input('phone', '', 'trim');
        if (empty($content)) {
            return errorJson('请输入反馈内容');
        }
        $today = strtotime(date('Y-m-d'));
        $count = Db::name('feedback')
            ->where([
                ['user_id', '=', self::$user['id']],
                ['create_time', '>', $today]
            ])
            ->count();
        if ($count >= 5) {
            return errorJson('今天提交太多了，明天再来！');
        }
        try {
            Db::name('feedback')
                ->insert([
                    'site_id' => self::$site_id,
                    'user_id' => self::$user['id'],
                    'type' => $type,
                    'content' => $content,
                    'phone' => $phone,
                    'create_time' => time()
                ]);
            return successJson('', '提交成功，谢谢！');
        } catch (\Exception $e) {
            return errorJson('提交失败：' . $e->getMessage());
        }
    }

    /**
     * 关于我们
     */
    public function about()
    {
        $setting = getSystemSetting(self::$site_id, 'about');
        $content = !empty($setting['content']) ? $setting['content'] : '';
        $contents = $content ? explode("\n", $content) : [];
        return successJson($contents);
    }

    public function setUserAvatar()
    {
        $session_key = $_SESSION['session_key'];
        $encrypt_data = input('encryptedData', '', 'trim');
        $iv = input('iv', '', 'trim');

        $Wxapp = new \Weixin\Wxapp();
        $result = $Wxapp->Pkcs7Decode($session_key, $encrypt_data, $iv);
        if (isset($result['errno'])) {
            return errorJson($result['message']);
        }
        Db::name('user')
            ->where('id', self::$user['id'])
            ->update([
                'avatar' => $result['avatarUrl'],
                'nickname' => $result['nickName'],
                'gender' => $result['gender'],
                'city' => $result['city'],
                'province' => $result['province'],
                'country' => $result['country'],
                'update_time' => time()
            ]);
        $user = Db::name('user')
            ->where('id', self::$user['id'])
            ->find();
        $_SESSION['user'] = json_encode($user);
        return successJson();
    }

    public function setUserInfo()
    {
        $avatar = input('avatar', '', 'trim');
        $nickname = input('nickname', '', 'trim');
        if (empty($avatar) || $avatar == 'https://mmbiz.qpic.cn/mmbiz/icTdbqWNOwNRna42FI242Lcia07jQodd2FJGIYQfG0LAJGFxM4FbnQP6yfMxBgJ0F3YRqJCJ1aPAK2dQagdusBZg/0') {
            return errorJson('请设置头像');
        }
        if (empty($nickname) || $nickname == '微信用户') {
            return errorJson('请设置昵称');
        }

        $user = Db::name('user')
            ->where('id', self::$user['id'])
            ->find();
        if ($user) {
            Db::name('user')
                ->where('id', self::$user['id'])
                ->update([
                    'avatar' => $avatar,
                    'nickname' => $nickname,
                    'update_time' => time()
                ]);
            $user = Db::name('user')
                ->where('id', self::$user['id'])
                ->find();
            return successJson([
                'avatar' => $user['avatar'],
                'nickname' => $user['nickname']
            ]);
        } else {
            return errorJson('请重新打开页面');
        }
    }

    public function setUserPhone()
    {
        $session_key = $_SESSION['session_key'];
        $encrypt_data = input('encryptedData', '', 'trim');
        $iv = input('iv', '', 'trim');

        $Weixin = new \Weixin\Wxapp();
        $result = $Weixin->Pkcs7Decode($session_key, $encrypt_data, $iv);
        if (isset($result['errno'])) {
            return errorJson($result['message']);
        }
        $phone = $result['phoneNumber'];
        $user = Db::name('user')
            ->where('id', self::$user['id'])
            ->find();
        if ($user['phone'] != $phone) {
            Db::name('user')
                ->where('id', $user['id'])
                ->update([
                    'phone' => $phone,
                    'update_time' => time()
                ]);
            $user['phone'] = $phone;
        }
        $_SESSION['user'] = json_encode($user);
        return successJson([
            'phone' => $user['phone'] ? substr_replace($user['phone'], '****', 3, 4) : ''
        ]);
    }

    /**
     * 关于我们
     */
    public function kefu()
    {
        $setting = getSystemSetting(self::$site_id, 'kefu');
        if (empty($setting)) {
            $setting = [
                'phone' => '',
                'wxno' => '',
                'email' => '',
                'wx_qrcode' => '',
                'qun_qrcode' => ''
            ];
        }
        return successJson($setting);
    }

    /**
     * 卡密信息
     */
    public function getCardInfo()
    {
        $code = input('code', '', 'trim');
        $card = Db::name('card')
            ->where([
                ['site_id', '=', self::$site_id],
                ['code', '=', $code]
            ])
            ->field('type,val,user_id,bind_time')
            ->find();
        if (!$card) {
            return errorJson('卡密输入有误');
        }
        if ($card['type'] == 'gpt4') {
            $card['type'] = 'model4';
        }
        if ($card['user_id']) {
            $card['bind_time'] = date('Y-m-d', $card['bind_time']);
        }
        unset($card['user_id']);

        return successJson($card);
    }

    /**
     * 使用卡密
     */
    public function bindCard()
    {
        $code = input('code', '', 'trim');
        $card = Db::name('card')
            ->where([
                ['site_id', '=', self::$site_id],
                ['code', '=', $code]
            ])
            ->find();
        if (!$card) {
            return errorJson('卡密输入有误');
        }
        if ($card['user_id']) {
            return errorJson('此卡密已被使用');
        }
        if (!in_array($card['type'], ['times', 'draw', 'gpt4', 'vip']) || intval($card['val']) <= 0) {
            return errorJson('卡密信息有误，请联系客服');
        }

        Db::startTrans();
        try {
            if ($card['type'] == 'times') {
                changeUserBalance(self::$user['id'], $card['val'], '卡密充值');
            } elseif ($card['type'] == 'draw') {
                changeUserDrawBalance(self::$user['id'], $card['val'], '卡密充值');
            } elseif ($card['type'] == 'gpt4') {
                changeUserGpt4Balance(self::$user['id'], $card['val'] * 10000, '卡密充值');
            } elseif ($card['type'] == 'vip') {
                $today = strtotime(date('Y-m-d 23:59:59', time()));
                $user = Db::name('user')
                    ->where('id', self::$user['id'])
                    ->find();
                $vip_expire_time = max($today, $user['vip_expire_time']);
                $vip_expire_time = strtotime('+' . $card['val'] . ' month', $vip_expire_time);
                Db::name('user')
                    ->where('id', self::$user['id'])
                    ->update([
                        'vip_expire_time' => $vip_expire_time
                    ]);
                Db::name('user_vip_logs')
                    ->insert([
                        'site_id' => self::$site_id,
                        'user_id' => self::$user['id'],
                        'vip_expire_time' => $vip_expire_time,
                        'desc' => '卡密充值',
                        'create_time' => time()
                    ]);
            }
            Db::name('card')
                ->where('id', $card['id'])
                ->update([
                    'user_id' => self::$user['id'],
                    'bind_time' => time()
                ]);
            Db::commit();
            return successJson('', '兑换成功！');
        } catch (\Exception $e) {
            Db::rollback();
            return errorJson('操作失败：' . $e->getMessage());
        }
    }

}
