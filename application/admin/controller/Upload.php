<?php
/**
 * File: Upload.php.
 * User: AloneH
 * Date: 2019/4/29
 * Time: 20:58
 * Project: layuiadmin
 * Home: http://oibit.cn
 * License: MIT
 */

namespace app\admin\controller;

use think\Exception;

class Upload extends Admin
{

    protected $size = 0;
    protected $ext = [];

    public function __construct()
    {
        parent::__construct();
        $this->size = model('Option')->get('upload_size')->value * 1024;
        $this->ext = explode('|', model('Option')->get('upload_type')->value);
        $this->Model = model('Upload');
    }

    public function index() {
        return $this->view->fetch();
    }

    public function read() {
        $page = $this->request->param("page/d", 1);
        $limit = $this->request->param("limit/d", 15);
        $keyword = $this->request->param("keyword/s", "");
        $Upload = $this->Model
            ->where("url|create_time|update_time", "like", '%' . $keyword . "%")
            ->where('id', 'like', is_numeric($keyword) ? $keyword : '%%')
            ->page($page, $limit)
            ->order(['id' => 'asc'])
            ->select();

        $pages = $this->Model
            ->where("url|create_time|update_time", '%' . $keyword . "%")
            ->where('id', 'like', is_numeric($keyword) ? $keyword : '%%')
            ->count();

        return $this->success("获取数据成功", url('admin/article/read'), $Upload, $pages);
    }

    public function delete() {
        $id = $this->request->param("id/d", 0);
        $Upload = $this->Model->get($id);
        unlink($Upload->url);
        $nums = $Upload->delete();
        if ($nums) {
            return $this->success("删除成功！");
        } else {
            return $this->error("删除失败！");
        }
    }

    /**
     * 上传图片
     * @param $field 文件字段
     * @return object|void 返回对象
     */
    public function picture($field) {
        $Ret = [
            'status' => false,
            'msg' => null,
        ];
        if ( is_array($field) ){

        } else {
            try {
                $file = request()->file($field);
                $info = $file->validate(['size'=>$this->size, 'ext'=> $this->ext])->move( '.\uploads\images');
                if($info) {
                    $data = [
                        'url' => '\\uploads\\images\\' . $info->getSaveName(),
                        'size' => $info->getSize(),
                    ];
                    $this->Model->save($data);
                    $Ret['status'] = true;
                    $Ret['msg'] = '\\uploads\\images\\' . $info->getSaveName();
                } else {
                    $Ret['msg'] = $file->getError();
                }
            } catch (Exception $msg) {
                $Ret['msg'] = $msg;
            }
        }
        return array_to_object($Ret);
    }



}