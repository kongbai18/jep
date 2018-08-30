<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/26 0026
 * Time: 11:06
 */

namespace app\platformMgmt\controller;

use think\Controller;
use app\platformMgmt\model\Permission as PermissionModel;

class Permission extends Base
{
    /**
     *
     * @SWG\Get(path="/platformMgmt/permission",
     *   summary="获取权限列表信息",
     *   description="请求该接口需要先登录并且有此权限,不需要传参",
     *   @SWG\Response(response="返回json数组,包含状态码status,描述message，数据data，以及头部httpCode",
     *      description="状态码为 1 时成功返回数据data；为 0 时失败并返回提示失败原因message；为 -1 时为未登录；为 -2 时为无此权限 "
     *  )
     * )
     */
    public function index(){
        //实例化权限类
        $permissionModel = New PermissionModel();

        //权限树状数据
        $permissionList = $permissionModel->getList();

        $data =  [
            'permissionData' => $permissionList
        ];

        return show(config('code.success'), '获取权限列表成功', $data);
    }

    /**
     *
     * @SWG\Get(path="/platformMgmt/permission/{id}",
     *   summary="获取权限信息",
     *   description="请求该接口需要先登录并且有此权限。",
     *   @SWG\Parameter(name="id",in="path",type="number",required="true",
     *      description="权限ID"
     *   ),
     *   @SWG\Response(response="返回json数组,包含状态码status,描述message，数据data，以及头部httpCode",
     *      description="状态码为 1 时成功返回数据data；为 0 时失败并返回提示失败原因message；为 -1 时为未登录；为 -2 时为无此权限 "
     *  )
     * )
     */
    public function read($id){
        //实例化权限类
        $permissionModel = New PermissionModel();

        $permissionData = $permissionModel->get($id);

        if(empty($permissionData)){
            return show(config('code.error'),'该权限不存在','', 404);
        }

        $data =  [
            'permissionData' => $permissionData
        ];

        return show(config('code.success'), '获取权限信息成功', $data);

    }

    /**
     *
     * @SWG\Post(path="/platformMgmt/permission/getChild",
     *   summary="获取权限子集及本身",
     *   description="请求该接口需要先登录并且有此权限。",
     *   @SWG\Parameter(name="id",in="formData",type="number",required="true",
     *      description="权限ID"
     *   ),
     *   @SWG\Response(response="返回json数组,包含状态码status,描述message，数据data，以及头部httpCode",
     *      description="状态码为 1 时成功返回数据data；为 0 时失败并返回提示失败原因message；为 -1 时为未登录；为 -2 时为无此权限 "
     *  )
     * )
     */
    public function getChild(){
        $id = input('post.id');
        $permissionModel = model('permission');

        $permissionData = $permissionModel->get($id);

        if(empty($permissionData)){
            return show(config('code.error'),'该权限不存在');
        }

        $data = $permissionModel->getChild($id);

        return show(config('code.success'),'获取权限子集成功',$data);
    }

    /**
     *
     * @SWG\Post(path="/platformMgmt/permission",
     *   summary="添加权限",
     *   description="请求该接口需要先登录并且有此权限。",
     *   @SWG\Parameter(name="name",in="formData",type="string",required="true",
     *      description="权限名,不得超过30字符"
     *   ),
     *     @SWG\Parameter(name="parent_id",in="formData",type="number",required="true",
     *      description="上级权限ID"
     *   ),
     *   @SWG\Parameter(name="module_name",in="formData",type="string",
     *      description="模板名称"
     *   ),
     *   @SWG\Parameter(name="controller_name",in="formData",type="string",
     *      description="控制器名称"
     *   ),
     *     @SWG\Parameter(name="action_name",in="formData",type="string",
     *      description="方法名"
     *   ),
     *
     *   @SWG\Response(response="返回json数组,包含状态码status,描述message，数据data，以及头部httpCode",
     *      description="状态码为 1 时成功；为 0 时失败并返回提示失败原因message；为 -1 时为未登录；为 -2 时为无此权限 "
     *  )
     * )
     */
    public function save(){
        //实例化权限类
        $permissionModel = New PermissionModel();

        $data = input('post.');

        // validate
        $validate = validate('Permission');
        if(!$validate->check($data)) {
            return show(config('code.error'), $validate->getError());
        }


        //入库操作
        try {
            $id = $permissionModel->add($data);
        }catch (\Exception $e) {
            return show(config('code.error'),'新增失败','',500);
        }

        if($id) {
            return show(config('code.success'), '新增权限成功');
        } else {
            return show(config('code.error'), '新增失败','',500);
        }
    }


