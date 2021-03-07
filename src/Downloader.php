<?php

namespace video;

class Downloader
{
    protected Video $video;
    protected Request $request;
    const PROPERTY_CHECK = [
        'title', 'real_url'
    ];

    public function __construct(Video $video)
    {
        $this->video = $video;
        $this->request = new Request();
        $this->checkVideoProperty();
    }

    public function download() {
        $multi_seg = count($this->video->real_url);
        for ($i = 0; $i < $multi_seg; $i++) {
            $save_to = $this->video->save_to . $i . '.flv';
            echo $save_to, PHP_EOL;
            $this->request->download($this->video->real_url[0], $this->video->header, $save_to);
        }

    }

    protected function checkVideoProperty() {
        foreach (self::PROPERTY_CHECK as $val) {
            if (empty($this->video->$val)) {
                throw new \Exception("property {$val} can not be empty");
            }
        }
    }
}