<?php


namespace video;


class Parser
{
    /**
     * @var VideoParser 视频链接解析器
     */
    protected VideoParser $p;

    /**
     * @var string 默认的存储路径
     */
    protected string $default_save_dir = __DIR__;

    public function __construct(VideoParser $p) {
        $this->p = $p;
    }

    public function run(string $web_url, array $cookie, string $save_dir, bool $separate_audio) :Video {
        $save_dir = empty($save_dir) ? $this->default_save_dir : $save_dir;
        $this->p->setCookie($cookie);
        $this->p->parseUrl($web_url);
        $this->p->setSeparateAudio($separate_audio);
        $this->p->setSaveDir($save_dir);
        return $this->p->makeVideo();
    }
}