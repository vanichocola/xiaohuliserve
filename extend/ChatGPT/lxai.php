<?php
 
namespace ChatGPT;

class lxai
{
    protected static $model = 'gpt-3.5-turbo';
    protected static $apiKey = '';
    protected static $temperature = 1;
    protected static $max_tokens = 1000;
    protected static $apiHost = 'http://ai.4387.top:8010';
    protected static $pageStartTime = 0;

    /**
     * sdk constructor.
     * @param string $apiKey
     * @param string $model
     * @param string $temperature
     * @param string $max_tokens
     */
    public function __construct($apiKey = '', $model = '', $temperature = '', $max_tokens = '')
    {
        if ($model) {
            self::$model = $model;
        }
        if ($temperature) {
            self::$temperature = $temperature;
        }
        if ($max_tokens) {
            self::$max_tokens = $max_tokens;
        }
        self::$apiKey = $apiKey;
    }

    public function drawMJ($option = [])
    {
        self::$pageStartTime = microtime(true);

        $url = 'http://ai.4387.top:8606/mj/trigger/submit';
        $post = [
            'action' => 'IMAGINE',
            'prompt' => $option['prompt']
        ];

        $result = $this->httpRequest($url, json_encode($post));
        if (!empty($result['code']) && $result['code'] == 1) {
            return $this->queryDrawResult($result['result']);
        } else {
            return [
                'errno' => 1,
                'message' => $result['description'] ?? '任务提交失败'
            ];
        }
    }

    public function drawSD($option = [])
    {
        self::$pageStartTime = microtime(true);

        $url = 'http://ai.4387.top:8606/mj/trigger/submit';
        $post = [
            'action' => 'IMAGINE',
            'prompt' => $option['prompt']
        ];

        $result = $this->httpRequest($url, json_encode($post));
        if (!empty($result['code']) && $result['code'] == 1) {
            return $this->queryDrawResult($result['result']);
        } else {
            return [
                'errno' => 1,
                'message' => $result['description'] ?? '任务提交失败'
            ];
        }
    }

    /**
     * @return array|mixed
     * 查询账户余额
     */
    public function getBalance()
    {
        $now = time();
        $startDate = date('Y-m-01', $now);
        $endDate = date('Y-m-d', $now);

        $usageUrl = self::$apiHost . '/v1/dashboard/billing/usage?start_date=' . $startDate . '&end_date=' . $endDate;
        $subUrl = self::$apiHost . '/v1/dashboard/billing/subscription';


        $result = $this->httpRequest($usageUrl);
        $total_used = round($result['total_usage'] / 100, 3);

        $result = $this->httpRequest($subUrl);
        $total_granted = round($result['hard_limit_usd'], 3);
        $total_available = round(($total_granted * 1000 - $total_used * 1000) / 1000, 3);

        return [
            'total_granted' => $total_granted,
            'total_used' => $total_used,
            'total_available' => $total_available
        ];
    }

    private function queryDrawResult($task_id)
    {
        $url = 'http://ai.4387.top:8606/mj/task/' . $task_id . '/fetch';

        $result = $this->httpRequest($url);
        if (!empty($result) && isset($result['status'])) {
            if ($result['status'] === 'SUCCESS') {
                return [
                    'errno' => 0,
                    'data' => [$result['imageUrl']]
                ];
            } elseif ($result['status'] === 'FAILURE') {
                return [
                    'errno' => 1,
                    'message' => $result['description'] ?? ''
                ];
            }
        }

        $runtime = $this->getRunTime();
        if ($runtime < 180) {
            usleep(5000000);
            return $this->queryDrawResult($task_id);
        }
        return [
            'errno' => 1,
            'message' => '生成失败.'
        ];
    }

    private function getRunTime()
    {
        $etime = microtime(true);
        $total = $etime - self::$pageStartTime;
        return round($total, 4);
    }

    /**
     * http请求
     */
    protected function httpRequest($url, $post = '')
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . self::$apiKey
        ]);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        if ($post) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        }
        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            return [
                'errno' => 1,
                'message' => 'curl 错误信息: ' . curl_error($ch)
            ];
        }
        curl_close($ch);
        return json_decode($result, true);
    }
}
