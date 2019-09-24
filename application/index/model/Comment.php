<?php
/**
 * File: Comment.php.
 * User: AloneH
 * Date: 2019/4/30
 * Time: 9:29
 * Project: layuiadmin
 * Home: http://oibit.cn
 * License: MIT
 */

namespace app\index\model;


use think\Model;

class Comment extends Model
{
    protected $autoWriteTimestamp = 'int';
    /**
     * 时间戳字段名
     * @var string
     */
    protected $createTime = 'create_time';
    protected $updateTime = 'update_time';


    public function getParentAttr($value, $data) {
        return $data['parent_id'];
    }

    public function article() {
        return $this->hasOne('Article', 'id', 'article_id');
    }

    public function user() {
        return $this->hasOne('app\index\model\user\User', 'id', 'user_id')->field(['nickname', 'avator']);
    }

}