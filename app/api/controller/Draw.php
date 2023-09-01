<?php

namespace app\api\controller;

use think\facade\Db;
use think\facade\Log;

class Draw extends Base
{
    /**
     * 获取消息历史记录
     */
    public function getHistoryMsg()
    {
        $list = Db::name('msg_draw')
            ->where([
                ['site_id', '=', self::$site_id],
                ['user_id', '=', self::$user['id']],
                ['is_delete', '=', 0]
            ])
            ->field('id,message,images,state,errmsg')
            ->order('id desc')
            ->limit(10)
            ->select()->toArray();
        $msgList = [];
        $list = array_reverse($list);
        foreach ($list as $v) {
            $msgList[] = [
                'draw_id' => $v['id'],
                'state' => $v['state'],
                'errmsg' => $v['errmsg'],
                'message' => $v['message'],
                'response' => explode('|', $v['images'])
            ];
        }

        return successJson($msgList);
    }

    public function draw()
    {
        $isTask = input('is_task', 0, 'intval');
        $draw_id = input('draw_id', '0', 'intval');
        if ($draw_id) {
            $message = Db::name('msg_draw')
                ->where([
                    ['site_id', '=', self::$site_id],
                    ['id', '=', $draw_id],
                    ['is_delete', '=', 0]
                ])
                ->value('message_input');
        } else {
            $message = input('message', '', 'trim');
        }
        if (empty($message)) {
            return errorJson('请输入绘图要求和场景描述');
        }
        $clearMessage = wordFilter($message);

        $user = Db::name('user')
            ->where('id', self::$user['id'])
            ->find();
        if (!$user) {
            $_SESSION['user'] = null;
            return errorJson('请登录');
        }

        // 禁言用户
        if ($user['is_freeze']) {
            return errorJson('系统繁忙，请稍后再试');
        }

        $size = '512x512';
        $num = 1;

        if ($isTask) {
            // 前端断开继续运行
            ignore_user_abort();
            // 脚本运行时间最大3分钟
            set_time_limit(180);
            if (!$draw_id) {
                exit;
            }

            $urls = [];
            try {
                $drawSetting = getSystemSetting(self::$site_id, 'draw');
                $platform = $drawSetting['platform'] ?? 'openai';
                $channel = $drawSetting['channel'] ?? 'openai';
                $apikey = $this->getApiKey($channel);
                if (empty($apikey)) {
                    $this->setDrawFail($draw_id, 'key已用尽，请等待平台处理');
                }

                if ($channel == 'openai' || $channel == 'api2d') {
                    $ChatGPT = new \ChatGPT\sdk($apikey);
                    if($channel == 'openai') {
                        $apiSetting = getSystemSetting(0, 'api');
                        if ($apiSetting['channel'] == 'agent' && $apiSetting['agent_host']) {
                            $ChatGPT->setChannel('agent');
                            $ChatGPT->setDiyHost($apiSetting['agent_host']);
                        }
                    } elseif ($channel == 'api2d') {
                        $ChatGPT->setChannel('agent');
                        $ChatGPT->setDiyHost('https://openai.api2d.net');
                    }

                    $result = $ChatGPT->draw([
                        'prompt' => $clearMessage,
                        'size' => $size,
                        'n' => $num,
                        'response_format' => 'b64_json'
                    ]);
                } elseif ($channel == 'lxai') {
                    $apikey = $drawSetting['lxai_key'] ?? '';
                    $ChatGPT = new \ChatGPT\lxai($apikey);
                    if ($platform == 'mj') {
                        $result = $ChatGPT->drawMJ([
                            'prompt' => $clearMessage
                        ]);
                    } elseif ($platform == 'sd') {
                        $result = $ChatGPT->drawSD([
                            'prompt' => $clearMessage
                        ]);
                    }
                } elseif ($channel == 'replicate') {
                    $replicateSDK = new \ChatGPT\replicate($apikey);
                    $result = $replicateSDK->draw([
                        'prompt' => $clearMessage
                    ]);
                }

                if (empty($result)) {
                    $this->setDrawFail($draw_id, '未知错误');
                }

                if ($result['errno']) {
                    $errLevel = 'warning';
                    $errMsg = $result['message'];
                    if ($channel == 'openai') {
                        if (strpos($errMsg, 'Billing hard limit has been reached') !== false) {
                            $errLevel = 'error';
                            $errMsg = '接口余额不足';
                        }
                    } elseif ($channel == 'api2d') {
                        if (strpos($errMsg, 'Not enough point') !== false) {
                            $errLevel = 'error';
                            $errMsg = '接口余额不足';
                        }
                    }
                    if ($errLevel == 'error') {
                        $this->setKeyStop($channel, $apikey, $errMsg);
                        $this->draw();
                    } else {
                        $this->setDrawFail($draw_id, $errMsg);
                    }
                    exit;
                }
                foreach ($result['data'] as $img) {
                    if ($channel == 'openai') {
                        $url = $this->saveToFile($img['b64_json']);
                    } elseif ($channel == 'api2d') {
                        $url = $this->saveToFile($img['url']);
                    }  elseif ($channel == 'replicate') {
                        $url = $this->saveToFile($img);
                    } elseif ($channel == 'lxai') {
                        $url = $this->saveToFile($img);
                    }
                    if (!empty($url)) {
                        $urls[] = $url;
                    }
                }
            } catch (\Exception $e) {
                $this->setDrawFail($draw_id, $e->getMessage());
                exit;
            }

            if (empty($urls)) {
                $this->setDrawFail($draw_id, '生成图片失败');
                exit;
            }

            // 生成成功，更新状态
            Db::name('msg_draw')
                ->where('id', $draw_id)
                ->update([
                    'platform' => $platform,
                    'channel' => $channel,
                    'images' => implode('|', $urls),
                    'state' => 1,
                    'finish_time' => time()
                ]);

        } else {
            if (intval($user['balance_draw']) <= 0) {
                return errorJson('绘图次数用完了，请充值！');
            }

            $now = time();
            $taskNum = Db::name('msg_draw')
                ->where([
                    ['site_id', '=', self::$site_id],
                    ['user_id', '=', self::$user['id']],
                    ['state', '=', 0],
                    ['is_delete', '=', 0],
                    ['create_time', '>', $now - 180]
                ])
                ->count();
            if ($taskNum >= 5) {
                return errorJson('最多同时进行5个任务，请稍后再试');
            }

            if ($draw_id) {
                Db::name('msg_draw')
                    ->where([
                        ['site_id', '=', self::$site_id],
                        ['id', '=', $draw_id],
                        ['is_delete', '=', 0]
                    ])
                    ->update([
                        'state' => 0,
                        'images' => '',
                        'errmsg' => '',
                        'finish_time' => 0,
                        'create_time' => time()
                    ]);
            } else {
                // 添加绘画记录
                $draw_id = Db::name('msg_draw')
                    ->insertGetId([
                        'site_id' => self::$site_id,
                        'user_id' => self::$user['id'],
                        'message' => $clearMessage,
                        'message_input' => $message,
                        'state' => 0, // 0生成中 1已生成 2生成失败
                        'size' => $size,
                        'num' => $num,
                        'create_time' => time()
                    ]);
            }

            // 扣费
            changeUserDrawBalance($user['id'], -1, '绘画消费');

            // 异步执行绘画任务
            $scheme = $_SERVER['REQUEST_SCHEME'] ?? 'https';
            $drawTaskUrl = $scheme . '://' . $_SERVER['HTTP_HOST'] . '/web.php/draw/draw';
            $this->httpRequest($drawTaskUrl, [
                'is_task' => 1,
                'draw_id' => $draw_id,
                'message' => $message
            ]);

            return successJson([
                'draw_id' => $draw_id
            ]);
        }
    }

