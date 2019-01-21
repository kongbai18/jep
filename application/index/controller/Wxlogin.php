<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/7 0007
 * Time: 9:36
 */

namespace app\index\controller;

use app\index\model\WxUser as WxUserModel;


class Wxlogin extends Base
{
    public function _initialize()
    {

    }

    public function login()
    {
        $model = new WxUserModel;
        $user_id = $model->login(input('post.'));
        $token = $model->getToken();
        if($token){
            return show(config('code.success'),'登陆成功',$token);
        }
        return show(config('code.error'),'登陆失败');
    }

}