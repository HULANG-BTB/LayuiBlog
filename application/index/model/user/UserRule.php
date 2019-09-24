<?php
/**
 * File: UserRule.php.
 * User: AloneH
 * Date: 2019/4/29
 * Time: 22:34
 * Project: layuiadmin
 * Home: http://oibit.cn
 * License: MIT
 */

namespace app\index\model\user;


use think\Model;

class UserRule extends Model
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