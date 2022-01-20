<?php

namespace core\base\settings;

use core\base\settings\Settings;

class ShopSettings
{
    // Singleton
    private $templateArr = [
        'text' => ['price', 'short'],
        'textarea' => ['good_content']
    ];
    private $baseSettings;

    private function __construct() {
    }

    private function  __clone() {}

    private static $_instance;

    public static function get($property) {
        return self::instance() -> $property;
    }

    public static function instance() {
        if(self::$_instance instanceof self) {
            return self::$_instance;
        }
        self::$_instance = new self;
        self::instance()->baseSettings = Settings::instance();
        $baseProperties = self::$_instance->baseSettings->clueProperties(get_class());
        return self::$_instance;
    }
}