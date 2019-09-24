<?php
/**
 * File: Index.php.
 * User: AloneH
 * Date: 2019/4/29
 * Time: 19:14
 * Project: layuiadmin
 * Home: http://oibit.cn
 * License: MIT
 */

namespace app\admin\controller;

use think\Controller;
use app\admin\controller\Admin;

class Index extends Admin
{
    public function index() {
        return $this->view->fetch();
    }
}