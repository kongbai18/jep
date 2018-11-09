<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/26 0026
 * Time: 12:47
 */

namespace app\common\validate;

use think\Validate;
class Category extends Validate
{
    protected $rule = [
        'name' => 'require|max:30',
        'parent_id' => 'require|^\d+$',
        'sort_id' => '^([1-9][0-9]*)$',
    ];

    protected $message = [
        'name.require' => '名称不能为空',
        'name.max' => '权限名称不得超过30字符',
        'parent_id' => '上级权限必须为非负整数',
        'sort_id' =>'排序必须为正整数',
    ];
}