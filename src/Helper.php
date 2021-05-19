<?php


namespace video;

class Helper
{
    /**
     * @desc 解析cookie字符串到数组
     * @user lei
     * @date 2021/3/7
     * @param string $cookie_str
     * @return array
     */
    public static function parseCookie(string $cookie_str) :array {
        $raw_arr = self::parseStringToArray(';', $cookie_str);
        $cookies = [];
        foreach ($raw_arr as $v) {
            $tmp_arr = self::parseStringToArray('=', $v);
            $cookies[$tmp_arr[0]] = urlencode($tmp_arr[1]);
        }
        return $cookies;
    }

    /**
     * @desc 根据分割符, 分割字符串到数组
     * @user lei
     * @date 2021/3/7
     * @param string $separate_character
     * @param string $str
     * @return array
     */
    public static function parseStringToArray(string $separate_character, string $str) :array {
        $str = trim($str);
        return explode($separate_character, $str);
    }

    public static function printLine() {
        echo PHP_EOL;
    }
}