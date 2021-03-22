# video-downloader
download video from websites

# 背景
在b站听到一个up翻唱的歌曲很好听,但是up没有上传音频版本,只有视频版本,于是萌生了下载视频然后提取音频的想法,就写了这个小工具

# 支持的站点
- [x] B站

# 功能
目前b站可使用的功能:
- [x] 根据url下载视频
- [x] 多段视频合并为一个视频(依赖ffmpeg)
- [x] 分离音频(依赖ffmpeg)

# 使用
## 使用 composer
`$ composer require leychan/video-downloader:1.0.3`

新建`index.php`文件
```php
<?php

require_once __DIR__ . '/vendor/autoload.php';

(new \video\Application())->run();
```
`$ composer install`

`$ php -f index.php`

## 直接git clone
`$ git clone https://github.com/leychan/video-downloader`

`$ cd video-downloader`

`$ composer install`

`$ php -f index.php`