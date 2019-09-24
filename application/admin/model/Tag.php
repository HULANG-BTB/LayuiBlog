<?php
/**
 * File: Tag.php.
 * User: AloneH
 * Date: 2019/4/29
 * Time: 20:30
 * Project: layuiadmin
 * Home: http://oibit.cn
 * License: MIT
 */

namespace app\admin\model;

use think\Model;

class Tag extends Model
{
    protected $autoWriteTimestamp = 'int';
    /**
     * 时间戳字段名
     * @var string
     */
    protected $createTime = 'create_time';
    protected $updateTime = 'update_time';
}