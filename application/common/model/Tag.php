<?php
/**
 * File: Tag.php.
 * User: AloneH
 * Date: 2019/4/28
 * Time: 12:55
 * Project: layuiadmin
 * Home: http://oibit.cn
 * License: MIT
 */

namespace app\common\model;

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