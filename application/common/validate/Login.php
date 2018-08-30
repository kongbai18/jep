<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/29 0029
 * Time: 12:35
 */

namespace app\common\validate;


use think\Validate;

class Login extends Validate
{
    protected $rule = [
        'username' => 'require',
        'password' => 'require',
        'code' => 'require',
    ];

    protected $message = [
        'username.require' => '名称不能为空',
        'password.require' => '密码不能为空',
        'code.require' => '验证码不能为空',
    ];

}