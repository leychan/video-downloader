<?php


namespace video;


use League\CLImate\CLImate;

class Application
{
    /**
     * @var CLImate 命令行
     */
    private CLImate $cli;

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
    private array $cookie;

    private string $video_page_url;

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
        try {
            $this->welcome();
            Helper::printLine();

            $this->doSetWebsite();
            Helper::printLine();

            $this->doSetUrl();
            Helper::printLine();

            $this->doSetSaveDir();
            Helper::printLine();

            $this->doSetCookie();
            Helper::printLine();

            $this->doSetSeparateAudio();
            Helper::printLine();

            $this->deal();
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
        $this->cli->bold('welcome to use video-downloader');exit;
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
        $tmp_save_dir = empty($save_dir) ? __DIR__ . '/../' : $save_dir;
        $dir_arr = explode('/', $tmp_save_dir);
        $len = count($dir_arr);
        for ($i = 0; $i < $len; $i++) {
            if ($dir_arr[$i] == '..') {
                if (isset($dir_arr[$i - 1])) {
                    unset($dir_arr[$i - 1]);
                    unset($dir_arr[$i]);
                }
            }
        }
        $this->save_dir = trim(implode('/', $dir_arr));
    }

    /**
     * @desc 设置cookie
     * @user lei
     * @date 2021/3/7
     */
    public function doSetCookie() {
        $cookie_str = Cookie::get($this->website);
        if (empty($cookie_str)) {
            $input_cookie = $this->cli->input('please input cookie data of the website, default "":');
            $cookie_str = $input_cookie->prompt();
            Cookie::save($cookie_str, $this->website);
        }
        $cookie = Helper::parseCookie($cookie_str);
        $this->cookie = $cookie;
    }

    public function printLineSpace() {
        $this->cli->out('');
    }

    /**
     * @desc 视频页面的url
     * @user lei
     * @date 2021/3/7
     */
    public function doSetUrl() {
        $input_save_dir = $this->cli->input('please input the url of the video page:');
        $web_url = $input_save_dir->prompt();
        $this->video_page_url = $web_url;
        if (empty(trim($this->video_page_url))) {
            throw new \Exception('url can not be empty');
        }
    }

    public function doSetSeparateAudio() {
        $separate_audio_arr = ['Yes', 'No'];
        $input_separate_audio = $this->cli->radio('please check if need separate audio:', $separate_audio_arr);
        $separate_audio = $input_separate_audio->prompt();
        $this->separate_audio = strtolower($separate_audio) == 'yes' ? true : false;
    }

    /**
     * @desc 解析和下载
     * @user lei
     * @date 2021/3/7
     */
    public function deal() {
        $website_class = '\video\website\\' . $this->website;
        $website = new $website_class;
        $parser = new \video\Parser($website);
        $video = $parser->run($this->video_page_url, $this->cookie, $this->save_dir, $this->separate_audio);

        //根据视频对象下载视频
        $downloader = new \video\Downloader($video);
        $downloader->run();

        $deal = new DealVideoSlice($video);
        $deal->run();
    }
}