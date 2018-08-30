<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/27 0027
 * Time: 16:39
 */

namespace app\platformmgmt\controller;


use think\Controller;
use app\common\lib\exception\ApiException;
use app\platformmgmt\model\Permission as PermissionModel;

class Base extends Controller
{

    /**
     * 初始化
     * @throws ApiException
     */
    public function _initialize()
    {
        // 判定用户是否登录
        $isLogin = $this->isLogin();

        if(!$isLogin){
            throw new ApiException('暂未登陆',200,config('code.logout'));
        }

        $this->chkPer();
    }
    /**
     * 判定是否登录
     * @return bool
     */
    protected function isLogin() {
        //获取session
        $user = session(config('admin.session_user'), '', config('admin.session_user_scope'));

        if($user && $user->id) {
            return true;
        }else{
            return false;
        }
    }

    protected function chkPer(){
        $permisssionModel = new PermissionModel();

        $per = $permisssionModel->chkPri();

        if(!$per){
            throw new ApiException('无操作权限',400,config('code.unper'));
        }
    }

}