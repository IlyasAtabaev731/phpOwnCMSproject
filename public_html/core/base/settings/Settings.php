<?php

namespace core\base\settings;

class Settings
{
    // Singleton
    private $routes = [
        'admin' => [
            'name' => 'admin',
            'path' => 'core/admin/controller',
            'hrUrl' => false
        ],
        'settings' => [
            'path' => 'core/base/settings'
        ],
        'plugins' => [
            'path' => 'core/plugins',
            'hrUrl' => false
        ],
        'user' => [
            'path' => 'core/user/controller',
            'hrUrl' => false,
            'routes' => [

            ]
        ],
        'default' => [
            'controller' => 'IndexController',
            'inputMethod' => 'inputData',
            'outputMethod' => 'outputData',
        ]
    ];
    private $template = [
        'text' => ['name', 'phone', 'address'],
        'textarea' => ['content', 'keywords']
    ];
    private function __construct() {}

    private function  __clone() {}

    private static $_instance;

    public static function get($property) {
        return self::instance() -> $property;
    }

    public static function instance() {
        if(self::$_instance instanceof self) {
            return self::$_instance;
        }
        return self::$_instance = new self;
    }
    public function clueProperties($class) {
        $baseProperties = [];
        foreach ($this as $name => $item) {
            $property = $class::get($name);
            if(is_array($property) && is_array($item)) {
                $baseProperties[$name] = array_merge_recursive($this->$name, $property);
            }
        }
        exit();
    }
}