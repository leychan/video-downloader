<?php

$proxy = [
    'https' => 'socks://127.0.0.1:1089',
    'http' => 'socks://127.0.0.1:1089',
];

return [
    'Bilibili' => [
        'get_aid_url' => 'https://api.bilibili.com/x/web-interface/view?bvid=',
        'get_cid_url' => 'https://api.bilibili.com/x/web-interface/view?aid=',
        'get_video_url' => 'https://api.bilibili.com/x/player/playurl', //获取视频的真正的url接口地址
        'favlist_url' => 'http://api.bilibili.com/x/v3/fav/resource/ids?platform=web&media_id=', //收藏夹内容列表
        'cookie_expire_time' => 86400,
    ],
    'Youtube' => [
        'get_video_info_url' => 'https://www.youtube.com/get_video_info?',
        'eurl' => 'https://youtube.googleapis.com/v/',
        'proxy' => $proxy,
    ],
];