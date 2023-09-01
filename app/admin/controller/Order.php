<?php

namespace app\admin\controller;

use think\facade\Db;

class Order extends Base
{
    /**
     * 获取订单列表
     */
    public function getList()
    {
        $page = input('page', 1, 'intval');
        $pagesize = input('pagesize', 10, 'intval');
        $page = max(1, $page);
        $pagesize = max(1, $pagesize);
        $pay_type = input('pay_type', '', 'trim');
        $trade_no = input('trade_no', '', 'trim');
        $date = input('date', '', 'trim');

        $where = [];
        $where[] = ['site_id', '=', self::$site_id];
        $where[] = ['status', '=', 1];

        // 按支付方式
        if ($pay_type) {
            $where[] = ['pay_type', '=', $pay_type];
        }
        // 按支付时间
        if (!empty($date)) {
            $start_time = strtotime($date[0]);
            $end_time = strtotime($date[1]);
            $where[] = ['pay_time', 'between', [$start_time, $end_time]];
        }
        // 按单号
        if ($trade_no) {
            $where[] = ['out_trade_no|transaction_id', 'like', '%' . $trade_no . '%'];
        }

        $list = Db::name('order')
            ->where($where)
            ->page($page, $pagesize)
            ->order('pay_time desc, id desc')
            ->select()
            ->each(function ($item) {
                $item['total_fee'] = $item['total_fee'] / 100;
                $item['pay_time'] = date('Y-m-d H:i:s', $item['pay_time']);
                return $item;
            });
        $count = Db::name('order')
            ->where($where)
            ->count();


        return successJson([
            'count' => $count,
            'list' => $list
        ]);
    }

    /**
     * 导出订单列表
     */
    public function getExportList()
    {
        $pay_type = input('pay_type', '', 'trim');
        $trade_no = input('trade_no', '', 'trim');
        $date = input('date', '', 'trim');

        $where = [];
        $where[] = ['site_id', '=', self::$site_id];
        $where[] = ['status', '=', 1];

        // 按支付方式
        if ($pay_type) {
            $where[] = ['pay_type', '=', $pay_type];
        }
        // 按支付时间
        if (!empty($date)) {
            $start_time = strtotime($date[0]);
            $end_time = strtotime($date[1]);
            $where[] = ['pay_time', 'between', [$start_time, $end_time]];
        }
        // 按单号
        if ($trade_no) {
            $where[] = ['out_trade_no|transaction_id', 'like', '%' . $trade_no . '%'];
        }

        $list = Db::name('order')
            ->where($where)
            ->order('pay_time desc, id desc')
            ->select()
            ->each(function ($item) {
                $item['total_fee'] = $item['total_fee'] / 100;
                $item['pay_time'] = date('Y-m-d H:i:s', $item['pay_time']);
                // 状态
                if ($item['is_refund'] == 1) {
                    $item['status'] = '已全额退款';
                } else {
                    $item['status'] = '付款成功';
                }
                // 支付方式
                if ($item['pay_type'] == 'wxpay') {
                    $item['pay_type'] = '微信支付';
                } elseif ($item['pay_type'] == 'alipay') {
                    $item['pay_type'] = '支付宝';
                }
                if ($item['goods_id']) {
                    $item['num'] .= '条';
                } elseif ($item['draw_id']) {
                    $item['num'] .= '张';
                }  elseif ($item['gpt4_id']) {
                    $item['num'] .= '万字';
                } elseif ($item['vip_id']) {
                    $item['num'] .= '个月';
                }
                return $item;
            });

        return successJson($list);
    }

    /**
     * 统计
     */
    public function getTongji()
    {
        $pay_type = input('pay_type', '', 'trim');
        $trade_no = input('trade_no', '', 'trim');
        $date = input('date', '', 'trim');

        $where = [];
        $where[] = ['site_id', '=', self::$site_id];
        $where[] = ['status', '=', 1];

        // 按支付方式
        if ($pay_type) {
            $where[] = ['pay_type', '=', $pay_type];
        }
        // 按支付时间
        if (!empty($date)) {
            $start_time = strtotime($date[0]);
            $end_time = strtotime($date[1]);
            $where[] = ['pay_time', 'between', [$start_time, $end_time]];
        }
        // 按单号
        if ($trade_no) {
            $where[] = ['out_trade_no|transaction_id', 'like', '%' . $trade_no . '%'];
        }

        // 订单数量、订单金额
        $data = Db::name('order')
            ->where($where)
            ->field('count(id) as order_count,sum(total_fee) as order_amount')
            ->find();

        return successJson([
            'orderCount' => intval($data['order_count']),
            'orderAmount' => intval($data['order_amount']) / 100
        ]);
    }

    /**
     * 订单详情
     */
    public function getOrderDetail()
    {
        $order_no = input('order_no', '', 'trim');
        if (!$order_no) {
            return errorJson('参数错误');
        }
        $where = [
            ['site_id', '=', self::$site_id],
            ['out_trade_no|transaction_id', '=', $order_no],
            ['status', '=', 1]
        ];
        $order = Db::name('order')
            ->where($where)
            ->find();
        if (!$order) {
            return errorJson('没有找到此订单');
        }

        return successJson([
            'id' => $order['id'],
            'out_trade_no' => $order['out_trade_no'],
            'transaction_id' => $order['transaction_id'],
            'total_fee' => $order['total_fee'] / 100,
            'pay_type' => $order['pay_type'],
            'user_id' => $order['user_id'],
            'goods_id' => $order['goods_id'],
            'draw_id' => $order['draw_id'],
            'vip_id' => $order['vip_id'],
            'num' => $order['num'],
            'pay_time' => date('Y-m-d H:i:s', $order['pay_time']),
            'is_refund' => $order['is_refund']
        ]);
    }
}
