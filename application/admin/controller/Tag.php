<?php
/**
 * File: Tag.php.
 * User: AloneH
 * Date: 2019/4/29
 * Time: 22:01
 * Project: layuiadmin
 * Home: http://oibit.cn
 * License: MIT
 */

namespace app\admin\controller;


class Tag extends Admin
{

    public function __construct()
    {
        parent::__construct();
        $this->Model = model('Tag');
    }

    public function index(){
        $tags = $this->Model->select();
        $this->assign("tags", $tags);
        return $this->view->fetch();
    }

    public function read() {
        $page = $this->request->param("page/d", 1);
        $limit = $this->request->param("limit/d", 15);
        $del = $this->request->param("del/d", 0);
        $keyword = '%' . $this->request->param("keyword/s", "") . "%";
        $tags = $this->Model
            ->where(['del' => $del])
            ->where("name|create_time|update_time", "like", $keyword)
            ->where('id', 'like', is_numeric($keyword) ? $keyword : '%%')
            ->page($page, $limit)
            ->order(['id' => 'asc'])
            ->select();

        $pages = $this->Model
            ->where(['del' => $del])
            ->where("name|create_time|update_time", "like", $keyword)
            ->where('id', 'like', is_numeric($keyword) ? $keyword : '%%')
            ->count();

        return $this->success("获取数据成功", url('admin/article/read'), $tags, $pages);

    }

    public function add(){
        if ( !$this->request->isPost() ) {
            return $this->error("非法操作！");
        }
        $name = $this->request->param("name", "");
        $isExist = $this->Model
            ->where(["name" => $name])
            ->count();
        if ( $isExist ) {
            return $this->error("标签已存在！");
        }
        $status = $this->Model->save(['name' => $name]);
        if ( $status ) {
            return $this->success("新增标签成功！");
        } else {
            return $this->error("新增标签失败！");
        }
        return json($ret);
    }

    public function modify(){
        if ( !$this->request->isPost() ) {
            return $this->error("非法操作！");
        }
        $id = $this->request->param("id", 0);
        $name = $this->request->param("name", "");
        $isExist = $this->Model
            ->where(["name" => $name])
            ->count();
        if ( $isExist ) {
            return $this->error("标签已存在！");
        }
        $status = $this->Model
            ->get($id)
            ->save(['name' => $name]);

        if ( $status ) {
            return $this->success("修改标签成功！");
        } else {
            return $this->error("修改标签失败！");
        }
    }

    public function delete() {
        $id = $this->request->param('id/d', 0);
        $rst = $this->Model->get($id)->save(['del' => 1]);
        if ($rst === false) {
            return $this->error("删除失败！");
        } else {
            return $this->success("删除成功！");
        }
    }
    public function recycle() {
        return $this->view->fetch();
    }

    public function recovery() {
        $id = $this->request->param('id/d', 0);
        $rst = $this->Model->get($id)->save(['del' => 0]);
        if ($rst === false) {
            return $this->error("恢复失败！");
        } else {
            return $this->success("恢复成功！");
        }
    }

}