<?php
/**
 * File: Category.php.
 * User: AloneH
 * Date: 2019/5/1
 * Time: 22:22
 * Project: layuiadmin
 * Home: http://oibit.cn
 * License: MIT
 */

namespace app\index\controller;


class Category extends Blog
{
    protected $ArticleModel = null;

    public function __construct()
    {
        parent::__construct();
        $this->ArticleModel = model('Article');
        $this->Model = model('Category');
    }

    public function index() {
        $id = $this->request->param("id/d", "0");
        $Category = $this->Model->get($id);
        $this->assign("Category", $Category);
        $this->assign("Title", $Category->name . "|" . $this->Title);
        return $this->view->fetch();
    }

    public function read() {
        $limit = config('paginate.list_rows');
        $category = $this->request->param("category/d", "1");
        $page = $this->request->param('page/d', '1');
        $ArticleList = $this->ArticleModel
            ->where(['del' => 0, 'top' => 0, 'status' => 0, 'category_id' => $category])
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
        $category = $this->request->param("category/d", "1");
        $count = $this->ArticleModel->where(['del' => 0, 'top' => 0, 'status' => 0, 'category_id' => $category])->count();
        return $this->success("获取条数成功" , url('index/index/index'), $count);
    }

}