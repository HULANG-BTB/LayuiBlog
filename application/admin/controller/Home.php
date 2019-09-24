<?php
/**
 * File: Home.php.
 * User: AloneH
 * Date: 2019/4/29
 * Time: 19:55
 * Project: layuiadmin
 * Home: http://oibit.cn
 * License: MIT
 */

namespace app\admin\controller;

use think\Controller;

class Home extends Admin
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index() {
        return $this->view->fetch();
    }
}