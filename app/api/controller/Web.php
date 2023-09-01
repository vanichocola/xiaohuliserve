<?php

namespace app\api\controller;

use think\facade\Db;
use Wxpay\v2\JsApiPay;
use Wxpay\v2\WxPayConfig;

class Web
{
    public function chat()
    {
        $token = input('token', '', 'trim');
        $prompt_id = input('prompt_id', 0, 'intval');
        $role_id = input('role_id', 0, 'intval');
        if (!$token) {
            return $this->error('参数错误1');
        }
        session_id($token);
        session_start();
        if (empty($_SESSION['user'])) {
            return $this->error('参数错误2');
        }
        $user = @json_decode($_SESSION['user'], true);
        if (empty($user)) {
            return $this->error('参数错误3');
        }
        $sitecode = Db::name('site')
            ->where('id', $user['site_id'])
            ->value('sitecode');
        if (!$sitecode) {
            return $this->error('参数错误4');
        }

        // 静默授权并绑定公众号openid
        $webSetting = getSystemSetting($user['site_id'], 'web');
        if (!empty($webSetting['bind_wxapp_user'])) {
            $code = input('code', '', 'trim');
            $wxmpConfig = getSystemSetting($user['site_id'], 'wxmp');
            if ((empty($_SESSION['wxpay_appid']) || $_SESSION['wxpay_appid'] != $wxmpConfig['appid'] || empty($_SESSION['wxpay_openid'])) && empty($code)) {
                $redirect_uri = 'https://' . $_SERVER['HTTP_HOST'] . '/api.php/web/chat/token/' . $token . '/prompt_id/' . $prompt_id . '/role_id/' . $role_id;
                $query = $this->toUrlParams([
                    'appid' => $wxmpConfig['appid'],
                    'response_type' => 'code',
                    'scope' => 'snsapi_base',
                    'redirect_uri' => urlencode($redirect_uri)
                ]);

                header('location:https://open.weixin.qq.com/connect/oauth2/authorize?' . $query);
                exit();
            }
            if (!(!empty($_SESSION['wxpay_appid']) && $_SESSION['wxpay_appid'] == $wxmpConfig['appid'] && !empty($_SESSION['wxpay_openid']))) {
                $config = new WxPayConfig();
                $config->SetAppId($wxmpConfig['appid']);
                $config->SetAppSecret($wxmpConfig['appsecret']);
                $JsApiPay = new JsApiPay();
                $openid = $JsApiPay->getOpenidFromMp($config, $code);
                if (is_array($openid)) {
                    if (strpos($openid['errmsg'], 'code been used') !== false) {
                        return $this->error('请重新扫码打开');
                    } else {
                        return $this->error($openid['errmsg']);
                    }
                }
                $_SESSION['wxpay_appid'] = $wxmpConfig['appid'];
                $_SESSION['wxpay_openid'] = $openid;
            }
            if (!empty($openid)) {
                $user = Db::name('user')
                    ->where('id', $user['id'])
                    ->find();
                if ($user['openid_mp'] != $openid) {
                    Db::name('user')
                        ->where('id', $user['id'])
                        ->update([
                            'openid_mp' => $openid
                        ]);
                    // 合并web客户
                    $pcuser = Db::name('user')
                        ->where([
                            ['id', '<>', $user['id']],
                            ['openid_mp', '=', $openid]
                        ])
                        ->find();
                    if ($pcuser) {
                        $this->mergeUser($user, $pcuser);
                    }
                }

            }
        }
        $url = '/h5/?' . $sitecode . '/#/';
        if ($prompt_id) {
            $url .= 'pages/wxapp/write?prompt_id=' . $prompt_id;
        } elseif ($role_id) {
            $url .= 'pages/wxapp/cosplay?role_id=' . $role_id;
        } else {
            $url .= 'pages/wxapp/chat';
        }
        header('location:' . $url);
        exit;
    }

    /**
     * @return void
     * 即将作废
     */
    public function write()
    {
        $this->chat();
    }

    /**
     * @return void
     * 即将作废
     */
    public function cosplay()
    {
        $this->chat();
    }

    private function toUrlParams($urlObj)
    {
        $buff = '';
        foreach ($urlObj as $k => $v) {
            if ($k != 'sign') {
                $buff .= $k . '=' . $v . '&';
            }
        }

        $buff = trim($buff, '&');
        return $buff;
    }

    /**
     * @param $msg
     * 在页面上输出错误信息
     */
    private function error($message)
    {
        return view('error', ['message' => $message]);
    }

