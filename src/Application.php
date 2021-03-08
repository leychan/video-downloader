<?php


namespace video;


use League\CLImate\CLImate;

class Application
{
    /**
     * @var CLImate 命令行
     */
    private $cli;

    /**
     * @var string 站点标识
     */
    private string $website;

    /**
     * @var string 文件保存的目录
     */
    private string $save_dir;

    /**
     * @var string 请求带上的cookies
     */
    private array $cookies;

    private string $web_url;

    /**
     * @var bool 是否分离音频
     */
    private bool $separate_audio;


    public function __construct() {
        $this->cli = new CLImate();
    }

    /**
     * @desc 应用启动
     * @user lei
     * @date 2021/3/7
     */
    public function run() {
        $this->welcome();
        $this->doSetWebsite();
        $this->doSetUrl();
        $this->doSetSaveDir();
        $this->doSetCookie();
        $this->doSetSeparateAudio();
        try {
            $this->doThings();
        } catch (\Exception $e) {
            $this->cli->to('error')->red($e->getMessage());
            exit;
        }

    }

    /**
     * @desc 欢迎信息
     * @user lei
     * @date 2021/3/7
     */
    public function welcome() {
        $this->cli->out('welcome to use video-downloader');
    }

    /**
     * @desc 设置站点
     * @user lei
     * @date 2021/3/7
     */
    public function doSetWebsite() {
        $websites = array_keys(Config::getConfig());
        $input_website = $this->cli->radio('please choose one website below to use:', $websites);
        $this->website = $input_website->prompt();
    }

    /**
     * @desc 设置存储位置
     * @user lei
     * @date 2021/3/7
     */
    public function doSetSaveDir() {
        $input_save_dir = $this->cli->input('please input the save dir of the video:');
        $save_dir = $input_save_dir->prompt();
        $this->save_dir = empty($save_dir) ? __DIR__ . '/../' : $save_dir;
    }

    /**
     * @desc 设置cookie
     * @user lei
     * @date 2021/3/7
     */
    public function doSetCookie() {
        $input_cookie = $this->cli->input('please input cookie data of the website, default "":');
        $cookie_str = $input_cookie->prompt();
        $cookies = Helper::parseCookie($cookie_str);
        $this->cookies = $cookies;
    }

    /**
     * @desc 视频页面的url
     * @user lei
     * @date 2021/3/7
     */
    public function doSetUrl() {
        $input_save_dir = $this->cli->input('please input the url of the video page:');
        $web_url = $input_save_dir->prompt();
        $this->web_url = $web_url;
    }

    public function doSetSeparateAudio() {
        $separate_audio_arr = ['Yes', 'No'];
        $input_separate_audio = $this->cli->radio('please check if need separate audio:', $separate_audio_arr);
        $separate_audio = $input_separate_audio->prompt();
        $this->separate_audio = strtolower($separate_audio) == 'Yes' ? true : false;
    }

    /**
     * @desc 解析和下载
     * @user lei
     * @date 2021/3/7
     */
    public function doThings() {
        $parser = new \video\Parser(new ('\video\website\\' . $this->website), $this->web_url);
        $video = $parser->doParser($this->cookies, $this->save_dir);

        //根据视频对象下载视频
        $downloader = new \video\Downloader($video);
        $downloader->download();

        //合并视频
        //todo

        //分离音频
        $video->separateAudio();
    }
}