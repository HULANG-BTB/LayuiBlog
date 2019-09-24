<?php
/**
 * File: Navigation.php.
 * User: AloneH
 * Date: 2019/5/1
 * Time: 20:38
 * Project: layuiadmin
 * Home: http://oibit.cn
 * License: MIT
 */

namespace app\admin\controller;


class Navigation extends Admin
{
    public function __construct()
    {
        parent::__construct();
        $this->Model = model('Navigation');
    }

    public function index() {
        return $this->view->fetch();
    }

    public function read() {
        $page = $this->request->param("page/d", 1);
        $limit = $this->request->param("limit/d", 15);
        $keyword = $this->request->param("keyword/s", "");
        $Navigation = $this->Model
            ->where("name|url|type|create_time|update_time", "like", '%' . $keyword . "%")
            ->where('id', 'like', is_numeric($keyword) ? $keyword : '%%')
            ->page($page, $limit)
            ->order(['weigh'=>'desc' ,'id' => 'asc'])
            ->select();

        $pages = $this->Model
            ->where("name|url|type|create_time|update_time", '%' . $keyword . "%")
            ->where('id', 'like', is_numeric($keyword) ? $keyword : '%%')
            ->count();

        return $this->success("获取数据成功", url('admin/article/read'), $Navigation, $pages);
    }

    public function add() {
        return $this->view->fetch();
    }

    public function insert() {
        $data = $this->request->param();
        $data['status'] = (isset($data['status']) && $data['status']=="on") ? 0 : 1;
        $data['type'] = (isset($data['type']) && $data['type']=="on") ? 0 : 1;
        $rst = $this->Model->save($data);
        if ($rst) {
            return $this->success("新增成功！");
        } else {
            return $this->error("新增失败！");
        }
    }

    public function edit() {
        $id = $this->request->param("id/d", 0);
        $Navigation = $this->Model->get($id);
        if (!$Navigation) {
            return $this->error("非法操作！");
        }
        $this->assign("Navigation", $Navigation);
        return $this->view->fetch();
    }

    public function update() {
        $id = $this->request->param("id/d", 0);
        $data = $this->request->param();
        $data['status'] = (isset($data['status']) && $data['status']=="on") ? 0 : 1;
        $data['type'] = (isset($data['type']) && $data['type']=="on") ? 0 : 1;
        $Navigation = $this->Model->get($id);
        $rst = $Navigation->save($data);
        if ($rst) {
            return $this->success("修改成功！");
        } else {
            return $this->error("修改失败！");
        }
    }

    public function delete() {
        $id = $this->request->param("id/d", 0);
        $Navigation = $this->Model->get($id);
        $rst = $Navigation->delete();
        if ($rst) {
            return $this->success("删除成功！");
        } else {
            return $this->error("删除失败！");
        }
    }
}