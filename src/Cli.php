<?php


namespace video;

use League\CLImate\CLImate;

class Cli
{
    private static $instance = null;
    private CLImate $cli;

    private function __construct() {
        $this->cli = new CLImate();
    }

    private function __wakeup() {

    }
    private function __clone() {

    }

    public static function getInstance() :Cli {
        if (static::$instance === null) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    public function output() {
        $this->cli->out();
    }
}