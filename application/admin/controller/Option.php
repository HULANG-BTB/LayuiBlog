<?php
/**
 * Created by PhpStorm.
 * User: AloneH
 * Date: 2019/4/22
 * Time: 20:07
 */

namespace app\admin\controller;

use think\Db;
use think\facade\Session;

class Option extends Admin
{
    public function __construct()
    {
        parent::__construct();
        $this->Model = model('Option');
    }

    /**
     * 获取网站配置信息
     * @param array $field
     * @return mixed
     */
    private function getOptions($field = []) {
        $option = [];
        if (empty($field)) {
            $field = [
                'web_title',
                'web_subtitle',
                'web_keyword',
                'web_description',
                'web_domain',
                'web_copyright',
                'web_cache',
                'mail_host',
                'mail_port',
                'mail_user',
                'mail_pass',
                'mail_from',
                'upload_size',
                'upload_type',
            ];
        }
        foreach ($field as $value) {
            $option[$value] = $this->Model->get($value)->value;
        }
        return $option;
    }

    /**
     * 网页更新界面
     * @return string
     * @throws \Exception
     */
    public function web() {
        $options = $this->getOptions();
        $this->assign('options', $options);
        return $this->view->fetch();
    }

    /**
     * 更新网页信息
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function update() {
        $options = $this->request->post();
        if (!isset($options)) {
            return $this->error("非法操作!");
        }
        $nums = 0;
        foreach ( $options as $key => $value ) {
            $nums += $this->Model->get($key)->save(['value' => $value]);
        }
        if ($nums == 0) {
            return $this->error("未更新或更新失败!");
        }
        return $this->success("更新成功！");
    }

    /**
     * 邮件更新界面
     * @return string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function email() {
        $email =  $this->getOptions();
        $this->assign('email', $email);
        return $this->view->fetch();
    }

    public function user() {
        return $this->view->fetch();
    }

    public function user_update() {
        $options = $this->request->post();
        if (!isset($options)) {
            return $this->error("非法操作!");
        }
        $nums = 0;
        $check = $this->validate($options, [
            'oldPassword' => 'require|min:6',
            'password' => 'require|min:6',
            'repassword' =>  'require|min:6'
        ]);
        if ($check !== true) {
            return $this->error($check);
        }
        if ($options['password'] != $options['repassword']) {
            return $this->error("两次密码不一致！");
        }

        $userId = Session::get('user');
        $user = Db::name('user')
            ->where($userId)
            ->find();
        if (md5($options['oldPassword'].$user['username']) != $user['password']) {
            return $this->error("原密码错误！");
        }
        $user = Db::name('user') ->update(['id' => $userId, 'password' => md5($options['password'].$user['username'])]);
        return $this->success("更新成功！");
    }


}