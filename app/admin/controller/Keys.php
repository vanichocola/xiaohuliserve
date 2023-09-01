<?php

namespace app\admin\controller;

use think\facade\Db;

class Keys extends Base
{
    public function getKeyList()
    {
        $page = input('page', 1, 'intval');
        $pagesize = input('pagesize', 10, 'intval');
        $type = input('type', 'openai', 'trim');
        $keyword = input('keyword', '', 'trim');
        $where = [
            ['site_id', '=', self::$site_id],
            ['type', '=', $type]
        ];
        if ($keyword) {
            $where[] = ['key|remark', 'like', '%' . $keyword . '%'];
        }
        try {
            $list = Db::name('keys')
                ->where($where)
                ->order('id desc')
                ->page($page, $pagesize)
                ->select()->each(function($item) {
                    $item['create_time'] = date('Y-m-d H:i:s', $item['create_time']);
                    return $item;
                });
            $count = Db::name('keys')
                ->where($where)
                ->count();
        } catch (\Exception $e) {
            return errorJson($e->getMessage());
        }

        return successJson([
            'list' => $list,
            'count' => $count
        ]);
    }

    /**
     * @return string
     * 新增key
     */
    public function saveKey()
    {
        $type = input('type', 'openai', 'trim');
        $key = input('key', '', 'trim');
        $remark = input('remark', '', 'trim');

        $rs = Db::name('keys')
            ->where([
                ['site_id', '=', self::$site_id],
                ['key', '=', $key]
            ])
            ->find();
        if ($rs) {
            return errorJson('Key已存在');
        }

        try {
            Db::name('keys')
                ->insert([
                    'site_id' => self::$site_id,
                    'type' => $type,
                    'key' => $key,
                    'state' => 1,
                    'remark' => $remark,
                    'create_time' => time()
                ]);
            return successJson('', '保存成功');
        } catch (\Exception $e) {
            return errorJson('保存失败：' . $e->getMessage());
        }
    }

    /**
     * @return string
     * 删除key
     */
    public function delKey()
    {
        $id = input('id', 0, 'intval');
        try {
            Db::name('keys')
                ->where([
                    ['site_id', '=', self::$site_id],
                    ['id', '=', $id]
                ])
                ->delete();
            return successJson('', '删除成功');
        } catch (\Exception $e) {
            return errorJson('删除失败：' . $e->getMessage());
        }
    }

    /**
     * @return string
     * 设置状态
     */
    public function setKeyState()
    {
        $id = input('id', 0, 'intval');
        $state = input('state', 0, 'intval');
        $state = $state ? 1 : 0;
        try {
            Db::name('keys')
                ->where([
                    ['site_id', '=', self::$site_id],
                    ['id', '=', $id]
                ])
                ->update([
                    'state' => $state,
                    'stop_reason' => $state ? '' : '手动停止'
                ]);
            return successJson('', '设置成功');
        } catch (\Exception $e) {
            return errorJson('设置失败：' . $e->getMessage());
        }
    }
}
