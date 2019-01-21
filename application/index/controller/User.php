<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/24 0024
 * Time: 14:12
 */

namespace app\index\controller;

use app\common\lib\email\Email;
use app\index\model\User as UserModel;
use app\common\lib\IAuth;

class User extends Base
{
    public function _initialize(){

    }

    public function index(){
        $result = $this->isLogin();
        if(!$result){
            return show(config('code.logout'),'暂未登陆');
        }
        $id=$this->userId;
        try{
            $data = model('user')->field('username,phone,email,image_url,last_login_time')->find($id);
        }catch (\Exception $e){
            return show(config('code.error'),'获取个人信息失败');
        }
        return show(config('code.success'),'获取个人信息成功',$data);
    }

    public function save(){
        $data = input('post.');

        if(time()-session('emailTime') > 600){
            return show(config('code.error'),'验证码超时');
        }

        if($data['code'] != session('emailCode')){
            return show(config('code.error'),'验证码错误');
        }

        // validate
        $validate = validate('User');
        if(!$validate->scene('add')->check($data)) {
            return show(config('code.error'), $validate->getError());
        }

        $userModel = new UserModel();
        $data['password'] = IAuth::setPassword($data['password']);
        $result = $userModel->add($data);

        if($result){
            return show(config('code.success'),'注册用户成功！');
        }

        return show(config('code.error'),'注册用户失败');


    }

    public function editPass(){
        $result = $this->isLogin();
        if(!$result){
            return show(config('code.logout'),'暂未登陆');
        }
        $id=$this->userId;

        $data = input('post.');
        if($data['password'] !== $data['password1']){
            return show(config('code.error'),'两次密码输入不相同');
        }

        try{
            $userModel = new UserModel();
            /*$udata = [
                'password' => IAuth::setPassword($data['password']),
            ];*/
            $data['password'] = IAuth::setPassword($data['password']);
            $data['id'] = $id;
            /*$userModel->where('id','eq',$id)->update($udata);*/
            $userModel->edit($data);
        }catch (\Exception $e){
            return show(config('code.error'),'修改失败');
        }

        return show(config('code.success'),'修改成功');
    }

    public function getEmailCode(){
        $data = input('post.');

        $validate = validate('User');
        if(!$validate->scene('getcode')->check($data)) {
            return show(config('code.error'), $validate->getError());
        }

        $code = rand(100000,999999);
        session('email',$data['email']);
        session('emailCode',$code);
        session('emailTime',time());

        $email = new Email();
        $result = $email->sendEmail($data['email'],$code);

        if($result){
            return show(config('code.success'),'获取验证码成功！');
        }

        return show(config('code.error'),'获取验证码失败！');
    }
}