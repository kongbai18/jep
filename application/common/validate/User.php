<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/28 0028
 * Time: 13:18
 */
namespace app\common\validate;


use think\Validate;

class User extends Validate
{
    protected $rule = [
        'username' => 'require|max:30|unique:user',
        'old_password' => 'require',
        'password' => 'require|min:6',
        'password1' => 'require|confirm:password',
        'email' => 'require|email|unique:user',
    ];

    protected $message = [
        'username.require' => '名称不能为空',
        'username.max' => '名称不得超过30字符',
        'password.require' => '必须输入密码',
        'password.min' => '密码至少六位字符',
        'password1.require' => '确认密码不能为空',
        'password1.confirm' => '两次输入密码不相同',
        'username.unique' => '名称已存在',
        'email.require' => '邮箱不能为空',
        'email.unique' => '邮箱已存在',
    ];

    protected $scene = [
        'add' => ['username','password','password1','email'],
        'getcode'  =>  ['email'],
    ];
}