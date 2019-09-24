<?php
/**
 * File: Login.php.
 * User: AloneH
 * Date: 2019/5/2
 * Time: 18:30
 * Project: layuiadmin
 * Home: http://oibit.cn
 * License: MIT
 */

namespace app\index\controller;

use think\Controller;
use think\facade\Session;
use think\facade\Cookie;
use think\facade\Url;
use think\captcha\Captcha;

class Login extends Blog
{

    public function __construct()
    {
        parent::__construct();
        $this->checkStatus();
    }

    public function index() {
        return $this->view->fetch();
    }

    public function login() {
        $options = $this->request->post();
        $check = $this->validate($options, [
            'vercode|验证码' => 'require|captcha',
            'username|用户名' => 'require|min:6',
            'password|密码' => 'require|min:6'
        ]);
        if (!isset($options)) {
            return $this->error("非法操作!");
        }
        if ($check !== true) {
            return $this->error($check);
        }
        $User = User::where("username|phone|email",'=', $options['username'])->find();
        if (!isset($User) || !$this->checkPassword($User, $options['password'])) {
            return $this->error("用户名或密码错误！");
        }
        if (isset($options['remember'])) {
            Cookie::forever("remember", "true");
        } else {
            Cookie::forever("remember", "false");
        }
        Session::set("user", $User);
        return $this->success("登录成功！", Url::build('index/index/index'));
    }

    private function checkStatus() {
        $UserId = Session::get('user.id');
        if (isset($UserId)) {
            return $this->redirect(Url::build('index/index/index'));
        }
    }

    private function checkPassword($user, $password) {
        $UserPassword = $user->password;
        $salt = $user->salt;
        $encryptPassword = $this->getEncryptPassword($password, $salt);
        return (($UserPassword === $encryptPassword) ? true : false);
    }

    private function getEncryptPassword($password = "", $salt = "", $crypt = "sha1") {
        return $crypt($password . $salt);
    }
}
