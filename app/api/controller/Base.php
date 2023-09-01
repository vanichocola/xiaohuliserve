<?php

namespace app\api\controller;

use think\facade\Request;

class Base
{
    protected static $user;
    protected static $site_id;

    public function __construct()
    {
        if (Request::action() == 'login') {
            return;
        }
        $token = Request::header('x-token');
        if ($token) {
            session_id($token);
        }
        session_start();
        if (empty($_SESSION['user'])) {
            die(json_encode(['errno' => 403, 'message' => '请登录']));
        }
        $user = json_decode($_SESSION['user'], true);
        if (empty($user['openid'])) {
            die(json_encode(['errno' => 403, 'message' => '请登录']));
        }

        self::$user = $user;
        self::$site_id = $user['site_id'];
    }
}
