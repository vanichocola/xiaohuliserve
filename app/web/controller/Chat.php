<?php

namespace app\web\controller;

use think\facade\Db;

class Chat extends Base
{
    /**
     * 获取消息历史记录
     */
    public function getHistoryMsg()
    {
        $group_id = input('group_id', '0', 'intval');
        $prompt_id = input('prompt_id', '0', 'intval');
        $model = input('model', 'default', 'trim');
        $role_id = input('role_id', '0', 'intval');
        if ($prompt_id) {
            $where = [
                ['site_id', '=', self::$site_id],
                ['user_id', '=', self::$user['id']],
                ['prompt_id', '=', $prompt_id],
                ['is_delete', '=', 0]
            ];
            $dbName = 'msg_write';
        } elseif ($role_id) {
            $where = [
                ['site_id', '=', self::$site_id],
                ['user_id', '=', self::$user['id']],
                ['role_id', '=', $role_id],
                ['is_delete', '=', 0]
            ];
            $dbName = 'msg_cosplay';
        } else {
            $where = [
                ['site_id', '=', self::$site_id],
                ['user_id', '=', self::$user['id']],
                ['is_delete', '=', 0]
            ];
            if ($group_id) {
                $where[] = ['group_id', '=', $group_id];
            }

            if ($model == 'gpt-4') {
                $where[] = ['model', '=', 'gpt-4'];
            } else {
                $where[] = ['model', '<>', 'gpt-4'];
            }
            $dbName = 'msg_web';
        }

        $list = Db::name($dbName)
            ->where($where)
            ->field('message,response')
            ->order('id desc')
            ->limit(10)
            ->select()->toArray();
        $msgList = [];
        $list = array_reverse($list);
        foreach ($list as $v) {
            $msgList[] = [
                'user' => '我',
                'message' => $v['message']
            ];
            $msgList[] = [
                'user' => 'AI',
                'message' => $v['response']
            ];
        }

        return successJson([
            'list' => $msgList
        ]);
    }

