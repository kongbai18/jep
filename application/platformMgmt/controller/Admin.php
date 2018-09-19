<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/26 0026
 * Time: 9:57
 */

namespace app\platformmgmt\controller;

use app\platformmgmt\model\Admin as AdminModel;
use app\common\lib\IAuth;
use app\platformmgmt\controller\Base;
class Admin extends Base
{
    /**
     *
     * 这里需要一个主`Swagger`定义：
     * @SWG\Swagger(
     *   schemes={"http"},
     *   host="localhost/jep/public",
     *   @SWG\Info(
     *     title="几和后端API文档",
     *     version="2.0",
     *   )
     * )
     */

    /**
     *
     * @SWG\Get(path="/platformMgmt/v1/admin",
     *   summary="获取管理员列表信息",
     *   description="请求该接口需要先登录并且有此权限,不需要传参",
     *   @SWG\Response(response="返回json数组,包含状态码status,描述message，数据data，以及头部httpCode",
     *      description="状态码为 1 时成功返回数据data；为 0 时失败并返回提示失败原因message；为 -1 时为未登录；为 -2 时为无此权限"
     *   )
     * )
     */
    public function index(){
        $adminModel = new AdminModel();

        $adminList = $adminModel->adminList();

        $data = [
            'adminList' => $adminList
        ];

        return show(config('code.success'),'获取管理员列表成功',$data);
    }

    /**
     *
     * @SWG\Get(path="/platformMgmt/v1/admin/{id}",
     *   summary="获取管理员信息",
     *   description="请求该接口需要先登录并且有此权限。",
     *   @SWG\Parameter(name="id",in="path",type="number",required="true",
     *      description="管理员ID"
     *   ),
     *   @SWG\Response(response="返回json数组,包含状态码status,描述message，数据data，以及头部httpCode",
     *      description="状态码为 1 时成功返回数据data；为 0 时失败并返回提示失败原因message；为 -1 时为未登录；为 -2 时为无此权限 "
     *   )
     * )
     */
    public function read($id){
        $adminModel = new AdminModel();

        $admin = $adminModel->find($id);

        if(empty($admin)){
            return show(config('code.error'),'该管理员不存在','', 404);
        }

        $adminData = $adminModel->getAdminRole($id);

        $data = [
            'adminData' => $adminData
        ];

        return show(config('code.success'),'获取管理员信息成功',$data);
    }

    /**
     *
     * @SWG\Post(path="/platformMgmt/v1/admin",
     *   summary="添加管理员",
     *   description="请求该接口需要先登录并且有此权限。",
     *   @SWG\Parameter(name="username",in="formData",type="string",required="true",
     *      description="管理员用户名,不得超过30字符"
     *   ),
     *   @SWG\Parameter(name="password",in="formData",type="string",required="true",
     *      description="密码，不得低于6位字符"
     *   ),
     *   @SWG\Parameter(name="password2",in="formData",type="string",required="true",
     *      description="密码确认，必须与password相同"
     *   ),
     *     @SWG\Parameter(name="phone",in="formData",type="string",
     *      description="手机号"
     *   ),
     *     @SWG\Parameter(name="email",in="formData",type="string",
     *      description="邮箱"
     *   ),
     *     @SWG\Parameter(name="role_id[]",in="formData",type="number",required="true",
     *      description="所属角色，为一个一维数组,可传多个"
     *   ),
     *
     *   @SWG\Response(response="返回json数组,包含状态码status,描述message，数据data，以及头部httpCode",
     *      description="状态码为 1 时成功；为 0 时失败并返回提示失败原因message；为 -1 时为未登录；为 -2 时为无此权限 "
     *   )
     * )
     */
    public function save(){
        $data = input('post.');

        // validate
        $validate = validate('Admin');
        if(!$validate->scene('add')->check($data)) {
            return show(config('code.error'), $validate->getError());
        }

        //密码加密
        $data['password'] = IAuth::setPassword($data['password']);

        if(!is_array($data['role_id'])){
            return show(config('code.error'),'传入角色数据不合法');
        }

        //入库操作
        $adminModel = new AdminModel();
        if($adminModel->addAdmin($data)){
            return show( config('code.success'), '新增管理员成功');
        }else{
            return show( config('code.error'), '新增失败','',500);
        }
    }


    /**
     *
     * @SWG\Get(path="/platformMgmt/v1/admin/{id}/edit",
     *   summary="修改管理员信息",
     *   description="请求该接口需要先登录并且有此权限。",
     *   @SWG\Parameter(name="id",in="path",type="string",required="true",
     *      description="管理员Id"
     *   ),
     *     @SWG\Parameter(name="username",in="query",type="string",required="true",
     *      description="管理员用户名,不得超过30字符"
     *   ),
     *   @SWG\Parameter(name="password",in="query",type="string",
     *      description="密码，不得低于6位字符,空白则不修改"
     *   ),
     *   @SWG\Parameter(name="password2",in="query",type="string",
     *      description="密码确认，必须与password相同"
     *   ),
     *     @SWG\Parameter(name="phone",in="query",type="string",
     *      description="手机号"
     *   ),
     *     @SWG\Parameter(name="email",in="query",type="string",
     *      description="邮箱"
     *   ),
     *     @SWG\Parameter(name="role_id[]",in="query",type="number",required="true",
     *      description="所属角色，为一个一维数组"
     *   ),
     *
     *   @SWG\Response(response="返回json数组,包含状态码status,描述message，数据data，以及头部httpCode",
     *      description="状态码为 1 时成功；为 0 时失败并返回提示失败原因message；为 -1 时为未登录；为 -2 时为无此权限 "
     *   )
     * )
     */
    public function edit($id){
        $admin = model('admin')->find($id);

        if(empty($admin)){
            return show(config('code.error'),'该管理员不存在','', 404);
        }

        if($id == 1){
            return show(config('code.error'),'初始用户数据不可修改');
        }
        $data = input('get.');
        $data['id'] = $id;

        // validate
        $validate = validate('Admin');
        if(!$validate->scene('edit')->check($data)) {
            return show(config('code.error'), $validate->getError());
        }

        //密码加密
        if(array_key_exists("password",$data)){
            if($data['password'] != ''){
                if(!$validate->scene('setPass')->check($data)) {
                    return show(config('code.error'), $validate->getError());
                }
                $data['password'] = IAuth::setPassword($data['password']);
            }else{
                unset($data['password']);
            }
        }

        //入库操作
        $adminModel = new AdminModel();
        if($adminModel->editAdmin($data)){
            return show( config('code.success'), '修改管理员成功');
        }else{
            return show(config('code.error'), '修改失败','',500);
        }

    }


