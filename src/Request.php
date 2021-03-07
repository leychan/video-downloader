<?php

namespace video;

class Request
{
    protected $client;

    public function __construct() {
        $this->client = new \GuzzleHttp\Client();
    }

    public function getClient() {
        return $this->client;
    }

    /**
     * @desc 下载视频并且保存到指定地址
     * @user lei
     * @date 2021/3/2
     * @param string $url 下载视频的地址
     * @param array $header 请求头
     * @param string $save_to 保存地址
     * @param array $extra 其他请求选项
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function download(string $url, array $header, string $save_to, array $extra = []) {
        $this->client->get($url, array_merge([
            'sink' => $save_to,
            'headers' => $header
        ], $extra));
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