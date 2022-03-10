<?php

namespace core\admin\controller;

use core\admin\model\Model;
use core\base\controller\BaseController;

class IndexController extends BaseController
{
    protected function inputData() {
        $db = Model::getInstance();

        $res = $db->query('SELECT * FROM `articles`');
        exit();
    }
}