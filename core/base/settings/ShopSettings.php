<?php

namespace core\base\settings;

use core\base\controller\Singleton;
use core\base\settings\Settings;

class ShopSettings
{
    use Singleton;

    private array $routes = [
        'plugins' => [
            'path' => 'core/plugins/',
            'hrUrl' => false,
            'dir' => 'controller',
        ]
    ];

    private Settings $baseSettings;

    public static function get($property) {
        return self::getInstance() -> $property;
    }

    public static function getInstance(): self {
        if(self::$_instance instanceof self) {
            return self::$_instance;
        }
        
        self::$_instance = new self;
        self::$_instance->baseSettings = Settings::getInstance();
        
        $baseProperties = self::$_instance->baseSettings->clueProperties(get_class());
        self::$_instance -> setProperties($baseProperties);
        
        return self::$_instance;
    }

    protected function setProperties($properties) {
        if($properties) {
            foreach ($properties as $name=> $property) {
                $this->$name = $property;
            }
        }
    }

}