<?php

namespace video\website;

use video\Config;
use video\Request;

class Bilibili extends Base
{
    /**
     * @var string 获取视频真实链接的url
     */
    public string $get_video_url;

    /**
     * @var string 获取aid的url
     */
    public string $get_aid_url;

    /**
     * @var string 获取cid的url
     */
    public string $get_cid_url;

    /**
     * @var string 获取收藏夹列表明细的url
     */
    public string $favlist_url;

    /**
     * @var string 要解析的url, 即b站打开视频页面的链接
     */
    public string $web_url;

    private string $aid;
    private string $cid;
    private string $bvid;

    /**
     * @var array cookie值, 必须
     */
    public array $cookie;

    public Request $request;

    /**
     * 默认的子路径文件夹名
     */
    const SAVE_CHILD_DIR = 'bilibili';

    /**
     * 配置文件中数组的key
     */
    const CONFIG_KEY = 'Bilibili';

    /**
     * 站点域名
     */
    const DOMAIN = 'bilibili.com';

    /**
     * 配置文件中必须包含的键
     */
    const SHOULD_CHECK_ARR = ['get_aid_url', 'get_cid_url', 'get_video_url'];

    public function __construct()
    {
        parent::__construct();
        $this->header = [
            'User-Agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/88.0.4324.182 Safari/537.36',
            'Referer' => 'https://www.bilibili.com',
            'Keep-Alive' => 'timeout=10'
        ];
    }

    /**
     * @desc 解析url, 获取真实的视频地址
     * @user lei
     * @date 2021/3/3
     * @param string $url
     * @return mixed|void
     * @throws \Exception
     */
    public function parseUrl(string $url)
    {
        $this->web_url = $url;
        $this->setConfig();
        $this->parseBvid();
        $this->getAidAndTitle();
        $this->getCid();
        $url = $this->get_video_url . "?avid={$this->aid}&cid={$this->cid}&qn=112";
        $body = $this->doGet($url);
        $this->video->real_url = array_column($body['data']['durl'], 'url');
    }

    /**
     * @desc 解析bvid
     * @user lei
     * @date 2021/5/19
     * @throws \Exception
     */
    protected function parseBvid() {
        preg_match('/BV[\w]+/', $this->web_url, $matches);
        if (empty($matches)) {
            throw new \Exception('未解析出bvid');
        }
        $this->bvid = $matches[0];
    }

    /**
     * @desc 解析aid和title
     * @user lei
     * @date 2021/5/19
     * @throws \Exception
     */
    protected function getAidAndTitle() {
        $url = $this->get_aid_url . $this->bvid;
        $body = $this->doGet($url);
        $this->aid = $body['data']['aid'];
        $this->video->title = str_replace(' | ', '|', $body['data']['title']);
    }

    /**
     * @desc 解析cid
     * @user lei
     * @date 2021/5/19
     * @throws \Exception
     */
    protected function getCid() {
        $url = $this->get_cid_url . $this->aid;
        $body = $this->doGet($url);
        $this->cid = $body['data']['cid'];
    }

    protected function doGet($url) {
        $body = $this->request->get($url, [
            'cookies' => Request::makeCookies($this->cookie, self::DOMAIN)
        ]);
        if ($body['code'] != 0) {
            throw new \Exception($body['message']);
        }
        return $body;
    }

    private function getFavList() {
        $config_key = static::CONFIG_KEY;
        $config = Config::getConfig($config_key);
        $url = $config['favlist_url'] . '1178578486';
        $body = $this->doGet($url);
        $data = $body['data'];
        $bvids = array_column($data, 'bvid');
        return $bvids;
    }
}