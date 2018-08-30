<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/26 0026
 * Time: 10:49
 */

namespace app\platformmgmt\controller;

use think\Controller;
use app\platformmgmt\model\Permission as PermissionModel;
use app\platformmgmt\model\Role as RoleModel;
use app\platformmgmt\controlle\Base;

class Role extends Base
{
    /**
     *
     * @SWG\Get(path="/platformMgmt/role",
     *   summary="获取角色列表信息",
     *   description="请求该接口需要先登录并且有此权限,不需要传参",
     *   @SWG\Response(response="返回json数组,包含状态码status,描述message，数据data，以及头部httpCode",
     *      description="状态码为 1 时成功返回数据data；为 0 时失败并返回提示失败原因message；为 -1 时为未登录；为 -2 时为无此权限 "
     *  )
     * )
     */
    public function index(){
        //实例化类
        $roleModel = new RoleMOdel();

        //获取数据
        $roleList = $roleModel->roleList();

        $data = [
            'roleData' => $roleList
        ];

        return show(config('code.success'),'获取角色列表成功',$data);
    }

    /**
     *
     * @SWG\Get(path="/platformMgmt/role/{id}",
     *   summary="获取角色信息",
     *   description="请求该接口需要先登录并且有此权限。",
     *   @SWG\Parameter(name="id",in="path",type="number",required="true",
     *      description="角色ID"
     *   ),
     *   @SWG\Response(response="返回json数组,包含状态码status,描述message，数据data，以及头部httpCode",
     *      description="状态码为 1 时成功返回数据data；为 0 时失败并返回提示失败原因message；为 -1 时为未登录；为 -2 时为无此权限 "
     *  )
     * )
     */
    public function read($id){
        //实例化类
        $roleModel = new RoleMOdel();

        $role = $roleModel->get($id);

        if(empty($role)){
            return show(config('code.error'),'该角色不存在','', 404);
        }

        //获取数据
        $roleData = $roleModel->getRolePer($id);

        $data = [
            'roleData' => $roleData
        ];

        return show(config('code.success'),'获取角色数据成功',$data);
    }

    /**
     *
     * @SWG\Post(path="/platformMgmt/role",
     *   summary="添加角色",
     *   description="请求该接口需要先登录并且有此权限。",
     *   @SWG\Parameter(name="name",in="formData",type="string",required="true",
     *      description="角色名，不得重复"
     *   ),
     *   @SWG\Parameter(name="content",in="formData",type="string",
     *      description="对角色的描述"
     *   ),
     *   @SWG\Parameter(name="per_id[]",in="formData",type="number",required="true",
     *      description="对应权限id，一维数组"
     *   ),
     *
     *   @SWG\Response(response="返回json数组,包含状态码status,描述message，数据data，以及头部httpCode",
     *      description="状态码为 1 时成功；为 0 时失败并返回提示失败原因message；为 -1 时为未登录；为 -2 时为无此权限 "
     *  )
     * )
     */
    public function save(){
        $data = input('post.');

        // validate
        $validate = validate('Role');

        if(!$validate->check($data)) {
            return show(config('code.error'), $validate->getError());
        }

        //入库操作
        $roleModel = new RoleMOdel();
         if($roleModel->addRole($data)){
             return show(config('code.success'), '新增角色成功','',201);
         }else{
             return show(config('code.error'), '新增失败');
         }
    }

    /**
     *
     * @SWG\Get(path="/platformMgmt/role/{id}/edit",
     *   summary="修改角色信息",
     *   description="请求该接口需要先登录并且有此权限。",
     *   @SWG\Parameter(name="id",in="path",type="string",required="true",
     *      description="管理员Id"
     *   ),
     *   @SWG\Parameter(name="name",in="query",type="string",required="true",
     *      description="角色名，不得重复"
     *   ),
     *   @SWG\Parameter(name="content",in="query",type="string",
     *      description="对角色的描述"
     *   ),
     *   @SWG\Parameter(name="per_id[]",in="query",type="number",required="true",
     *      description="对应权限id，一维数组"
     *   ),
     *
     *   @SWG\Response(response="返回json数组,包含状态码status,描述message，数据data，以及头部httpCode",
     *      description="状态码为 1 时成功；为 0 时失败并返回提示失败原因message；为 -1 时为未登录；为 -2 时为无此权限 "
     *  )
     * )
     */
    public function edit($id){
        if($id == 1){
            return show(0,'超级管理员不可修改');
        }

        $data = input('get.');
        $data['id'] = $id;

        // validate
        $validate = validate('Role');

        if(!$validate->check($data)) {
            return show(config('code.error'), $validate->getError());
        }

        //入库操作
        $roleModel = new RoleModel();
        if($roleModel->editRole($data)){
            return show(config('code.success'),'修改成功');
        }else{
            return show(config('code.error'),'修改是失败','',500);
        }
    }

    /**
     *
     * @SWG\Delete(path="/platformMgmt/role/{id}",
     *   summary="删除角色",
     *   description="请求该接口需要先登录并且有此权限。",
     *   @SWG\Parameter(name="id",in="path",type="number",required="true",
     *      description="角色ID"
     *   ),
     *   @SWG\Response(response="返回json数组,包含状态码status,描述message，数据data，以及头部httpCode",
     *      description="状态码为 1 时成功；为 0 时失败并返回提示失败原因message；为 -1 时为未登录；为 -2 时为无此权限 "
     *  )
     * )
     */
    public function delete($id){

        if($id == 1){
            return show(0,'超级管理员不可删除');
        }

        //判断角色下是否存在管理员
        $adminRoleModel = model('admin_role');

        $count=$adminRoleModel->field('count(*) as count ')->where(['role_id'=>['eq',$id]])->find()->toArray();


        if($count['count'] !== 0){
            return show(config('code.error'),'该角色下存在管理员，不可删除');
        }

        $roleModel = new RoleModel();
        if($roleModel->remove($id)){
            return show(config('code.success'),'删除角色成功');
        }
        return show(config('code.error'),'删除失败','',500);
    }
}