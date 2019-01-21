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
use think\Cache;
use think\Request;

class Base extends Controller
{
    public $userId = '';

    public function _initialize()
    {
        //判断是否携带token，携带则为小程序登陆
        $isLogin = $this->isLogin();

        // 判定用户是否登录
        if(!$isLogin){
            throw new ApiException('暂未登陆',200,config('code.logout'));
        }

    }

    protected function isLogin(){
        $data = Request::instance()->header();
        if(isset($data['token'])){
            if($this->userId = Cache::get($data['token'])){
                return true;
            }else{
                return false;
            }
        }
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