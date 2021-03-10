<?php

namespace video;

use GuzzleHttp\Client;
use League\CLImate\CLImate;

class Request
{
    protected Client $client;
    protected CLImate $cli;

    public function __construct() {
        $this->client = new \GuzzleHttp\Client();
        $this->cli = new CLImate();
    }


    /**
     * @desc 下载视频并且保存到指定地址
     * @user lei
     * @date 2021/3/2
     * @param string $url 下载视频的地址
     * @param array $header 请求头
     * @param string $save_to 保存地址
     * @param array $extra 其他请求选项
     * @param bool $with_progress 下载进度
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function download(string $url, array $header, string $save_to, array $extra = [], bool $with_progress = true) {
        $options = array_merge([
            'sink' => $save_to,
            'headers' => $header,
        ], $extra);
        $progress_option = [];
        if ($with_progress) {
            $progress_option = $this->processBarOption();
        }
        $this->client->get($url, array_merge($options, $progress_option));
    }

    /**
     * @desc 下载进度配置
     * @user lei
     * @date 2021/3/10
     * @return \Closure[]
     */
    public function processBarOption() :array {
        $this->cli->out('当前下载进度:');
        $progress = $this->cli->progress()->total(100);
        $option = [
            'progress' => function($total, $downloaded) use ($progress) {
                if ($total > 0 && $downloaded > 0) {
                    $cur = (int)($downloaded / $total * 100);
                    $progress->current($cur);
                }
            }
        ];
        return $option;
    }

    /**
     * @desc 根据给定的地址去获取数据
     * @user lei
     * @date 2021/3/2
     * @param string $url 请求的地址
     * @param array $option
     * @param bool $return_array 是否返回数组形式
     * @return mixed|\Psr\Http\Message\StreamInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function get(string $url, array $option, bool $return_array = true) {
        $res = $this->client->request('GET', $url, $option);
        $body = $res->getBody();
        if ($return_array) {
            return json_decode($body, true);
        }
        return $body;
    }

    public static function makeCookies(array $data, string $domain) {
        return \GuzzleHttp\Cookie\CookieJar::fromArray($data, $domain);
    }
}