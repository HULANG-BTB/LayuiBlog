<?php
/**
 * File: Admin.php.
 * User: AloneH
 * Date: 2019/4/29
 * Time: 19:15
 * Project: layuiadmin
 * Home: http://oibit.cn
 * License: MIT
 */

namespace app\admin\controller;

use think\Controller;
use think\facade\Session;
use think\facade\Url;
use think\facade\Cookie;

use app\admin\model\user\User;
use app\admin\model\Option;

use app\common\library\Auth;


class Admin extends Controller
{
    /**
     * 开启登录控制
     * @var bool
     */
    protected $needLogin = true;
    /**
     * 开启权限控制
     * @var bool
     */
    protected $needAuth = true;
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
     * 当前控制器
     * @var null
     */
    protected $Model = null;
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
        // 获取网页信息
        $this->getWebInfo();
    }

    /**
     * 检测登录状态
     * @return string|null
     */
    public function __checkLogin() {
        $UserId = Session::get('user.id');
        // 判断是否登录
        if (empty($UserId)) {
            return $this->error("抱歉，您还没有登录获取访问权限！", Url::build("admin/login/index"));
        }
        $this->User = User::get($UserId);
        // 检测登录过期
        if (Cookie::get("remember") == "true") {
            Session::set("user", $this->User );
        }
        $this->User = User::get($UserId);
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
        $this->Title = Option::get('web_title')->value;
        $this->Keyword = Option::get('web_keyword')->value;
        $this->Descroption = Option::get('web_description')->value;
        $this->Favicon = Option::get('web_favicon')->value;
    }


}