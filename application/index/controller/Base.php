<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/24 0024
 * Time: 9:43
 */

namespace app\index\controller;


use think\Controller;
use app\common\lib\exception\ApiException;

class Base extends Controller
{
    public $userId = '';

    public function _initialize()
    {
        //判断是否携带token，携带则为小程序登陆

        //判断token是否存在，不存在则往回未登录

        // 判定用户是否登录
        $isLogin = $this->isLogin();

        if(!$isLogin){
            throw new ApiException('暂未登陆',200,config('code.logout'));
        }

    }

    private function isLogin(){
        //获取session
        $user = session(config('user.session_user'), '', config('user.session_user_scope'));
        if($user && $user->id) {
            $this->userId = $user->id;
            return true;
        }else{
            return false;
        }
    }

}