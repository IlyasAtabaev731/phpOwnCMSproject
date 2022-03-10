<?php

namespace core\user\controller;

use core\base\controller\BaseController;

class IndexController extends BaseController
{
    protected function inputData() {
        $name = 'ivan';
        $content = $this->render('', compact('name'));
        $header = $this->render(TEMPLATES . 'header');
        $footer = $this->render(TEMPLATES . 'footer');
        return compact('content', 'header', 'footer');
    }

    protected function outputData() {
        $vars = func_get_args()[0];
        return $this->render(TEMPLATES . 'template', $vars);
    }
}