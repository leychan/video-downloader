<?php


namespace video;


class Config
{
    static array $config = [];

    public static function load() {
        self::$config = require_once __DIR__ . '/config/config.php';
    }

    public static function getConfig(string $key = '') {
        if (empty(self::$config)) {
            self::load();
        }
        return self::$config[$key] ?? self::$config;
    }
}