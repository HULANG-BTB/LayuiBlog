<?php
/**
 * File: Comment.php.
 * User: AloneH
 * Date: 2019/4/30
 * Time: 9:38
 * Project: layuiadmin
 * Home: http://oibit.cn
 * License: MIT
 */

namespace app\admin\controller;


class Comment extends Admin
{

    public function __construct()
    {
        parent::__construct();
        $this->Model = model("Comment");
    }

    public function index() {
        return $this->view->fetch();
    }

    public function read() {
        $page = $this->request->param("page/d", 1);
        $limit = $this->request->param("limit/d", 15);
        $del = $this->request->param("del/d", 0);
        $keyword = $this->request->param("keyword/s", "");
        $tags = $this->Model
            ->where(['del' => $del])
            ->where("status|ip|city|create_time|update_time", "like", '%' . $keyword . "%")
            ->where('status|id|article_id|parent_id', 'like', is_numeric($keyword) ? $keyword : '%%')
            ->page($page, $limit)
            ->order(['id' => 'asc'])
            ->select();

        $pages = $this->Model
            ->where(['del' => $del])
            ->where("status|ip|city|create_time|update_time", "like", '%' . $keyword . "%")
            ->where('status|id|article_id|parent_id', 'like', is_numeric($keyword) ? $keyword : '%%')
            ->count();

        return $this->success("获取数据成功", url('admin/article/read'), $tags, $pages);

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

    public function check() {
        $id = $this->request->param('id/d', 0);
        $rst = $this->Model->get($id)->save(['status' => 1]);
        if ($rst === false) {
            return $this->error("审核失败！");
        } else {
            return $this->success("审核成功！");
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