    private function setKeyStop($channel, $key, $errorMsg)
    {
        if ($errorMsg) {
            Db::name('keys')
                ->where([
                    ['site_id', '=', self::$site_id],
                    ['type', '=', $channel],
                    ['key', '=', $key]
                ])
                ->update([
                    'state' => 0,
                    'stop_reason' => $errorMsg
                ]);
        }
    }

    public function getDrawResult()
    {
        $draw_id = input('draw_id', 0, 'intval');
        $draw = Db::name('msg_draw')
            ->where([
                ['site_id', '=', self::$site_id],
                ['id', '=', $draw_id],
                ['is_delete', '=', 0]
            ])
            ->find();
        if (!$draw) {
            // 未找到任务
            return successJson([
                'state' => -1
            ]);
        }
        if ($draw['state'] == 0) {
            $now = time();
            if ($now - $draw['create_time'] > 180) {
                $errMsg = '图片生成失败';
                $this->setDrawFail($draw_id, $errMsg);
                return successJson([
                    'state' => 2,
                    'message' => $errMsg
                ]);
            }
            return successJson([
                'state' => 0
            ]);
        } elseif ($draw['state'] == 1) {
            return successJson([
                'state' => 1,
                'images' => explode('|', $draw['images'])
            ]);
        } elseif ($draw['state'] == 2) {
            return successJson([
                'state' => 2,
                'message' => $draw['errmsg']
            ]);
        } else {
            // 未知状态
            return successJson([
                'state' => -1
            ]);
        }
    }

