<?php

namespace app\admin\controller;

use think\facade\Db;

class Upgrade
{
    /**
     * 获取更新版本列表
     */
    public function getList()
    {
        $version = $setting = Db::name('setting')
            ->where('id', 1)
            ->value('version');
        $Upgrade = new \FoxUpgrade\upgrade();
        $result = $Upgrade->getVersionList($version);
        if (isset($result['errno']) && $result['errno'] > 0) {
            return errorJson($result['message']);
        }

        return successJson([
            'version' => $version,
            'list' => $result['data']
        ]);
    }

    
}
