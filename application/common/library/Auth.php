<?php
/**
 * File: Auth.php.
 * User: AloneH
 * Date: 2019/4/27
 * Time: 22:58
 * Project: layuiadmin
 * Home: http://oibit.cn
 * License: MIT
 */

namespace app\common\library;

use think\Config;
use think\Controller;
use think\Request;
use think\Db;
use think\facade\Cache;
use think\facade\Cookie;
use think\facade\Session;
use think\facade\Validate;
use think\Exception;

/**
 * Use Model Namespace
 */
use app\common\model\User;
use app\common\model\UserGroup;
use app\common\model\UserRule;

/**
 * Class Auth
 * @package app\common\controller
 */
class Auth extends Controller
{
    /**
     * Auth 模型对象
     * @var null
     */
    protected $Model = null;
    /**
     * 登录状态
     * @var bool
     */
    protected $IsLogin = true;
    /**
     * 规则列表
     * @var array
     */
    protected $Rules = [];
    /**
     * User对象
     * @var null
     */
    protected $User = null;

    protected $Rule = null;

    public function __construct()
    {
        parent::__construct();
        if ( $UserId = Session::get('user.id') ) {
            $this->User = User::get($UserId);
        } else {
            $this->IsLogin = false;
        }
        $this->Rule = model('UserRule');

    }

    /**
     * 检测是否具有对应的权限
     * @param null $path 控制器/方法
     * @param null $module 模块默认为当前模块
     * @return bool
     */
    public function checkAuth($path = null, $module = null) {
        if ( !$this->IsLogin ) {
            return false;
        }
        $ruleList = $this->getRuleList();
        $rules = [];
        foreach ($ruleList as $key => $value) {
            $rules[] = strtolower($value['name']);
        }
        $url = ( !empty($module) ? $module : $this->getModule()) . '/' . ( !empty($path) ? $path : $this->getPath());
        $url = strtolower(str_replace('.', '/', $url));

        return in_array($url, $rules) ? true : false;
    }

    /**
     * 获取当前控制器/方法
     * @return string
     */
    public function getPath() {
        return $this->request->controller() . '/' . $this->request->action();
    }

    /**
     * 获取当前模块
     * @return string
     */
    public function getModule() {
        return $this->request->module();
    }

    /**
     * 获取会员组别权限规则列表
     * return array
     */
    public function getRuleList() {
        if ( !empty($this->Rules) ) {
            return $this->Rules;
        }
        $group = $this->User->group;
        if ( !$group ) {
            return [];
        }
        $rules = explode(',', $group['rules']);
        $this->Rules = UserRule::where(['status'=> 'normal'])->where('id', 'in', $rules)->field(["id", "pid", "name", "title", "ismenu"])->select();
        return $this->Rules;
    }

}