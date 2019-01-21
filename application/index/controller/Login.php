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

    public function islog(){
        if($this->isLogin()){
            return show(config('code.success'),'已登陆');
        }

        return show(config('code.error'),'暂未登陆');
    }

    public function login(){
        $data = input('post.');

        //validate
        $validate = validate('login');
        if(!$validate->check($data)) {
            return show(config('code.error'), $validate->getError());
        }

        if(!captcha_check($data['code'])){
            return show(config('code.error'),'验证码错误!');
        }

        try{
            $user = model('user')->where(['username'=>['eq',$data['username']]])->find();
        }catch (\Exception $e){
            return show(config('code.error'),$e->getMessage(),'',500);
        }


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
            'last_login_ip' => request()->ip(),
        ];

        try {
            model('user')->save($udata, ['id' => $user->id]);
        }catch (\Exception $e) {
            return show(config('code.error'),$e->getMessage(),'',500);
        }

        //设置session
        session(config('user.session_user'),$user,config('user.session_user_scope'));

        $rdata = [
            'username' => $user['username'],
            'image_url' => $user['image_url'],
        ];

        return show(config('code.success'),'登陆成功！',$rdata);

    }


    public function logout() {
        session(null,config('user.session_user_scope'));
        // 跳转
        return show(config('code.success'),'退出成功');
    }
}