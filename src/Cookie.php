<?php


namespace video;


class Cookie
{
    const COOKIE_ROOT = __DIR__ .'/cookies/';
    public static function save(string $cookie_str, string $child_dir) {
        $dir = self::COOKIE_ROOT . $child_dir;
        if (!is_dir($dir)) {
            mkdir($dir);
        }
        $filename =  $dir . '/cookie';
        file_put_contents($filename, $cookie_str);
    }

    public static function get(string $child_dir) {
        $filename = self::makeFilename($child_dir);
        if (!file_exists($filename)) {
            return false;
        }
        $last_modify = filemtime($filename);
        $conf = Config::getConfig($child_dir);
        $expire = $conf['cookie_expire_time'] ?? 0;
        if (time() - $last_modify > $expire) {
            unlink($filename);
            return false;
        }
        return file_get_contents($filename);
    }

    public static function makeFilename(string $child_dir) {
        return self::COOKIE_ROOT . $child_dir . '/cookie';
    }
}