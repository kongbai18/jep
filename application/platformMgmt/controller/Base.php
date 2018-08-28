<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/27 0027
 * Time: 16:39
 */

namespace app\platformMgmt\controller;


use think\Controller;
use think\Request;

class Base extends Controller
{
    /**
     * 初始化的方法
     */
    public function _initialize() {
        // 判定用户是否登录
        $isLogin = $this->isLogin();
        if(!$isLogin) {
            return $this->redirect('login/index');
        }
    }

    /**
     * 判定是否登录
     * @return bool
     */
    public function isLogin() {
        return true;
        //获取session
        $user = session(config('admin.session_user'), '', config('admin.session_user_scope'));
        if($user && $user->id) {
            return true;
        }
        return false;
    }

}