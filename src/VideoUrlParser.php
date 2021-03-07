<?php

namespace video;

interface VideoUrlParser
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

    public function makeVideo(): Video;

    public function setCookie(array $cookie);
}