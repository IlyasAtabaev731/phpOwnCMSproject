<?php

namespace core\base\controller;

use core\base\exceptions\RouteException;
use core\base\settings\Settings;
use ReflectionException;
use ReflectionMethod;

abstract class BaseController
{
    use BaseMethods;

    protected $page;
    protected $errors;

    protected string $controller;
    protected string $inputMethod;
    protected string $outputMethod;
    protected ?array $parameters = null;

    protected array $styles = [];
    protected array $scripts = [];

    public function route() {
        $controller = str_replace('/', '\\', $this->controller);

        try {
            $reflectController = new ReflectionMethod($controller, 'request');

            $argsController = [
                'parameters' => $this->parameters,
                'inputMethod' => $this->inputMethod,
                'outputMethod' => $this->outputMethod,
            ];

            $reflectController->invoke(new $controller, $argsController);
        }catch (ReflectionException $e) {
            throw new RouteException($e->getMessage());
        }
    }

    public function request(array $args) {
        $parameters = $args['parameters'];
        $inputMethod = $args['inputMethod'];
        $outputMethod = $args['outputMethod'];

        $data = $this->$inputMethod();

        if(method_exists($this, $outputMethod)) {
            $page = $this->$outputMethod($data);
            if($page) $this->page = $page;
        }
        elseif ($data) $this->page = $data;

        if($this->errors) $this->writeLog($this->errors);

        $this->getPage();
    }

    protected function render(string $pathTemplate = '', array $parameters = []) {
        extract($parameters);

        if(!$pathTemplate) {
            $classReflect = new \ReflectionClass($this);
            $controllerNameSpace = $classReflect->getNamespaceName() . '\\';
            $controllerPath = str_replace('\\', '/', $controllerNameSpace);

            $routes = Settings::get('routes');

            if($routes['user']['path'] === $controllerPath) $templatesPath = TEMPLATES;
                else $templatesPath = ADMIN_TEMPLATE;

            $pathTemplate = $templatesPath . explode('controller', strtolower($classReflect->getShortName()))[0];
        }

        ob_start();

        if(!@include_once $pathTemplate . '.php') throw new RouteException('Отсутствует шаблон - ' . $pathTemplate);

        return ob_get_clean();
    }

    protected function getPage() {
        if(is_array($this->page)) {
            foreach ($this->page as $block) echo $block;
        }else{
            echo $this->page;
        }
    }

    protected function init(bool $admin = false) {
        if(!$admin) {
            if(USER_CSS_JS['styles']) {
                foreach (USER_CSS_JS['styles'] as $item) $this->styles[] = PATH . TEMPLATES . trim($item, '/');
            }
            if(USER_CSS_JS['scripts']) {
                foreach (USER_CSS_JS['scripts'] as $item) $this->scripts[] = PATH . TEMPLATES . trim($item, '/');
            }
        }else{
            if(ADMIN_CSS_JS['styles']) {
                foreach (ADMIN_CSS_JS['styles'] as $item) $this->styles[] = PATH . TEMPLATES . trim($item, '/');
            }
            if(ADMIN_CSS_JS['scripts']) {
                foreach (ADMIN_CSS_JS['scripts'] as $item) $this->scripts[] = PATH . TEMPLATES . trim($item, '/');
            }
        }
    }
}