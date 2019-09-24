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

namespace app\admin\model;


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

    public function getArticleAttr( $value, $data) {
        return UserGroup::get($data['article_id']);
    }

    public function getParentAttr( $value, $data) {
        return UserGroup::get($data['parent_id']);
    }
}