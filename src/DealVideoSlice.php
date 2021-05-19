<?php


namespace video;


class DealVideoSlice {
    private Video $video;
    
    public function __construct(Video $video) {
        $this->video = $video;
    }

    /**
     * @desc 从视频分离音频
     * @user lei
     * @date 2021/3/5
     */
    public function separateAudio() {
        if (!$this->video->separate_audio) {
            return;
        }
        //如果只有一个片段,或者有具体的合成的
        if (!empty($this->video->specific_path)) {
            self::separateAudioByVideo($this->video->specific_path, $this->video->save_dir);
        } else if (count($this->video->real_url) == 1) {
            $this->video->specific_path = $this->video->save_dir . '0.flv';
            self::separateAudioByVideo($this->video->specific_path, $this->video->save_dir);
        } else {
            throw new \Exception('视频还未合并, 请合并后再进行音频分离');
        }
    }

    /**
     * @desc 从具体的视频, 分离出音频
     * @user lei
     * @date 2021/3/5
     * @param string $path
     * @param string $save_dir
     * @return array
     */
    protected static function separateAudioByVideo(string $path, string $save_dir) :array {
        $shell = "ffmpeg -i '{$path}' '{$save_dir}out.mp3'";
        exec($shell, $output);
        return $output;
    }

    /**
     * @desc 合并视频
     * @user lei
     * @date 2021/3/9
     */
    public function merge() {
        if (!$this->video->need_merge) {
            return;
        }
        //如果存在
        $file_path = "{$this->video->save_dir}list.txt";
        if (file_exists($file_path)) {
            unlink($file_path);
        }
        //生成待合并的视频列表, 用作后面的合并
        $shell_file_list = 'for f in ' . $this->video->save_dir . '*.flv; do echo "file \'$f\'" >> ' . $file_path .  '; done';
        exec($shell_file_list);
        //合并视频
        $shell_merge = "ffmpeg -f concat -safe 0 -i {$this->video->save_dir}list.txt -c copy {$this->video->save_dir}merged.flv";
        exec($shell_merge);
        $this->video->specific_path = "{$this->video->save_dir}merged.flv";
    }

    public function run() {
        //合并视频
        $this->merge();

        //分离音频
        var_dump($this->video);
        $this->separateAudio();
    }
}