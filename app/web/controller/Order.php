<?php

namespace app\web\controller;

use think\facade\Db;

class Order extends Base
{
    /**
     * 获取充值套餐
     */
    public function getGoodsList()
    {
        $type = input('type', 'goods', 'trim');
        if (!in_array($type, ['vip', 'goods', 'draw', 'gpt4', 'model4'])) {
            return errorJson('参数错误');
        }
        if ($type == 'model4') {
            $type = 'gpt4';
        }
        $dbName = $type;

        $list = Db::name($dbName)
            ->where([
                ['site_id', '=', self::$site_id],
                ['status', '=', 1]
            ])
            ->field('id,title,price,market_price,num,hint,desc,is_default')
            ->order('weight desc, id asc')
            ->select()->each(function($item) {
                if ($item['desc']) {
                    $item['desc'] = explode("\n", $item['desc']);
                } else {
                    $item['desc'] = [];
                }

                return $item;
            })->toArray();

        return successJson($list);
    }

    public function createOrder()
    {
        $type = input('type', 'goods', 'trim');
        $goods_id = input('goods_id', 0, 'intval');
        if (!in_array($type, ['vip', 'goods', 'draw', 'gpt4'])) {
            return errorJson('参数错误');
        }
        $dbName = $type;

        $out_trade_no = $this->createOrderNo();

        $goods = Db::name($dbName)
            ->where([
                ['site_id', '=', self::$site_id],
                ['id', '=', $goods_id]
            ])
            ->find();
        if ($goods['status'] != 1) {
            return errorJson('该套餐已下架，请刷新页面重新提交');
        }
        $total_fee = $goods['price'];
        $num = $goods['num'];

        $order_id = Db::name('order')->insertGetId([
            'site_id' => self::$site_id,
            'goods_id' => $type == 'goods' ? $goods_id : 0,
            'draw_id' => $type == 'draw' ? $goods_id : 0,
            'gpt4_id' => $type == 'gpt4' ? $goods_id : 0,
            'vip_id' => $type == 'vip' ? $goods_id : 0,
            'user_id' => self::$user['id'],
            'out_trade_no' => $out_trade_no,
            'transaction_id' => '',
            'total_fee' => $total_fee,
            'pay_type' => 'wxpay',
            'status' => 0,
            'num' => $num,
            'create_time' => time()
        ]);

        return successJson([
            'order_id' => $order_id,
            'pay_url' => 'https://' . $_SERVER['HTTP_HOST'] . '/web.php/pay/order/id/' . $order_id
        ]);
    }

    public function checkPay()
    {
        $order_id = input('order_id', 0, 'intval');
        $order = Db::name('order')
            ->where('id', $order_id)
            ->find();
        if ($order && $order['status'] == 1) {
            $ispay = 1;
        } else {
            $ispay = 0;
        }
        return successJson([
            'ispay' => $ispay
        ]);
    }

    /**
     * 创建订单号
     */
    private function createOrderNo()
    {
        return 'Chat' . date('YmdHis') . rand(1000, 9999);
    }
}