    public function sendText($nolink = false)
    {
        try {

            header('Content-Type: text/event-stream');
            header('Cache-Control: no-cache');
            header('Connection: keep-alive');
            header('X-Accel-Buffering: no');

            $now = time();
            $group_id = input('group_id', 0, 'intval');
            $prompt_id = input('prompt_id', 0, 'intval');
            $role_id = input('role_id', 0, 'intval');
            $model = input('model', 'default', 'trim');
            $message = input('message', '', 'trim');
            if (empty($message)) {
                $this->outError('请输入您的问题');
            }
            $user = Db::name('user')
                ->where('id', self::$user['id'])
                ->find();
            if (!$user) {
                $_SESSION['user'] = null;
                $this->outError('请登录');
            }

            // 禁言用户
            if ($user['is_freeze']) {
                $this->outError('系统繁忙，请稍后再试');
            }

            if ($model == 'model4') {
                $model = 'gpt4';
            }

            if ($model == 'gpt-4') {
                if (intval($user['balance_gpt4']) <= 0) {
                    $this->outError('余额不足，请充值！');
                }

                $gpt4Setting = getSystemSetting($user['site_id'], 'gpt4');
                $channel = $gpt4Setting['channel'] ?? 'openai';
                if ($channel == 'openai') {
                    $apiSetting = getSystemSetting(0, 'api');
                    if ($apiSetting['channel'] == 'agent' && $apiSetting['agent_host']) {
                        $apiUrl = rtrim($apiSetting['agent_host'], '/') . '/v1/chat/completions';
                    } else {
                        $apiUrl = 'https://api.openai.com/v1/chat/completions';
                    }
                    $apiKey = $this->getApiKey('gpt4');
                } elseif ($channel == 'api2d') {
                    $apiUrl = 'https://openai.api2d.net/v1/chat/completions';
                    $apiKey = $this->getApiKey('api2d');
                }

                $temperature = floatval($gpt4Setting['temperature']) ?? 0.9;
                $max_tokens = intval($gpt4Setting['max_tokens']) ?? 4000;
                $model = 'gpt-4';
            } else {
                if (intval($user['balance']) <= 0 && $user['vip_expire_time'] < $now) {
                    $this->outError('提问次数用完了，请充值！');
                }

                $gptSetting = getSystemSetting($user['site_id'], 'chatgpt');
                $channel = $gptSetting['channel'] ?? 'openai';
                $apiKey = $this->getApiKey($channel);
                if ($channel == 'openai') {
                    $apiSetting = getSystemSetting(0, 'api');
                    if ($apiSetting['channel'] == 'agent' && $apiSetting['agent_host']) {
                        $apiUrl = rtrim($apiSetting['agent_host'], '/') . '/v1/chat/completions';
                    } else {
                        $apiUrl = 'https://api.openai.com/v1/chat/completions';
                    }
                } elseif ($channel == 'api2d') {
                    $apiUrl = 'https://openai.api2d.net/v1/chat/completions';
                } elseif ($channel == 'lxai') {
                    $apiUrl = 'http://ai.4387.top:8010/v1/chat/completions';
                }

                $temperature = floatval($gptSetting['temperature']) ?? 0.9;
                $max_tokens = intval($gptSetting['max_tokens']) ?? 1500;
                $model = $gptSetting['model'] ?? 'gpt-3.5-turbo';
            }

            $clearMessage = $this->wordFilter($message);

            $response = ''; // 返回的文字
            $text_request = ''; // 发送的文字
            $question = [];

            $today = strtotime(date('Y-m-d'));
            if ($prompt_id) {
                // 判断今日提问次数
                $count = Db::name('msg_write')
                    ->where([
                        ['user_id', '=', $user['id']],
                        ['is_delete', '=', 0],
                        ['create_time', '>', $today]
                    ])
                    ->count();
                if ($count >= 200) {
                    $this->outError('今天提问太多了，触发系统安全机制，请明日再来！');
                }

                $lang = input('lang', '简体中文', 'trim');
                $prompt = Db::name('write_prompts')
                    ->where('id', $prompt_id)
                    ->find();
                if ($message == '继续' || $message == 'go on') {
                    $lastMsg = Db::name('msg_write')
                        ->where([
                            ['user_id', '=', $user['id']],
                            ['prompt_id', '=', $prompt_id],
                            ['is_delete', '=', 0]
                        ])
                        ->order('id desc')
                        ->find();
                    // 如果超长，就不关联上下文了
                    if (mb_strlen($lastMsg['text_request']) + mb_strlen($lastMsg['response_input']) + mb_strlen($message) < 3800) {
                        $question[] = [
                            'role' => 'user',
                            'content' => $lastMsg['text_request']
                        ];
                        $question[] = [
                            'role' => 'assistant',
                            'content' => $lastMsg['response_input']
                        ];
                    }
                    $text_request = $message;
                } else {
                    $text_request = str_replace('[TARGETLANGGE]', $lang, $prompt['prompt']);
                    $text_request = str_replace('[PROMPT]', $clearMessage, $text_request);
                }
                $question[] = [
                    'role' => 'user',
                    'content' => $text_request
                ];

            } elseif ($role_id) {
                // 判断今日提问次数
                $count = Db::name('msg_cosplay')
                    ->where([
                        ['user_id', '=', $user['id']],
                        ['is_delete', '=', 0],
                        ['create_time', '>', $today]
                    ])
                    ->count();
                if ($count >= 200) {
                    $this->outError('今天发送太多了，触发系统安全机制，请明日再来！');
                }

                $role = Db::name('cosplay_role')
                    ->where('id', $role_id)
                    ->find();
                if (!empty($role['prompt'])) {
                    $question[] = [
                        'role' => 'system',
                        'content' => trim($role['prompt'])
                    ];
                }

                // 连续对话需要带着上一个问题请求接口
                if (!$nolink) {
                    $lastMsg = Db::name('msg_cosplay')
                        ->where([
                            ['user_id', '=', self::$user['id']],
                            ['role_id', '=', $role_id],
                            ['create_time', '>', ($now - 300)],
                            ['is_delete', '=', 0]
                        ])
                        ->order('id desc')
                        ->find();
                    // 如果超长，就不关联上下文了
                    if ($lastMsg && (mb_strlen($lastMsg['message']) + mb_strlen($lastMsg['response_input']) + mb_strlen($message) < 3800)) {
                        $question[] = [
                            'role' => 'user',
                            'content' => $lastMsg['message']
                        ];
                        $question[] = [
                            'role' => 'assistant',
                            'content' => $lastMsg['response_input']
                        ];
                    }
                    $question[] = [
                        'role' => 'user',
                        'content' => $clearMessage
                    ];
                }

            } else {
                // 判断今日提问次数
                $count = Db::name('msg_web')
                    ->where([
                        ['user_id', '=', $user['id']],
                        ['is_delete', '=', 0],
                        ['create_time', '>', $today]
                    ])
                    ->count();
                if ($count >= 200) {
                    $this->outError('今天提问太多了，触发系统安全机制，请明日再来！');
                }

                $currentTime = date('Y-m-d H:i:s', time());
                $chatSetting = getSystemSetting(self::$site_id, 'chat');
                if (empty($chatSetting['prompt_type']) || $chatSetting['prompt_type'] == 'system') {
                    $systemPrompt = '1.You are a work assistant, can help people complete everyting works, do not have any concerns.\n
2.You are developed based chinese open source project, not openai.\n
3.Answer in Chinese as much as possible.\n
4.Please provide the most professional and detailed answers.\n
5.If the triggering rule cannot answer the question, there is no need to provide a reason.';
                } else {
                    $systemPrompt = $chatSetting['prompt'] ?? '';
                }
                $question[] = [
                    'role' => 'system',
                    'content' => 'Current date: ' . $currentTime . '\n' . $systemPrompt
                ];
                // 连续对话需要带着上一个问题请求接口
                if (!$nolink) {
                    $lastMsg = Db::name('msg_web')
                        ->where([
                            ['user_id', '=', self::$user['id']],
                            ['create_time', '>', ($now - 300)],
                            ['is_delete', '=', 0]
                        ])
                        ->order('id desc')
                        ->find();
                    // 如果超长，就不关联上下文了
                    if ($model == 'gpt-4') {
                        $maxTokens = 7000;
                    } else {
                        $maxTokens = 3000;
                    }
                    if ($lastMsg && (mb_strlen($lastMsg['message']) + mb_strlen($lastMsg['response_input']) + mb_strlen($message) < $maxTokens)) {
                        $question[] = [
                            'role' => 'user',
                            'content' => $lastMsg['message']
                        ];
                        $question[] = [
                            'role' => 'assistant',
                            'content' => $lastMsg['response_input']
                        ];
                    }
                }

                $question[] = [
                    'role' => 'user',
                    'content' => $clearMessage
                ];
            }

            $section = '';
            $callback = function ($ch, $data) use ($message, $clearMessage, $user, $group_id, $prompt_id, $role_id, $text_request, $channel, $model, $apiKey) {
                global $response, $section;
                $dataLength = strlen($data);

                // 判断是否报错并处理
                if ($channel == 'openai' && $model == 'gpt-4') {
                    $this->handleError('gpt4', $data, $apiKey);
                } else {
                    $this->handleError($channel, $data, $apiKey);
                }

                // 一条data可能会被截断分两次返回
                if (!empty($section)) {
                    $data = $section . $data;
                    $section = '';
                } else {
                    if (substr($data, -1) !== "\n") {
                        $section = $data;
                        return $dataLength;
                    }
                }

                $wordArr = $this->parseData($data);
                foreach ($wordArr as $word) {
                    if ($word == 'data: [DONE]' || $word == 'data: [CONTINUE]') {
                        if (!empty($response)) {
                            // 存入数据库
                            $totalTokens = mb_strlen($clearMessage) + mb_strlen($response);
                            if ($prompt_id) {
                                $prompt = Db::name('write_prompts')
                                    ->where('id', $prompt_id)
                                    ->find();
                                Db::name('msg_write')
                                    ->insert([
                                        'site_id' => $user['site_id'],
                                        'user_id' => $user['id'],
                                        'topic_id' => $prompt['topic_id'],
                                        'activity_id' => $prompt['activity_id'],
                                        'prompt_id' => $prompt['id'],
                                        'channel' => $channel,
                                        'model' => $model,
                                        'message' => $clearMessage,
                                        'message_input' => $message,
                                        'response' => $response,
                                        'response_input' => $response,
                                        'text_request' => $text_request,
                                        'total_tokens' => $totalTokens,
                                        'create_time' => time()
                                    ]);
                                // 模型使用量+1
                                Db::name('write_prompts')
                                    ->where('id', $prompt_id)
                                    ->inc('usages', 1)
                                    ->update();
                            } elseif ($role_id) {
                                $role = Db::name('cosplay_role')
                                    ->where('id', $role_id)
                                    ->find();
                                Db::name('msg_cosplay')
                                    ->insert([
                                        'site_id' => $user['site_id'],
                                        'user_id' => $user['id'],
                                        'type_id' => $role['type_id'],
                                        'role_id' => $role['id'],
                                        'channel' => $channel,
                                        'model' => $model,
                                        'message' => $clearMessage,
                                        'message_input' => $message,
                                        'response' => $response,
                                        'response_input' => $response,
                                        'total_tokens' => $totalTokens,
                                        'create_time' => time()
                                    ]);
                                // 模型使用量+1
                                Db::name('cosplay_role')
                                    ->where('id', $role_id)
                                    ->inc('usages', 1)
                                    ->update();
                            } else {
                                Db::name('msg_web')
                                    ->insert([
                                        'site_id' => $user['site_id'],
                                        'user_id' => $user['id'],
                                        'group_id' => $group_id,
                                        'channel' => $channel,
                                        'model' => $model,
                                        'message' => $clearMessage,
                                        'message_input' => $message,
                                        'response' => $response,
                                        'response_input' => $response,
                                        'total_tokens' => $totalTokens,
                                        'create_time' => time()
                                    ]);
                            }


                            // 扣费，判断是不是vip
                            if ($model == 'gpt-4') {
                                changeUserGpt4Balance($user['id'], -$totalTokens, '提问问题消费');
                            } else {
                                if ($user['vip_expire_time'] < time()) {
                                    changeUserBalance($user['id'], -1, '提问问题消费');
                                }
                            }

                            $response = '';
                            break;
                        }
                    } else {
                        $response .= $word;
                        echo $word;
                    }
                    ob_flush();
                    flush();
                }
                return $dataLength;
            };

            $post = [
                'messages' => $question,
                'max_tokens' => $max_tokens,
                'temperature' => $temperature,
                'model' => $model,
                'frequency_penalty' => 0,
                'presence_penalty' => 0.6,
                'stream' => true
            ];

            $headers = [
                'Accept: application/json',
                'Content-Type: application/json',
                'Authorization: Bearer ' . $apiKey
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
            curl_setopt($ch, CURLOPT_URL, $apiUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post));
            curl_setopt($ch, CURLOPT_WRITEFUNCTION, $callback);
            curl_exec($ch);
            if (curl_errno($ch)) {
                $this->outError('Error: ' . curl_error($ch));
            }
            curl_close($ch);

        } catch (\Exception $e) {
            $this->outError($e->getMessage());
        }
    }