    private function setDrawFail($draw_id, $errMsg)
    {
        Db::name('msg_draw')
            ->where('id', $draw_id)
            ->update([
                'state' => 2,
                'errmsg' => $errMsg
            ]);
        // 退费
        changeUserDrawBalance(self::$user['id'], 1, '绘画失败退费');
    }

    /**
     * 保存图片文件内容到本地或者云存储
     */
    private function saveToFile($content)
    {
        if (strpos($content, 'https://') !== false || strpos($content, 'http://') !== false) {
            $context = stream_context_create([
                'http' => ['method' => 'GET', 'timeout' => 30],
                'ssl' => ['verify_peer' => false, 'verify_peer_name' => false]
            ]);
            $content = file_get_contents($content, false, $context);
        } else {
            $content = base64_decode($content);
        }
        if (empty($content)) {
            return '';
        }
        // 保存到本地
        $date = date('Ymd');
        $dir = './upload/draw/' . $date . '/';
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        $filepath = $dir . self::$user['id'] . uniqid() . '.png';
        file_put_contents($filepath, $content);
        if (!file_exists($filepath)) {
            return '';
        }
        // 上传到oss
        $url = '';
        $ossSetting = getSystemSetting(0, 'storage');
        $engine = $ossSetting['engine'] ?? 'local';
        if ($engine == 'alioss') {
            $alioss = new \FileStorage\alioss([
                'access_key_id' => $ossSetting['alioss_access_key_id'],
                'access_key_secret' => $ossSetting['alioss_access_key_secret'],
                'endpoint' => $ossSetting['alioss_region'],
                'bucket' => $ossSetting['alioss_bucket'],
                'domain' => $ossSetting['alioss_domain'] ?? ''
            ]);
            $putDir = ltrim($filepath, './');
            $result = $alioss->upload($putDir, $filepath);
            if ($result['errno']) {
                Log::write($result['message'], 'upload to alioss error');
            } else {
                $url = $result['url'];
                @unlink($filepath);
            }
        } elseif ($engine == 'txcos') {
            $txcos = new \FileStorage\txcos([
                'secret_id' => $ossSetting['txcos_secret_id'],
                'secret_key' => $ossSetting['txcos_secret_key'],
                'region' => $ossSetting['txcos_region'],
                'bucket' => $ossSetting['txcos_bucket'],
                'domain' => $ossSetting['txcos_domain'] ?? ''
            ]);
            $putDir = ltrim($filepath, './');
            $result = $txcos->upload($putDir, $filepath);
            if ($result['errno']) {
                Log::write($result['message'], 'upload to txcos error');
            } else {
                $url = $result['url'];
                @unlink($filepath);
            }
        } elseif ($engine == 'qiniu') {
            $qiniu = new \FileStorage\qiniu([
                'access_key' => $ossSetting['qiniu_access_key'],
                'secret_key' => $ossSetting['qiniu_secret_key'],
                'region' => $ossSetting['qiniu_region'],
                'bucket' => $ossSetting['qiniu_bucket'],
                'domain' => $ossSetting['qiniu_domain'] ?? ''
            ]);
            $putDir = ltrim($filepath, './');
            $result = $qiniu->upload($putDir, $filepath);
            if ($result['errno']) {
                Log::write($result['message'], 'upload to qiniu error');
            } else {
                $url = $result['url'];
                @unlink($filepath);
            }
        }

        if ($engine == 'local' || empty($url)) {
            $url = mediaUrl($filepath, true);
        }

        return $url;
    }

    /**
     * 从key池里取回一个key
     */
    private function getApiKey($type = 'openai')
    {
        $rs = Db::name('keys')
            ->where([
                ['site_id', '=', self::$site_id],
                ['type', '=', $type],
                ['state', '=', 1]
            ])
            ->order('last_time asc, id asc')
            ->find();
        if (!$rs) {
            return '';
        }
        Db::name('keys')
            ->where('id', $rs['id'])
            ->update([
                'last_time' => time()
            ]);
        return $rs['key'];
    }

    private function httpRequest($url, $post = '')
    {
        $token = session_id();
        $header = [
            'x-token: ' . $token ?? ''
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_TIMEOUT_MS, 1000);
        if ($post) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        }
        curl_exec($ch);
        curl_close($ch);
    }

}
