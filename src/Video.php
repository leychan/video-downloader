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
    public string $save_to = './';

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
     * @var bool 是否合并
     */
    public bool $need_merge = false;

    /**
     * @var string 合成视频的具体路径
     */
    public string $specific_path = '';

    /**
     * @desc 从视频分离音频
     * @user lei
     * @date 2021/3/5
     * @return string
     */
    public function separateAudio() :string {
        //如果只有一个片段,或者有具体的合成的
        if (!empty($video->specific_path)) {
            self::separateAudioByVideo($video->specific_path);
        } else if (count($this->real_url) == 1) {
            $this->specific_path = $this->save_to . '/0.flv';
            self::separateAudioByVideo($this->specific_path);
        } else {
            throw new \Exception('视频还未合并, 请合并后再进行音频分离');
        }
        //todo
        return '';
    }

    /**
     * @desc 从具体的视频, 分离出音频
     * @user lei
     * @date 2021/3/5
     * @param string $path
     * @return array
     */
    protected static function separateAudioByVideo(string $path) :array {
        exec("ffmpeg -i {$path} out.mp3", $output);
        return $output;
    }

    public static function merge(Video $video) :string {

    }
}