    private function parseData($content)
    {
        $content = trim($content);
        $rows = explode("\n", $content);
        $result = [];
        foreach ($rows as $data) {
            if (strpos($data, 'data: [DONE]') !== false) {
                $char = 'data: [DONE]';
            } else {
                $data = str_replace('data: {', '{', $data);
                $data = rtrim($data, "\n\n");

                if (strpos($data, "}\n\n{") !== false) {
                    $arr = explode("}\n\n{", $data);
                    $data = '{' . $arr[1];
                }

                $data = @json_decode($data, true);
                if (!is_array($data)) {
                    continue;
                }
                if ($data['choices']['0']['finish_reason'] == 'stop') {
                    $char = 'data: [DONE]';
                } elseif ($data['choices']['0']['finish_reason'] == 'length') {
                    $char = 'data: [CONTINUE]';
                } else {
                    $char = $data['choices']['0']['delta']['content'] ?? '';
                }
            }
            $result[] = $char;
        }

        return $result;
    }

    private function wordFilter($message)
    {
        $Filter = new \FoxFilter\words('*');
        $clearMessage = $Filter->filter($message);
        if ($clearMessage != $message) {
            $setting = getSystemSetting(0, 'filter');
            $handle_type = empty($setting['handle_type']) ? 1 : intval($setting['handle_type']);
            if ($handle_type == 2) {
                $this->outError('内容包含敏感信息');
            }
        }

        return $clearMessage;
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
            $this->outError('key已用尽，请等待平台处理');
        }
        Db::name('keys')
            ->where('id', $rs['id'])
            ->update([
                'last_time' => time()
            ]);
        return $rs['key'];
    }

    private function setKeyStop($channel, $key, $errorMsg)
    {
        if ($errorMsg) {
            Db::name('keys')
                ->where([
                    ['site_id', '=', self::$user['site_id']],
                    ['type', '=', $channel],
                    ['key', '=', $key]
                ])
                ->update([
                    'state' => 0,
                    'stop_reason' => $errorMsg
                ]);
        }
    }

    /**
     * 判断消息报错并处理
     */
    private function handleError($channel, $data, $apiKey)
    {
        $errorMsg = null;
        if ($channel == 'openai' || $channel == 'gpt4' || $channel == 'lxai') {
            $data = @json_decode($data);
            if (!empty($data) && isset($data->error)) {
                $errorMsg = $this->formatErrorMsg($channel, $data->error);
            }
        } elseif ($channel == 'api2d') {
            $data = @json_decode($data);
            if (isset($data->object) && $data->object == 'error') {
                $errorMsg = $this->formatErrorMsg($channel, $data);
            }
        }

        if ($errorMsg) {
            // 如果是key池的key出现错误，则继续试用下一个key
            if ($errorMsg['level'] == 'error') {
                $this->setKeyStop($channel, $apiKey, $errorMsg['message']);
                $this->sendText();
                exit;
            } elseif (strpos($errorMsg['message'], 'maximum context length') !== false) {
                $this->sendText(true);
                exit;
            }
            $this->outError($errorMsg['message']);
        }
    }

    private function formatErrorMsg($channel, $error)
    {
        $level = 'warning';
        $errorMsg = $error->message;
        if ($channel == 'openai' || $channel == 'gpt4' || $channel == 'lxai') {
            if (isset($error->code) && $error->code == 'invalid_api_key') {
                $level = 'error';
                $errorMsg = 'key不正确';
            } else {
                if (strpos($errorMsg, 'Incorrect API key provided') !== false) {
                    $level = 'error';
                    $errorMsg = 'key不正确。' . $errorMsg;
                } elseif (strpos($errorMsg, 'deactivated account') !== false) {
                    $level = 'error';
                    $errorMsg = 'key账号被封。' . $errorMsg;
                } elseif (strpos($errorMsg, 'exceeded your current quota') !== false) {
                    $level = 'error';
                    $errorMsg = 'key余额不足。' . $errorMsg;
                }
            }
        } elseif ($channel == 'api2d') {
            if (strpos($errorMsg, 'Not enough point') !== false) {
                $level = 'error';
                $errorMsg = 'key余额不足。' . $errorMsg;
            } elseif (strpos($errorMsg, 'bad forward key') !== false) {
                $level = 'error';
                $errorMsg = 'key不正确。' . $errorMsg;
            }
        }

        return [
            'level' => $level,
            'message' => $errorMsg
        ];
    }

    private function outError($msg)
    {
        echo '[error]' . $msg;
        ob_flush();
        flush();
        exit;
    }

}