    /**
     *
     * @SWG\Post(path="/platformMgmt/v1/admin/setPassword",
     *   summary="修改个人信息",
     *   description="请求该接口需要先登录",
     *     @SWG\Parameter(name="username",in="formData",type="string",required="true",
     *      description="管理员用户名,不得超过30字符"
     *   ),
     *     @SWG\Parameter(name="old_password",in="formData",type="string",required="true",
     *      description="原始密码"
     *   ),
     *   @SWG\Parameter(name="password",in="formData",type="string",
     *      description="密码，不得低于6位字符,空白则不修改"
     *   ),
     *   @SWG\Parameter(name="password2",in="formData",type="string",
     *      description="密码确认，必须与password相同"
     *   ),
     *     @SWG\Parameter(name="phone",in="formData",type="string",
     *      description="手机号"
     *   ),
     *     @SWG\Parameter(name="email",in="formData",type="string",
     *      description="邮箱"
     *   ),
     *
     *   @SWG\Response(response="返回json数组,包含状态码status,描述message，数据data，以及头部httpCode",
     *      description="状态码为 1 时成功；为 0 时失败并返回提示失败原因message；为 -1 时为未登录"
     *  )
     * )
     */
    public function setPassword(){
        $data = input('post.');
        //获取session
        $user = session(config('admin.session_user'), '', config('admin.session_user_scope'));
        $data['id'] = $user->id;

        //查询用户信息
        $admin = model('admin')->get($data['id']);

        // validate
        $validate = validate('Admin');
        if(!$validate->scene('editOne')->check($data)) {
            return show(config('code.error'), $validate->getError());
        }

        // 再对原始密码进行校验
        if (IAuth::setPassword($data['old_password']) != $admin['password']) {
            return show(config('code.error'),'原始密码不正确');
        }

        //密码加密
        if(array_key_exists("password",$data)){
            if($data['password'] != ''){
                if(!$validate->scene('setPass')->check($data)) {
                    return show(config('code.error'), $validate->getError());
                }
                $data['password'] = IAuth::setPassword($data['password']);
            }else{
                unset($data['password']);
            }
        }

        model('admin')->edit($data);

        return show(config('code.success'),'修改个人信息成功');
    }


    /**
     *
     * @SWG\Delete(path="/platformMgmt/v1/admin/{id}",
     *   summary="删除管理员",
     *   description="请求该接口需要先登录并且有此权限。",
     *   @SWG\Parameter(name="id",in="path",type="number",required="true",
     *      description="管理员ID"
     *   ),
     *   @SWG\Response(response="返回json数组,包含状态码status,描述message，数据data，以及头部httpCode",
     *      description="状态码为 1 时成功；为 0 时失败并返回提示失败原因message；为 -1 时为未登录；为 -2 时为无此权限 "
     *  )
     * )
     */
    public function delete($id){
        $admin = model('admin')->find($id);

        if(empty($admin)){
            return show(config('code.error'),'该管理员不存在','', 404);
        }

        if($id == 1){
            return show(0,'初始管理员不可删除');
        }
        $adminModel = new AdminModel();

        if($adminModel->remove($id)){
            return show(config('code.success'),'删除管理员成功');
        }
        return show(config('code.error'),'删除失败'.'',500);
    }

    /**
     *
     * @SWG\Post(path="/platformMgmt/v1/admin/setStatus",
     *   summary="设置管理员状态",
     *   description="请求该接口需要先登录并且有此权限",
     *   @SWG\Parameter(name="id",in="formData",type="number",required="true",
     *      description="管理员ID"
     *   ),
     *   @SWG\Response(response="返回json数组,包含状态码status,描述message，数据data，以及头部httpCode",
     *      description="状态码为 1 时成功；为 0 时失败并返回提示失败原因message；为 -1 时为未登录；为 -2 时为无此权限 "
     *  )
     * )
     */
    public function setStatus(){
        $id = input('post.id');

        if($id == 1){
            return show(config('code.error'),'超级管理员状态不可该改变');
        }
        $adminModel = model('admin');
        $admin = $adminModel->get($id);

        if(empty($admin)){
            return show(config('code.error'),'该管理员不存在');
        }

        if($admin->status == 1){
            $udata['status'] = 0;
        }else if($admin->status == 0){
            $udata['status'] = 1;
        }

        $udata['id'] = $id;
        $adminModel->edit($udata);

        return show(config('code.success'),'修改状态成功');
    }

}