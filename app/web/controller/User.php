<?php

namespace app\web\controller;

use think\facade\Db;
use think\facade\Request;

class User extends Base
{
    public function checkLogin()
    {
        return successJson();
    }

    public function info()
    {
        $now = time();
        $user = Db::name('user')
            ->where([
                ['site_id', '=', self::$site_id],
                ['id', '=', self::$user['id']]
            ])
            ->find();
        if (!$user) {
            die(json_encode(['errno' => 403, 'message' => '请登录']));
        }
        // 分销入口
        $commissionSetting = getSystemSetting(self::$site_id, 'commission');
        $commissionIsOpen = empty($commissionSetting['is_open']) ? 0 : 1;
        // 是否打开GPT4开关
        $gpt4Setting = getSystemSetting(self::$site_id, 'gpt4');

        return successJson([
            'user_id' => $user['id'],
            'nickname' => $user['nickname'] ?? '未设置昵称',
            'avatar' => $user['avatar'] ? mediaUrl($user['avatar'], true) : '',
            'commission_is_open' => $commissionIsOpen,
            'is_commission' => $user['commission_level'] ? 1 : 0,
            'vip_expire_time' =>  $user['vip_expire_time'] > $now ? date('Y-m-d', $user['vip_expire_time']) : '',
            'balance' => $user['balance'] ?? 0,
            'balance_draw' => $user['balance_draw'] ?? 0,
            'balance_gpt4' => floor($user['balance_gpt4'] / 100) / 100,
            'hasModel4' => empty($gpt4Setting['is_open']) ? 0 : 1,
            'model4Name' => empty($gpt4Setting['alias_web']) ? 'GPT-4' : $gpt4Setting['alias_web']
        ]);
    }

    public function setUserAvatar()
    {
        $avatar = input('avatar', '', 'trim');
        if (empty($avatar)) {
            return errorJson('图片地址不能为空');
        }
        Db::name('user')
            ->where([
                ['site_id', '=', self::$site_id],
                ['id', '=', self::$user['id']]
            ])
            ->update([
                'avatar' => $avatar
            ]);
        return successJson('修改成功');
    }

    public function setUserNickname()
    {
        $nickname = input('nickname', '', 'trim');
        if (empty($nickname)) {
            return errorJson('请输入昵称');
        }
        Db::name('user')
            ->where([
                ['site_id', '=', self::$site_id],
                ['id', '=', self::$user['id']]
            ])
            ->update([
                'nickname' => $nickname
            ]);
        return successJson('保存成功');
    }

    /**
     * 退出登录
     */
    public function logout()
    {
        session_start();
        $_SESSION['user'] = null;
        $_SESSION['sitecode'] = null;
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
            }
            elseif ($card['type'] == 'draw') {
                changeUserDrawBalance(self::$user['id'], $card['val'], '卡密充值');
            }
            elseif ($card['type'] == 'gpt4') {
                changeUserGpt4Balance(self::$user['id'], $card['val'] * 10000, '卡密充值');
            }
            elseif ($card['type'] == 'vip') {
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
