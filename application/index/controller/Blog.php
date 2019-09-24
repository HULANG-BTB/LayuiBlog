<?php
/**
 * File: Blog.php.
 * User: AloneH
 * Date: 2019/4/30
 * Time: 13:36
 * Project: layuiadmin
 * Home: http://oibit.cn
 * License: MIT
 */

namespace app\index\controller;


use think\Controller;

use app\common\library\Auth;


class Blog extends Controller
{
    /**
     * 开启登录控制
     * @var bool
     */
    protected $needLogin = false;
    /**
     * 开启权限控制
     * @var bool
     */
    protected $needAuth = false;
    /**
     * 网页标题
     * @var string
     */
    protected $Title = '';
    /**
     * 网页关键词
     * @var string
     */
    protected $Keyword = '';
    /**
     * 网页描述
     * @var string
     */
    protected $Description = '';
    /**
     * 网页icon图标
     * @var string
     */
    protected $Favicon = '';
    /**
     * 导航栏信息
     * @var null
     */
    protected $Navigation = null;
    protected $Category = null;
    /**
     * 当前控制器模型
     * @var null
     */
    protected $Model = null;
    protected $NavModel = null;
    protected $UserModel = null;
    protected $OptionModel = null;
    protected $CategoryModel = null;
    /**
     * 用户
     * @var null
     */
    protected $User = null;


    /**
     * 构造函数
     * Admin constructor.
     */
    public function __construct()
    {
        parent::__construct();

        // 检测登录
        if ($this->needLogin === true) {
            $this->__checkLogin();
        }
        // 检测权限
        if ($this->needAuth === true) {
            $this->__checkAuth();
        }

        $this->NavModel = model("Navigation");
        $this->OptionModel = model("Option");
        $this->UserModel = model("User");
        $this->CategoryModel = model("Category");

        // 获取网页信息
        $this->getWebInfo();
        $this->assign("Title", $this->Title);
        $this->assign("Keyword", $this->Keyword);
        $this->assign("Description", $this->Description);
        $this->assign("Navigation", $this->Navigation);
        $this->assign("Category", $this->Category);

    }

    /**
     * 检测登录状态
     * @return string|null
     */
    public function __checkLogin() {
        $UserId = session('user.id');
        // 判断是否登录
        if (empty($UserId)) {
            return $this->error("抱歉，您还没有登录获取访问权限！", Url::build("admin/login/index"));
        }
        $this->User = $this->UserModel->get($UserId);
        // 检测登录过期
        if (cookie("remember") == "true") {
            session("user", $this->User );
        }
        $this->User = $this->UserModel->get($UserId);
    }

    /*
     * 检测权限
     * @return string|null
     */
    public function __checkAuth() {
        if ((new Auth)->checkAuth() === false) {
            return $this->error("抱歉，您暂无该权限，请联系管理员！");
        }
    }

    /**
     * 获取网页信息
     * @return null
     */
    public function getWebInfo() {
        $this->Title = $this->OptionModel->get('web_title')->value;
        $this->Keyword = $this->OptionModel->get('web_keyword')->value;
        $this->Description = $this->OptionModel->get('web_description')->value;
        $this->Favicon = $this->OptionModel->get('web_favicon')->value;
        $this->Navigation = $this->NavModel->where(['status' => 0])->order(['weigh' => 'desc'])->select();
        $this->Category = $this->CategoryModel->select();
    }
}