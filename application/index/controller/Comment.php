<?php
/**
 * File: Comment.php.
 * User: AloneH
 * Date: 2019/5/2
 * Time: 9:12
 * Project: layuiadmin
 * Home: http://oibit.cn
 * License: MIT
 */

namespace app\index\controller;


class Comment extends Blog
{
    public function __construct()
    {
        parent::__construct();
        $this->Model = model('Comment');
        $this->User = session('user');
    }

    public function  getComment() {
        $Aid = $this->request->param("id/d", 0);
        $data = $this->commentList($Aid, 0);
        return $this->success("获取数据成功!", url('index/index/index'), $data);
    }

    public function reply() {
        $Aid = $this->request->param("Aid/d", 0);
        $Pid = $this->request->param("Pid/d", 0);

        $this->assign("Aid", $Aid);
        $this->assign("Pid", $Pid);

        return $this->view->fetch();
    }

    private function commentList($Aid, $Pid) {
        $list = $this->Model
            ->where(['article_id' => $Aid, 'parent_id' => $Pid, 'del' => '0', 'status' => '1'])
            ->select();
        $ret = [];
        foreach ($list as $key => $val) {
            if ( !$list[$key]->user ){
                $list[$key]['user'] = [
                    'nickname' => '匿名用户',
                    'avator' => "/uploads/images/avator/1.jpg"
                ];
            }
            $ret[$key] = $list[$key]->toArray();
            $ret[$key]['child'] = $this->commentList($Aid, $list[$key]->id);
        }
        return $ret;
    }

    public function insert() {
        $data = $this->request->param();
        if (empty($data)) {
            return $this->error("非法操作！");
        }
        if (empty($this->User)) {
            $data['user_id'] = 0;
            // return $this->error("请先登录！");
        } else {
            $data['user_id'] = $this->User->id;
        }
        $data['ip'] = get_client_ipaddress();
        $data['city'] = get_client_city($data['ip']);
        $rst = $this->Model->save($data);
        if ($rst) {
            return $this->success("评论成功,审核后显示！");
        } else {
            return $this->success("评论失败！");
        }
    }

    public function getNewComment() {
        $list = $this->Model
            ->where(['del' => '0', 'status' => '1'])
            ->order(['id' => 'desc'])
            ->limit(0,12)
            ->select();
        $ret = [];
        foreach ($list as $key => $val) {
            $ret[$key] = $list[$key]->toArray();
            $ret[$key]['context'] = htmlspecialchars($ret[$key]['context']);
        }
        return $this->success("获取数据成功!", url("index/index/index"), $ret, 3);
    }

}