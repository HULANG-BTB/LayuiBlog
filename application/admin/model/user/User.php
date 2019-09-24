<?php
/**
 * File: User.php.
 * User: AloneH
 * Date: 2019/4/29
 * Time: 19:41
 * Project: layuiadmin
 * Home: http://oibit.cn
 * License: MIT
 */

namespace app\admin\model\user;

use think\Model;

class User extends Model
{
    /**
     * 设置主键
     * @var string
     */
    protected $pk = "id";
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

    public function getGroupAttr( $value, $data) {
        return UserGroup::get($data['group_id']);
    }
}