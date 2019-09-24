<?php
/**
 * File: Article.php.
 * User: AloneH
 * Date: 2019/4/29
 * Time: 20:27
 * Project: layuiadmin
 * Home: http://oibit.cn
 * License: MIT
 */

namespace app\admin\controller;

class Article extends Admin
{

    protected $Tag = null;
    protected $Category = null;

    public function __construct()
    {
        parent::__construct();
        $this->Model = model('Article');
        $this->TagModel = model('Tag');
        $this->CategoryModel = model('Category');
    }

    public function index(){

        return $this->view->fetch();

    }

    public function read() {
        $page = $this->request->param("page/d", 1);
        $limit = $this->request->param("limit/d", 15);
        $del = $this->request->param("del/d", 0);
        $keyword = '%' . $this->request->param("keyword/s", "") . "%";

        $articles = $this->Model->where(['status' => 0, 'del' => $del])
            ->where("title|abstract|content|create_time|update_time", 'like' , $keyword)
            ->where('id', 'like', is_numeric($keyword) ? $keyword : '%%')
            ->page($page, $limit)
            ->order(['id' => 'asc'])
            ->select();

        $pages = $this->Model->where(['status' => 0, 'del' => $del])
            ->where("title|abstract|content|create_time|update_time", "like", $keyword)
            ->where('id', 'like', is_numeric($keyword) ? $keyword : '%%')
            ->count();

        return $this->success("获取数据成功", url('admin/article/read'), $articles, $pages);
    }

    public function add(){
        $Tag = $this->TagModel->select();
        $this->assign("Tag", $Tag);
        $Category = $this->CategoryModel->select();
        $this->assign("Category", $Category);
        return $this->view->fetch();
    }

    public function insert() {
        if ( !$this->request->isPost() ) {
            return $this->error("非法操作！");
        }
        $title = $this->request->param('title');
        $abstract = $this->request->param('abstract');
        $content = $this->request->param('content');
        $tags = implode("|", $this->request->param('tags/a'));
        $category = $this->request->param('category/d');
        $thumbnail = "";
        if ( strlen($abstract) == 0 ) {
            $abstract = substr($content,0, min(510, strlen($content)));
            $abstract = str_replace("\r", "\\r", $abstract);
            $abstract = str_replace("\n", "\\r", $abstract);
            $abstract = str_replace("\r\n", "\\r\\n", $abstract);
        }

        $Upload = new Upload();
        $UpRet = $Upload->picture("thumbnail");

        if ($UpRet->status) {
            $thumbnail = $UpRet->msg;
        } else {
            $thumbnail = "/uploads/Images/thumbnail/default.png";
        }

        $data = [
            'title' => $title,
            'abstract' => $abstract,
            'content'=> $content,
            'tags_list' => $tags,
            'thumbnail' => $thumbnail,
            'category_id' => $category,
        ];

        $result = $this->Model->save($data);

        if ($result) {
            return $this->success("发布文章成功！");
        } else {
            return $this->error("新增文章失败！");
        }
    }

    public function delete() {
        $id = $this->request->param('id/d|0');
        $rst = $this->Model->get($id)->save(['id' => $id, 'del' => 1]);
        if ($rst === false) {
            return $this->error("删除失败！");
        } else {
            return $this->success("删除成功！");
        }
    }

    public function picture() {
        $ret = [
            'success' => 0,
            'message' => null,
            'url' => null,
        ];
        $Upload = new Upload();
        $result = $Upload->picture("editormd-image-file");
        if ($result->status) {
            $ret['success'] = 1;
            $ret['message'] = "上传图片成功！";
            $ret['url'] = $result->msg;
        } else {
            $ret['message'] = $result->msg;
        }
        return json($ret);
    }

    public function edit() {
        $id = $this->request->param('id/d', 0);
        if ($id == 0)
        {
            return $this->error('非法操作！');
        }
        $Article = $this->Model->get($id)->toArray();
        if (empty($Article))
        {
            return $this->error('非法操作！');
        }

        $Article['abstract'] = str_replace("\r", "\\r", $Article['abstract']);
        $Article['abstract'] = str_replace("\n", "\\r", $Article['abstract']);
        $Article['abstract'] = str_replace("\r\n", "\\r\\n", $Article['abstract']);

        $this->assign("Article", $Article);
        $Tag = $this->TagModel->select();
        $this->assign("Tag", $Tag);
        $Category = $this->CategoryModel->select();
        $this->assign("Category", $Category);

        $thisTags = explode("|", $Article['tags_list']);
        $thisCategory = $Article['category_id'];
        $this->assign("thisTags", $thisTags);
        $this->assign("thisCategory", $thisCategory);
        return $this->fetch();

    }

    public function update() {
        if ( !$this->request->isPost() ) {
            return $this->error("非法操作");
        }
        $id = $this->request->param('id');
        $title = $this->request->param('title');
        $abstract = $this->request->param('abstract');
        $content = $this->request->param('content');
        $tags = str_replace(",","|",$this->request->param('tags/a'));
        $category = $this->request->param('category/d');
        if ( strlen($abstract) == 0 ) {
            $abstract = substr($content,0, min(510, strlen($content)));
            $abstract = str_replace("\r", "\\r", $abstract);
            $abstract = str_replace("\n", "\\r", $abstract);
            $abstract = str_replace("\r\n", "\\r\\n", $abstract);
        }

        $data = [
            'id' => $id,
            'title' => $title,
            'abstract' => $abstract,
            'content'=> $content,
            'tags_list' => $tags,
            'category_id' => $category,
        ];

        $Upload = new Upload();
        $UpRet = $Upload->picture("thumbnail");

        if ($UpRet->status) {
            $data['thumbnail'] = $UpRet->msg;
        }

        $result = $this->Model->get($id)->save($data);

        if ($result) {
            return $this->success("修改成功！");
        } else {
            return $this->error("修改失败！");
        }
    }

    public function recycle() {
        return $this->view->fetch();
    }

    public function recovery() {
        $id = $this->request->param('id/d|0');
        $rst = $this->Model->get($id)->save(['id' => $id, 'del' => 0]);
        if ($rst === false) {
            return $this->error("恢复失败！");
        } else {
            return $this->success("恢复成功！");
        }
    }
}