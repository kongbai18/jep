<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/27 0027
 * Time: 9:34
 */

namespace app\common\validate;

use think\Validate;

class Role extends Validate
{
    protected $rule = [
        'name' => 'require|max:30|unique:role',
        'per_id' => 'require',
        'content' => 'max:150',
    ];

    protected $message = [
        'name.require' => '名称不能为空',
        'name.max' => '角色名称不得超过30字符',
        'content.max' => '备注不得超过150字符',
        'name.unique' => '角色名称已存在',
        'per_id' => '至少选择一种权限',

    ];
}