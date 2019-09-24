<?php
/**
 * File: Page.php.
 * User: AloneH
 * Date: 2019/5/1
 * Time: 22:53
 * Project: layuiadmin
 * Home: http://oibit.cn
 * License: MIT
 */

namespace app\index\controller;


class Page extends Blog
{
    public function timeline() {
        return 'Hello TimeLine!';
    }

    public function about() {
        return 'Hello About Me!';
    }

    public function contact() {
        return 'Hello Contact!';
    }

    public function donate() {
        return 'Hello Donate Me!';
    }
}