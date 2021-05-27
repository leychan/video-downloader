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
    }

    public function run() {
        $this->checkVideoProperty();
        $this->download();
    }

    public function download() {
        $multi_seg = count($this->video->real_url);
        $extra_options = [];
        if (!empty($this->video->proxy)) {
            $extra_options = ['proxy' => $this->video->proxy];
        }
        for ($i = 0; $i < $multi_seg; $i++) {
            $save_to = $this->video->save_dir . $i . '.flv';
            $this->request->download($this->video->real_url[$i], $this->video->header, $save_to, $extra_options);
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