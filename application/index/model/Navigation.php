<?php
/**
 * File: Navigation.php.
 * User: AloneH
 * Date: 2019/4/30
 * Time: 17:56
 * Project: layuiadmin
 * Home: http://oibit.cn
 * License: MIT
 */

namespace app\index\model;


use think\Model;

class Navigation extends Model
{
    protected $autoWriteTimestamp = 'int';
    /**
     * 时间戳字段名
     * @var string
     */
    protected $createTime = 'create_time';
    protected $updateTime = 'update_time';
}