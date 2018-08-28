<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/27 0027
 * Time: 17:04
 */

namespace app\platformMgmt\controller;

use app\common\lib\IAuth;

class Login extends Base
{
    /**
     * 覆盖base初始化
     */
    public function _initialize() {
    }

    /**
     * 登陆页
     * @return mixed|void
     */
    public function index()
    {
        $isLogin = $this->isLogin();
        if($isLogin) {
            return $this->redirect('index/index');
        }else {
            // 如果后台用户已经登录了， 那么我们需要跳到后台页面
            return $this->fetch();
        }
    }

    /**
     * 登录相关业务
     */
    public function check() {
        if(request()->isPost()) {
            $data = input('post.');
            if (!captcha_check($data['code'])) {
                $this->result('',0,'验证码不正确');
            }
            // 判定 username password
            // validate机制 小伙伴自行完成

            try {
                // username  username+password
                $user = model('Admin')->get(['username' => $data['username']]);
            }catch (\Exception $e) {
                $this->result('',0,$e->getMessage());
            }

            if (!$user || $user->status != config('code.status_normal')) {
                $this->result('',0,'该用户不存在');
            }

            // 再对密码进行校验
            if (IAuth::setPassword($data['password']) != $user['password']) {
                $this->result('',0,'密码不正确');
            }
            // 1 更新数据库 登录时间 登录ip
            $udata = [
                'last_login_time' => time(),
                'last_login_ip' => request()->ip(),
            ];

            try {
                model('Admin')->save($udata, ['id' => $user->id]);
            }catch (\Exception $e) {
                $this->result('',0,$e->getMessage());
            }
            // 2 session
            session(config('admin.session_user'), $user);
            $this->result(['jump_url' => url('index/index')],1,'登录成功');

            //halt($user);
        }else {
            $this->result('',0,'请求不合法');
        }
    }

    /**
     * 退出登录的逻辑
     * 1、清空session
     * 2、 跳转到登录页面
     */
    public function logout() {
        session(null);
        // 跳转
        $this->redirect('login/index');
    }
}