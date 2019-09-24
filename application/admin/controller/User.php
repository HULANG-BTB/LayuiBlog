<?php
/**
 * File: User.php.
 * User: AloneH
 * Date: 2019/4/29
 * Time: 22:27
 * Project: layuiadmin
 * Home: http://oibit.cn
 * License: MIT
 */

namespace app\admin\controller;

use oibit\Tree;

class User extends Admin
{
    protected $Group = null;
    protected $Rule = null;

    public function __construct()
    {
        parent::__construct();
        $this->Model = model('User');
        $this->Group = model('UserGroup');
        $this->Rule = model('UserRule');
    }

    public function user() {
        return $this->view->fetch();
    }

    public function userRead() {
        $page = $this->request->param("page/d", 1);
        $limit = $this->request->param("limit/d", 15);
        $del = $this->request->param("del/d", 0);
        $keyword = '%' . $this->request->param("keyword/s", "") . "%";

        $Users = $this->Model->where("username|nickname|phone|email|avator|login_ip|login_time|type|status|create_time", "like", $keyword)
            ->where('id', 'like', is_numeric($keyword) ? $keyword : '%%')
            ->page($page, $limit)
            ->order(['id' => 'asc'])
            ->select();

        $pages = $this->Model->where("username|nickname|phone|email|avator|login_ip|login_time|type|status|create_time", "like", $keyword)
            ->where('id', 'like', is_numeric($keyword) ? $keyword : '%%')
            ->count();

        return $this->success("获取数据成功", url('admin/user/read'), $Users, $pages);
    }

    public function group() {
        return $this->view->fetch();
    }

    public function groupList() {
        $page = $this->request->param("page/d", 1);
        $limit = $this->request->param("limit/d", 15);
        $del = $this->request->param("del/d", 0);
        $keyword = '%' . $this->request->param("keyword/s", "") . "%";

        $Group = $this->Group->where("name|create_time|update_time", "like", $keyword)
            ->where('id', 'like', is_numeric($keyword) ? $keyword : '%%')
            ->page($page, $limit)
            ->order(['id' => 'asc'])
            ->select();

        $pages = $this->Group->where("name|create_time|update_time", "like", $keyword)
            ->where('id', 'like', is_numeric($keyword) ? $keyword : '%%')
            ->count();

        return $this->success("获取数据成功", url('admin/user/read'), $Group, $pages);
    }

    public function edit() {
        $UserId = $this->request->param("id/d", 0);
        $User = $this->Model->get($UserId);
        $this->assign("User", $User);
        return $this->fetch();
    }

    public function groupAdd() {
        return $this->view->fetch();
    }

    public function groupInsert() {
        $data = $this->request->param();
        isset($data['rules']) ? $data['rules'] = implode(",", $data['rules']) : $data['rules'] = "";
        $data['status'] = isset($data['status']) && $data['status']=="on" ? "normal" : "hidden";
        $nums = $this->Group->save($data);
        if ($nums) {
            return $this->success("添加用户组成功！");
        } else {
            return $this->success("添加用户组失败！");
        }
    }

    public function groupEdit() {
        $GroupId = $this->request->param("id/d", 0);
        $Group = $this->Group->get($GroupId);
        $this->assign("Group", $Group);
        return $this->view->fetch();
    }

    public function groupUpdate() {
        $GroupId = $this->request->param("id/d", 0);
        $Rules = implode(",", $this->request->param("rules/a", []));
        $Name = $this->request->param("name/s", "");
        $Group = $this->Group->get($GroupId);
        $Group->rules = $Rules;
        $Group->name = $Name;
        $nums = $Group->save();
        if ($nums) {
            return $this->success("更新成功！");
        } else {
            return $this->error("更新失败！");
        }
    }

    public function groupDelete() {
        $GroupId = $this->request->param("id/d", 0);
        $Group = $this->Group->get($GroupId);
        $nums = $Group->delete();
        if ($nums) {
            return $this->success("删除成功！");
        } else {
            return $this->error("删除失败！");
        }
    }

    public function groupRuleList() {
        $Rule = $this->Rule->where('status', '=', 'normal')
            ->order('id','ASC')
            ->field(['id', 'pid', 'title as name'])
            ->select();
        return $this->success("获取数据成功", "", $Rule);
    }

    public function rule() {
        return $this->view->fetch();
    }

    public function ruleList() {
        $keyword = $this->request->param("keyword/s", "");

        $Rule = $this->Rule->where("name|title|remark", "like", '%' . $keyword . "%")
            ->where('id|pid|create_time|update_time', 'like', is_numeric($keyword) ? $keyword : '%%')
            ->order('weigh', 'desc')
            ->order(['id' => 'asc'])
            ->select();

        // dump($Rule);

        Tree::instance()->init($Rule, null, "　", ['　', '├', '└']);
        $Rule = Tree::instance()->getTreeList(Tree::instance()->getTreeArray(0), 'title');

        $pages = $this->Rule->where("name|title|remark|create_time|update_time", "like", $keyword)
            ->where('id|pid', 'like', is_numeric($keyword) ? $keyword : '%%')
            ->count();

        return $this->success("获取数据成功", url('admin/user/read'), $Rule, $pages);
    }

    public function ruleAdd() {
        $RuleList = $this->Rule->field(['id', 'pid', 'title'])->select();
        Tree::instance()->init($RuleList, null, "　", ['　', '├', '└']);
        $RuleList = Tree::instance()->getTreeList(Tree::instance()->getTreeArray(0), 'title');
        $this->assign("RuleList", $RuleList);
        return $this->view->fetch();
    }

    public function ruleInsert(){
        $data = $this->request->param();
        $data['ismenu'] = isset($data['ismenu']) && $data['ismenu']=="on" ? 1 : 0;
        $data['status'] = isset($data['status']) && $data['status']=="on" ? "normal" : "hidden";
        $nums = $this->Rule->save($data);
        if ($nums) {
            return $this->success("增加规则成功！");
        } else {
            return $this->error("增加规则失败！");
        }
    }

    public function ruleEdit() {
        $RuleId = $this->request->param("id/d", 0);
        $Rule = $this->Rule->get($RuleId);
        $RuleList = $this->Rule->field(['id', 'pid', 'title'])->select();
        Tree::instance()->init($RuleList, null, "　", ['　', '├', '└']);
        $RuleList = Tree::instance()->getTreeList(Tree::instance()->getTreeArray(0), 'title');
        $this->assign("RuleList", $RuleList);
        $this->assign("Rule", $Rule);
        return $this->view->fetch();
    }

    public function ruleUpdate() {
        $data = $this->request->param();
        $data['ismenu'] = isset($data['ismenu']) && $data['ismenu']=="on" ? 1 : 0;
        $data['status'] = isset($data['status']) && $data['status']=="on" ? "normal" : "hidden";
        $Rule = $this->Rule->get($data['id']);
        $nums = $Rule->save($data);
        if ($nums) {
            return $this->success("更新成功！");
        } else {
            return $this->error("更新失败！");
        }
    }

    public function ruleDelete() {
        $RuleId = $this->request->param('id/d', 0);
        $Rule = $this->Rule->get($RuleId);
        $nums = $Rule->delete();
        if ($nums) {
            return $this->success("删除成功！");
        } else {
            return $this->error("删除失败！");
        }
    }

}