<?php

namespace core\base\controllers;

use core\base\settings\Settings;
use core\base\settings\ShopSettings;

class RouteController
{
    // Singleton
    private static $_instance;
    private function __construct() {
        $s = Settings::get('routes');
        $s1 = ShopSettings::get('templateArr');
        exit();
    }
    private function __clone() {}
    public static function  getInstance() {
        if(self::$_instance instanceof self) {
            return self::$_instance;
        }
        return self::$_instance = new self;
    }
}