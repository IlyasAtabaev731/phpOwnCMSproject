<?php

namespace core\base\controller;

trait Singleton {
    private static ?self $_instance = null;

    public static function getInstance(): self {
        if(self::$_instance instanceof self) {
            return self::$_instance;
        }
        return self::$_instance = new self;
    }

    private function __clone() {}

    private function __construct() {}
}