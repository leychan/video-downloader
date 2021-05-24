<?php

namespace video;

interface VideoParser
{
    /**
     * @desc 配置
     * @user lei
     * @date 2021/3/2
     * @return mixed
     */
    public function setConfig();

    /**
     * @desc 根据url解析到视频真实url
     * @user lei
     * @date 2021/3/2
     * @return mixed
     */
    public function parseUrl(string $url);

    /**
     * @desc 设置存储路径
     * @user lei
     * @date 2021/3/3
     * @param string $save_dir
     * @return mixed
     */
    public function setSaveDir(string $save_dir);

    /**
     * @desc 设置是否分离音频
     * @user chenlei11
     * @date 2021/5/19
     * @param bool $separate_audio
     * @return mixed
     */
    public function setSeparateAudio(bool $separate_audio);

    /**
     * @desc 设置音频标题
     * @user chenlei11
     * @date 2021/5/24
     * @param string $audio_title
     * @return mixed
     */
    public function setAudioTitle(string $audio_title);

    public function makeVideo(): Video;

    public function setCookie(array $cookie);
}