<?php

defined('VG_ACCESS') or die('Access denied');

// Paths relative to index.php
const TEMPLATES = 'templates/default/';
const ADMIN_TEMPLATE = 'core/admin/view/';

const COOKIE_VERSION = '1.0.0';
const CRYPT_KEY = '';
const COOKIE_TIME = 60;
const BLOCK_TIME = 3;

const QTY = 3;
const QTY_LINES = 8;

const ADMIN_CSS_JS = [
    'styles' => [],
    'scripts' => [],
];

const USER_CSS_JS = [
    'styles' => [],
    'scripts' => [],
];

use core\base\exceptions\RouteException;

function auto_load_classes($class_name){
    $class_name = str_replace('\\', '/', $class_name);
    if (!@include_once $class_name . '.php') {
        throw new RouteException('Не верное имя файла для подключения - ' . $class_name);
    }
}
spl_autoload_register('auto_load_classes');