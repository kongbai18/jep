<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/24 0024
 * Time: 14:29
 */

namespace app\index\controller;


use app\common\lib\IAuth;

class Login extends Base
{
    public function _initialize(){

    }

    public function login(){
        $data = input('post.');

        if(!captcha_check($data['code'])){
            return show(config('code.error'),'验证码错误!');
        }

        $user = model('user')->where(['username'=>['eq',$data['username']]])->find();

        if(!$user){
            return show(config('code.error'),'该账户不存在！');
        }

        if(IAuth::setPassword($data['password']) != $user->password){
            return show(config('code.error'),'密码输入错误！');
        }
        //1.更新数据库登陆时间
        // 1 更新数据库 登录时间 登录ip
        $udata = [
            'last_login_time' => time(),
            'last_login_ip' => ipton(request()->ip()),
        ];

        try {
            model('user')->save($udata, ['id' => $user->id]);
        }catch (\Exception $e) {
            return show(config('code.error'),$e->getMessage(),'',500);
        }

        //设置session
        session(config('user.session_user'),$user,config('user.session_user_scope'));

        return show(config('code.success'),'登陆成功！');

    }
}