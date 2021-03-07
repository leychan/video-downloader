<?php

namespace video\website;

use video\Config;
use video\Request;
use video\Video;
use video\VideoUrlParser;


class Base
{
    /**
     * @var string 要解析的url, 即b站打开视频页面的链接
     */
    public string $web_url;

    /**
     * @var string cookie值, 必须
     */
    public array $cookie;

    public Request $request;

    /**
     * @var string 视频标题
     */
    public string $title;

    /**
     * @var array 视频的真实地址集合
     */
    public array $real_url;

    /**
     * @var string[] 请求头
     */
    public array $header;

    /**
     * 默认的子路径文件夹名
     */
    const SAVE_CHILD_DIR = '';

    /**
     * 配置文件中数组的key
     */
    const CONFIG_KEY = '';

    /**
     * 配置文件中必须包含的键
     */
    const SHOULD_CHECK_ARR = [];

    /**
     * @var string 文件存储路径
     */
    public string $save_dir;

    /**
     * @var bool 是否分离音频
     */
    public bool $is_sep_audio = false;

    public function __construct()
    {
        $this->request = new Request();
    }

    public function setSaveDir(string $save_dir)
    {
        if (substr($save_dir, -1, 1) == '/') {
            $save_dir = substr($save_dir, 0, strlen($save_dir) - 1);
        }
        $this->save_dir = $save_dir . DIRECTORY_SEPARATOR . 'video/' . static::SAVE_CHILD_DIR . DIRECTORY_SEPARATOR . $this->title . DIRECTORY_SEPARATOR;
        if (!is_dir($this->save_dir)) {
            mkdir($this->save_dir, 0777, true);
        }
    }

    public function setCookie(array $cookie) {
        $this->cookie = $cookie;
    }

    public function setConfig()
    {
        $config_key = static::CONFIG_KEY;
        $config = Config::getConfig($config_key);

        if (empty($config)) {
            throw new \Exception("the config of {$config_key} is not exists");
        }
        foreach (static::SHOULD_CHECK_ARR as $val) {
            if (empty($config[$val])) {
                throw new \Exception("lost config key {$val}");
            }
            $this->$val = $config[$val];
        }
    }

    public function makeVideo(): Video
    {
        $video = new Video();
        $video->title = $this->title;
        $video->header = $this->header;
        $video->save_dir = $this->save_dir;
        $video->real_url = $this->real_url;
        $video->need_merge = count($this->real_url) > 1;
        $video->separate_audio = true;
        return $video;
    }
}