<?php
/**
 * File: Tag.php.
 * User: AloneH
 * Date: 2019/4/30
 * Time: 22:17
 * Project: layuiadmin
 * Home: http://oibit.cn
 * License: MIT
 */

namespace app\index\controller;


class Tag extends Blog
{

    protected $ArticleModel = null;

    public function __construct()
    {
        parent::__construct();
        $this->Model = model('Tag');
        $this->ArticleModel = model('Article');
    }

    public function read() {
        $TagList = $this->Model->select();
        if ( !empty($TagList) ) {
            return $this->success("获取数据成功!", url('index/index/index'), $TagList);
        }
        return $this->error("未能成功获取数据!");
    }

    public function index() {
        $id = $this->request->param("id/d", "0");
        $Tag = $this->Model->get($id);
        $this->assign("Tag", $Tag);
        $this->assign("Title", $Tag->name . "|" . $this->Title);
        return $this->view->fetch();
    }

    public function getList() {
        $limit = config('paginate.list_rows');
        $tag = $this->request->param("tag/d", "1");
        $page = $this->request->param('page/d', '1');
        $ArticleList = $this->ArticleModel
            ->where(['del' => 0, 'top' => 0, 'status' => 0])
            ->whereLike('tags_list', $tag . ",%", "and")
            ->whereLike('tags_list', '%,' . $tag . ",%", "or")
            ->whereLike('tags_list', '%,' . $tag , "or")
            ->field(['id', 'title', 'abstract', 'category_id', 'thumbnail', 'create_time', 'comment_count', 'view_count'])
            ->order(['id' => 'desc'])
            ->page($page, $limit)
            ->select();
        foreach ($ArticleList as $key => $val) {
            $ArticleList[$key]['category_name'] = $this->ArticleModel->get($val['id'])->category->name;
        }
        return $this->success("获取数据成功", url('index/index/index'), $ArticleList);
    }

    public function getCount() {
        $tag = $this->request->param("tag/d", "1");
        $count = $this->ArticleModel
            ->where(['del' => 0, 'top' => 0, 'status' => 0])
            ->whereLike('tags_list', $tag . ",%", "and")
            ->whereLike('tags_list', '%,' . $tag . ",%", "or")
            ->whereLike('tags_list', '%,' . $tag , "or")
            ->count();
        return $this->success("获取条数成功" , url('index/index/index'), $count);
    }

}