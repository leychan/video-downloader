<?php

namespace video;

class Video
{
    /**
     * @var string 视频的标题
     */
    public string $title = '';

    /**
     * @var string 视频存储路径
     */
    public string $save_dir = './';

    /**
     * @var array 需要下载的视频路径合集
     */
    public array $real_url = [];

    /**
     * @var array 下载视频的请求头
     */
    public array $header = [];

    /**
     * @var array 下载视频的其他请求选项
     */
    public array $extra_option = [];

    /**
     * @var bool 是否分离音频
     */
    public bool $separate_audio = false;

    /**
     * @var string 音频名称
     */
    public string $audio_title = '';

    public string $audio_title_default = 'out';

    /**
     * @var bool 是否合并
     */
    public bool $need_merge = false;

    /**
     * @var string 合成视频的具体路径
     */
    public string $specific_path = '';
}