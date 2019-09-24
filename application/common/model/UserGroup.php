<?php
/**
 * File: UserGroup.php.
 * User: AloneH
 * Date: 2019/4/28
 * Time: 10:13
 * Project: layuiadmin
 * Home: http://oibit.cn
 * License: MIT
 */

namespace app\common\model;

use think\Model;

/**
 * Class UserGroup
 * @package app\common\model
 */

class UserGroup extends Model
{
    /**
     * 设置主键
     * @var string
     */
    protected $pk = 'id';
    /**
     * 自动写入时间戳字段
     * @var string
     */
    protected $autoWriteTimestamp = 'int';
    /**
     * 时间戳字段名
     * @var string
     */
    protected $createTime = 'create_time';
    protected $updateTime = 'update_time';

}