    /**
     *
     * @SWG\Get(path="/platformMgmt/permission/{id}/edit",
     *   summary="修改权限信息",
     *   description="请求该接口需要先登录并且有此权限。",
     *   @SWG\Parameter(name="id",in="path",type="string",required="true",
     *      description="权限Id"
     *   ),
     *   @SWG\Parameter(name="name",in="query",type="string",required="true",
     *      description="权限名,不得超过30字符"
     *   ),
     *     @SWG\Parameter(name="parent_id",in="query",type="number",required="true",
     *      description="上级ID"
     *   ),
     *   @SWG\Parameter(name="module_name",in="query",type="string",
     *      description="模板名称"
     *   ),
     *   @SWG\Parameter(name="controller_name",in="query",type="string",
     *      description="控制器名称"
     *   ),
     *     @SWG\Parameter(name="action_name",in="query",type="string",
     *      description="方法名"
     *   ),
     *
     *   @SWG\Response(response="返回json数组,包含状态码status,描述message，数据data，以及头部httpCode",
     *      description="状态码为 1 时成功；为 0 时失败并返回提示失败原因message；为 -1 时为未登录；为 -2 时为无此权限 "
     *  )
     * )
     */
    public function edit($id){
        //实例化权限类
        $permissionModel = New PermissionModel();
        $data = input('get.');
        $data['id'] = $id;

        // validate
        $validate = validate('Permission');
        if(!$validate->check($data)) {
            return show(config('code.error'), $validate->getError());
        }

        //入库操作
        try {
            $result = $permissionModel->edit($data);
        }catch (\Exception $e) {
            return show(config('code.error'), '更新失败','',500);
        }

        if($result !== false) {
            return show(config('code.success'), '更新权限成功');
        } else {
            return show(config('code.error'), '更新失败','',500);
        }
    }

    /**
     *
     * @SWG\Delete(path="/platformMgmt/permission/{id}",
     *   summary="删除权限",
     *   description="请求该接口需要先登录并且有此权限。",
     *   @SWG\Parameter(name="id",in="path",type="number",required="true",
     *      description="权限ID"
     *   ),
     *   @SWG\Response(response="返回json数组,包含状态码status,描述message，数据data，以及头部httpCode",
     *      description="状态码为 1 时成功；为 0 时失败并返回提示失败原因message；为 -1 时为未登录；为 -2 时为无此权限 "
     *  )
     * )
     */
    public function delete($id){

        //判断权限下是否存在角色
        $rolePermission = model('role_permission');

        $count=$rolePermission->field('count(*) as count ')->where(['per_id'=>['eq',$id]])->find()->toArray();


        if($count['count'] !== 0){
            return show(config('code.error'),'有角色包含此权限，不可删除');
        }


        $permissionModel = new PermissionModel();
        //判断是否存在子集权限
        try{
            $child=$permissionModel->field('count(*) as count ')->where(['parent_id'=>['eq',$id]])->find()->toArray();
        }catch (\Exception $e){
            return show(config('code.error'),'删除失败','',500);
        }

        if($child['count'] !== 0){
            return show(config('code.error'),'删除失败,有子集权限未删除');
        }

        //执行删除
        try{
            $permissionModel->where(['id'=>['eq',$id]])->delete();
        }catch(\Exception $e){
            return show(config('code.error'),'删除失败','',500);
        }
         return show(config('code.success'),'删除权限成功');

    }
}