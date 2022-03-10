<?php

namespace core\base\controller;

use Exception;
use core\base\exceptions\RouteException;
use core\base\settings\Settings;

class RouteController extends BaseController
{
    use Singleton;

    protected array $routes;

    private function __clone() {}

    private function __construct() {
        $address = $_SERVER['REQUEST_URI'];

        if(strrpos($address, '/') === strlen($address) - 1 && strrpos($address, '/') !== 0) {
            // Если обращаемся на юрл заканчивающийся на '/'
            $this->redirect(rtrim($address, '/'), 301);
            // редиректим на юрл без / в конце
        }

        $path = substr($_SERVER['PHP_SELF'], 0, strrpos($_SERVER['PHP_SELF'], 'index.php') );
            // Путь до исполнительного файла
        if($path === PATH) {
            // Если совпадает с путем в конфиге
            $this->routes = Settings::get('routes');

            $url = explode('/', substr($address, strlen(PATH)));

            if(!$this->routes) throw new RouteException('Отсутствуют маршруты в базовых настройках');
//            strpos($address, $this->routes['admin']['alias']) === strlen(PATH) &&
            if($url[0] === $this->routes['admin']['alias']) {
                // Admin:

                array_shift($url); // Убираем из $url строку админ алиаса

                if($url[0] && is_dir($_SERVER['DOCUMENT_ROOT'] . PATH . $this->routes['plugins']['path'] . $url[0])) {
                    // Plugins:
                    $plugin = array_shift($url);
                    
                    $pluginSettings = $this->routes['settings']['path'] . ucfirst($plugin . 'Settings');
                    
                    if(file_exists($_SERVER['DOCUMENT_ROOT'] . PATH . $pluginSettings . '.php')) {
                        $pluginSettings = str_replace('/', '\\', $pluginSettings);
                        $this->routes = $pluginSettings::get('routes');
                    }

                    $dir = $this->routes['plugins']['dir'] ? '/' . $this->routes['plugins']['dir'] . '/' : '/';
                    $dir = str_replace('//', '/', $dir);

                    $this->controller = $this->routes['plugins']['path'] . $plugin . $dir;
                    
                    $hrUrl = $this->routes['plugins']['hrUrl'];
                    $route = 'plugins';
                }else{
                    // Admin:
                    $this->controller = $this->routes['admin']['path'];
                    $hrUrl = $this->routes['admin']['hrUrl'];
                    $route = 'admin';
                }
            }else{
                // User:
                $hrUrl = $this->routes['user']['hrUrl'];
                $this->controller = $this->routes['user']['path'];
                $route = 'user';
            }

            $this->createRoute($route, $url); // создаем маршрут определяем контроллер, ввод, вывод

            if($url[1]) {
                // Получаем все параметры адресной строки для маршрутизации
                $count = count($url);
                $key = '';
                if(!$hrUrl) {
                    $i = 1;
                }else{
                    $this->parameters['alias'] = $url[1];
                    $i = 2;
                }
                while ($i < $count) {
                    $key = $url[$i];
                    $i++;
                    $this ->parameters[$key] = $url[$i] ?? '';
                    $i++;
                }
//                for(; $i < $count; $i++) {
//                    if(!$key) {
//                        $key = $url[$i];
//                        $this->parameters[$key] = '';
//                    }else {
//                        $this->parameters[$key] = $url[$i];
//                        $key = '';
//                    }
//                }
            }
        }else{
            throw new RouteException('Не корректная корневая директория сайта', 1);
        }

    }
    
    private function createRoute(string $var,array $url) {
        // $var это юсер или админ или плагин
        $route = [];
        if (!empty($url[0])) {

            if ($this->routes[$var]['routes'][$url[0]]) {

                $route = explode('/', $this->routes[$var]['routes'][$url[0]]);
                
                $this->controller .= ucfirst($route[0].'Controller');
            }else{
                $this->controller .= ucfirst($url[0].'Controller');
            }

        }else{
            $this->controller .= $this->routes['default']['controller'];
        }

        $this->inputMethod = $route[1] ?? $this->routes['default']['inputMethod'];
        $this->outputMethod = $route[2] ?? $this->routes['default']['outputMethod'];
    }
}