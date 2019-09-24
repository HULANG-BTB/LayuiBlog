<?php
namespace app\index\controller;

class Index extends Blog
{

    public function __construct()
    {
        parent::__construct();
        $this->TagModel = model('Tag');
    }

    public function index()
    {
        return $this->view->fetch();
    }



}