    /**
     * @param $user
     * 合并两个用户
     */
    private function mergeUser($user, $pcuser)
    {
        try {
            // 合并余额、会员时间
            $now = time();
            $balance = intval($user['balance'] + $pcuser['balance']);
            $balance_draw = intval($user['balance_draw'] + $pcuser['balance_draw']);
            $balance_gpt4 = intval($user['balance_gpt4'] + $pcuser['balance_gpt4']);
            $user_vip_time = $user['vip_expire_time'] > $now ? $user['vip_expire_time'] - $now : 0;
            $pcuser_vip_time = $pcuser['vip_expire_time'] > $now ? $pcuser['vip_expire_time'] - $now : 0;
            if ($user_vip_time || $pcuser_vip_time) {
                $vip_expire_time = $now + $user_vip_time + $pcuser_vip_time;
            } else {
                $vip_expire_time = 0;
            }
            $commission_level = ($user['commission_level'] || $pcuser['commission_level']) ? 1 : 0;
            $commission_money = $user['commission_money'] + $pcuser['commission_money'];
            $commission_paid = $user['commission_paid'] + $pcuser['commission_paid'];
            $commission_frozen = $user['commission_frozen'] + $pcuser['commission_frozen'];
            $commission_total = $user['commission_total'] + $pcuser['commission_total'];
            Db::name('user')
                ->where('id', $user['id'])
                ->update([
                    'balance' => $balance,
                    'balance_draw' => $balance_draw,
                    'balance_gpt4' => $balance_gpt4,
                    'vip_expire_time' => $vip_expire_time,
                    'commission_level' => $commission_level,
                    'commission_money' => $commission_money,
                    'commission_paid' => $commission_paid,
                    'commission_frozen' => $commission_frozen,
                    'commission_total' => $commission_total
                ]);
            Db::name('user')
                ->where('id', $pcuser['id'])
                ->delete();

            // 合并提问的问题、会话、创作
            Db::name('msg_group')
                ->where('user_id', $pcuser['id'])
                ->update([
                    'user_id' => $user['id']
                ]);
            Db::name('msg_web')
                ->where('user_id', $pcuser['id'])
                ->update([
                    'user_id' => $user['id']
                ]);
            Db::name('msg_write')
                ->where('user_id', $pcuser['id'])
                ->update([
                    'user_id' => $user['id']
                ]);
            Db::name('msg_draw')
                ->where('user_id', $pcuser['id'])
                ->update([
                    'user_id' => $user['id']
                ]);
            // 合并分销数据
            Db::name('user')
                ->where('tuid', $pcuser['id'])
                ->update([
                    'tuid' => $user['id']
                ]);
            Db::name('user')
                ->where('commission_pid', $pcuser['id'])
                ->update([
                    'commission_pid' => $user['id']
                ]);
            Db::name('user')
                ->where('commission_ppid', $pcuser['id'])
                ->update([
                    'commission_ppid' => $user['id']
                ]);

            Db::name('commission_apply')
                ->where('user_id', $pcuser['id'])
                ->update([
                    'user_id' => $user['id']
                ]);

            Db::name('commission_apply')
                ->where('pid', $pcuser['id'])
                ->update([
                    'pid' => $user['id']
                ]);
            Db::name('commission_bill')
                ->where('user_id', $pcuser['id'])
                ->update([
                    'user_id' => $user['id']
                ]);
            Db::name('commission_withdraw')
                ->where('user_id', $pcuser['id'])
                ->update([
                    'user_id' => $user['id']
                ]);
            // 分销订单
            Db::name('order')
                ->where('commission1', $pcuser['id'])
                ->update([
                    'commission1' => $user['id']
                ]);
            Db::name('order')
                ->where('commission2', $pcuser['id'])
                ->update([
                    'commission2' => $user['id']
                ]);
            // 留言数据
            Db::name('feedback')
                ->where('user_id', $pcuser['id'])
                ->update([
                    'user_id' => $user['id']
                ]);
            // 分享数据
            Db::name('reward_share')
                ->where('user_id', $pcuser['id'])
                ->update([
                    'user_id' => $user['id']
                ]);
            Db::name('reward_invite')
                ->where('user_id', $pcuser['id'])
                ->update([
                    'user_id' => $user['id']
                ]);
            Db::name('reward_ad')
                ->where('user_id', $pcuser['id'])
                ->update([
                    'user_id' => $user['id']
                ]);


        } catch (\Exception $e) {

        }
    }
}
