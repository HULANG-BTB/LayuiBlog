<?php
/**
 * File: Article.php.
 * User: AloneH
 * Date: 2019/4/30
 * Time: 22:12
 * Project: layuiadmin
 * Home: http://oibit.cn
 * License: MIT
 */

namespace app\index\controller;


class Article extends Blog
{

    protected $TagModel = null;

    public function __construct()
    {
        parent::__construct();
        $this->Model = model('Article');
        $this->TagModel = model('Tag');
    }

    public function read() {
        $limit = config('paginate.list_rows');
        $page = $this->request->param('page/d', '1');
        $ArticleList = $this->Model
            ->where(['del' => 0, 'top' => 0, 'status' => 0])
            ->field(['id', 'title', 'abstract', 'category_id', 'thumbnail', 'create_time', 'comment_count', 'view_count'])
            ->order(['id' => 'desc'])
            ->page($page, $limit)
            ->select();
        foreach ($ArticleList as $key => $val) {
            $ArticleList[$key]['category_name'] = $this->Model->get($val['id'])->category->name;
        }
        return $this->success("获取数据成功", url('index/index/index'), $ArticleList);
    }

    public function readtop() {
        $ArticleList = $this->Model
            ->where(['del' => 0, 'top' => 1, 'status' => 0])
            ->field(['id', 'title', 'abstract', 'category_id', 'thumbnail', 'create_time', 'comment_count', 'view_count'])
            ->select();
        foreach ($ArticleList as $key => $val) {
            $ArticleList[$key]['category_name'] = $this->Model->get($val['id'])->category->name;
        }
        return $this->success("获取数据成功", url('index/index/index'), $ArticleList);
    }

    public function getCount() {
        $count = $this->Model->where(['del' => 0, 'top' => 0, 'status' => 0])->count();
        return $this->success("获取条数成功" , url('index/index/index'), $count);
    }

    public function rank() {
        $ArticleList = $this->Model
            ->where(['del' => 0, 'top' => 0, 'status' => 0])
            ->field(['id', 'title', 'thumbnail', 'comment_count', 'view_count'])
            ->order(['view_count' => 'desc'])
            ->limit(0,5)
            ->select();
        return $this->success("获取数据成功", url('index/index/index'), $ArticleList);
    }

    public function detail() {
        $id = $this->request->param('id/d', 0);
        $Article = $this->Model->get($id);
        if (empty($Article)) {
            return $this->error("非法操作！");
        }
        $this->assign("Title", $Article->title . "|" . $this->Title);
        $this->assign("Description", $Article->abstract . "|" . $this->Description);
        $Article->setInc('view_count', 1);
        $Article['tag'] = $this->TagModel->all(explode(",", $Article->tags_list));
        $this->assign("Article", $Article);
        return $this->view->fetch();
    }

    public function search() {

    }

}