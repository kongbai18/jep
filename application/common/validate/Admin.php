<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/27 0027
 * Time: 14:49
 */

namespace app\common\validate;


use think\Validate;

class Admin extends Validate
{
    protected $rule = [
        'username' => 'require|max:30|unique:admin',
        'old_password' => 'require',
        'password' => 'require|min:6',
        'password2' => 'require|confirm:password',
        'email' => 'email',
        'role_id' => 'require',
    ];

    protected $message = [
        'username.require' => '名称不能为空',
        'username.max' => '名称不得超过30字符',
        'password.require' => '必须输入密码',
        'password.min' => '密码至少六位字符',
        'password2.require' => '确认密码不能为空',
        'password2.confirm' => '两次输入密码不相同',
        'username.unique' => '名称已存在',
        'role_id' => '至少选择一种角色',
        'old_password' => '原始密码不能为空',
    ];

    protected $scene = [
        'add' => ['username','password','password2','email','role_id'],
        'edit'  =>  ['username','email','role_id'],
        'editOne'  =>  ['username','old_password','email'],
        'setPass'  =>  ['password','password2'],
    ];
}