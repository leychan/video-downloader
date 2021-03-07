<?php


namespace video;


class Parser
{
    /**
     * @var VideoUrlParser 视频链接解析器
     */
    protected VideoUrlParser $p;

    /**
     * @var string 要解析的url, 即站点打开视频页面的链接
     */
    protected string $web_url;

    /**
     * @var string 默认的存储路径
     */
    protected string $default_save_dir = __DIR__;

    public function __construct(VideoUrlParser $p, string $web_url) {
        $this->p = $p;
        $this->web_url = $web_url;
    }

    public function doParser(array $cookie, string $save_dir) :Video {
        $save_dir = empty($save_dir) ? $this->default_save_dir : $save_dir;
        $this->p->setCookie($cookie);
        $this->p->parseUrl($this->web_url);
        $this->p->setSaveDir($save_dir);
        return $this->p->makeVideo();
    }
}