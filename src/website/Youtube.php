<?php


namespace video\website;


use video\Config;

class Youtube extends Base {

    /**
     * @var string 获取和视频相关的信息
     */
    public string $get_video_info_url = '';

    /**
     * @var string 解析视频信息需要用到的参数
     */
    public string $eurl = '';

    /**
     * @var string 视频id
     */
    private string $video_id = '';

    /**
     * 配置文件中数组的key
     */
    const CONFIG_KEY = 'Youtube';

    /**
     * 配置文件中必须包含的键
     */
    const SHOULD_CHECK_ARR = ['get_video_info_url', 'eurl', 'proxy'];

    /**
     * youtube返回正确信息中的status值
     */
    const STATUS_OK = 'ok';

    const SAVE_CHILD_DIR = 'youtube';

    public function parseUrl(string $url) {
        $this->web_url = $url;
        $this->setConfig();
        $this->getVideoId();
        $this->getRealUrls();
        $this->setTitle();
    }

    public function setTitle() {
        $this->video->title = $this->video->audio_title;
    }

    public function getVideoId() {
        //https://www.youtube.com/watch?v=AKdqYyVXssI
        preg_match('/\?v=([\w]+)/', $this->web_url, $matches);
        if (empty($matches[1])) {
            throw new \Exception('未解析出video id');
        }
        $this->video_id = $matches[1];
    }

    public function getRealUrls() {
        $url = $this->get_video_info_url . http_build_query([
                'html5' => 1,
                'video_id' => $this->video_id,
                'eurl' => $this->eurl . $this->video_id
            ]);
        $body = $this->request->get($url, [
            'header' => $this->header,
            'proxy' => $this->proxy
        ], false);

        parse_str($body, $body_arr);
        $this->checkStatus($body_arr['status']);
        $formats = json_decode($body_arr['player_response'], true)['streamingData']['formats'];
        foreach ($formats as $format) {
            $this->video->real_url[] = $format['url'];
        }
    }

    public function checkStatus($status) {
        if ($status !== self::STATUS_OK) {
            throw new \Exception('youtube返回状态不正确');
        }
    }
}