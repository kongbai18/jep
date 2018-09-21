<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/27 0027
 * Time: 17:04
 */

namespace app\platformmgmt\controller;

use app\common\lib\exception\ApiException;
use app\common\lib\IAuth;

use app\platformmgmt\model\Permission as PermissionModel;
use app\platformmgmt\controller\Base;

class Login extends Base
{
    /**
     * 覆盖base初始化
     */
    public function _initialize() {

    }

    /**
     *
     * @SWG\Get(path="/platformMgmt/v1/login",
     *   summary="后台登陆",
     *   description="后台系统登陆功能，返回二级权限列表",
     *     @SWG\Parameter(name="username",in="query",type="string",required="true",
     *      description="用户名"
     *   ),
     *     @SWG\Parameter(name="password",in="query",type="string",required="true",
     *      description="用户密码"
     *   ),
     *     @SWG\Parameter(name="code",in="query",type="string",required="true",
     *      description="验证码"
     *   ),
     *   @SWG\Response(response="返回json数组,包含状态码status,描述message，数据data，以及头部httpCode",
     *      description="状态码为 1 时成功返回数据二级权限列表data；为 0 时失败并返回提示失败原因message；为2 时则已经登陆状态 "
     *  )
     * )
     */
    public function index() {
        $isLogin = $this->isLogin();

        if($isLogin) {
            throw new ApiException('您已登陆',200,config('code.login'));
        }

        $data = input('post.');

        // validate
        $validate = validate('Login');
        if(!$validate->check($data)) {
            return show(config('code.error'), $validate->getError());
        }


        if (!captcha_check($data['code'])) {
            return show(config('code.error'),'验证码错误');
        }

        try {
            // username  username+password
            $user = model('Admin')->get(['username' => $data['username']]);
        }catch (\Exception $e) {
            return show(config('code.error'),$e->getMessage());
        }

        if (!$user || $user->status != 1) {
            return show(config('code.error'),'该用户不存在');
        }

        // 再对密码进行校验
        if (IAuth::setPassword($data['password']) != $user['password']) {
            return show(config('code.error'),'密码不正确');
        }
        // 1 更新数据库 登录时间 登录ip
        $udata = [
            'last_login_time' => time(),
            'last_login_ip' => ipton(request()->ip()),
        ];

        try {
            model('Admin')->save($udata, ['id' => $user->id]);
        }catch (\Exception $e) {
            return show(config('code.error'),$e->getMessage(),'',500);
        }

        //2.获取用户权限两级列表
        $permissionMode = new PermissionModel();
        $btnData = $permissionMode->getBtns($user->id);

        $data = [
            'btnData' => $btnData
        ];

        // 3 session
        session(config('admin.session_user'), $user);

        return show(config('code.success'),'登录成功',$data);

    }

    /**
     *
     * @SWG\Get(path="/platformMgmt/v1/logout",
     *   summary="退出后台登陆",
     *   description="后台系统登陆功能，返回二级权限列表",
     *   @SWG\Response(response="返回json数组,包含状态码status,描述message，数据data，以及头部httpCode",
     *      description="状态码为 1 时成功；为 0 时失败并返回提示失败原因message； "
     *  )
     * )
     */
    public function logout() {
        session(null);
        // 跳转
        return show(config('code.success'),'退出成功');
    }
}