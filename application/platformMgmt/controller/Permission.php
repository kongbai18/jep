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

class Permission extends Controller
{
    /*
     * 权限列表
     */
    public function index(){
        //实例化权限类
        $permissionModel = New PermissionModel();

        //权限树状数据
        try{
            $permissionData = $permissionModel->getList();
        }catch (\Exception $e){
            return show(0, '获取权限列表失败', '', 500);
        }

        $data =  [
            'permissionData' => $permissionData
        ];

        return show(0, '获取管理员列表成功', $data);
    }

    /*
     * 添加权限
     */
    public function save(){
        //实例化权限类
        $permissionModel = New PermissionModel();

        $data = input('post.');

        // validate
        $validate = validate('Permission');
        if(!$validate->check($data)) {
            return show(0, $validate->getError());
        }


        //入库操作
        try {
            $id = $permissionModel->add($data);
        }catch (\Exception $e) {
            return show(0,'新增失败','',500);
        }

        if($id) {
            return show(1, '新增权限成功');
        } else {
            return show(0, '新增失败','',500);
        }
    }


    public function edit($id){
        //实例化权限类
        $permissionModel = New PermissionModel();
        $data = input('get.');
        $data['id'] = $id;

        // validate
        $validate = validate('Permission');
        if(!$validate->check($data)) {
            return show(0, $validate->getError());
        }

        //入库操作
        try {
            $result = $permissionModel->edit($data);
        }catch (\Exception $e) {
            return show(0, '更新失败','',500);
        }

        if($result !== false) {
            return show(1, '更新权限成功');
        } else {
            return show(0, '更新失败','',500);
        }
    }

    /**
     * 删除权限
     * @param $id
     */
    public function delete($id){

        //判断权限下是否存在角色
        $rolePermission = model('role_permission');
        try{
            $count=$rolePermission->field('count(*) as count ')->where(['per_id'=>['eq',$id]])->find()->toArray();
        }catch (\Exception $e){
            return show(0,'删除失败','',500);
        }

        if($count['count'] !== 0){
            return show(0,'有角色包含此权限，不可删除');
        }


        $permissionModel = new PermissionModel();
        //判断是否存在子集权限
        try{
            $child=$permissionModel->field('count(*) as count ')->where(['parent_id'=>['eq',$id]])->find()->toArray();
        }catch (\Exception $e){
            return show(0,'删除失败','',500);
        }

        if($child['count'] !== 0){
            return show(0,'删除失败,有子集权限未删除');
        }

        //执行删除
        try{
            $permissionModel->where(['id'=>['eq',$id]])->delete();
        }catch(\Exception $e){
            return show(0,'删除失败','',500);
        }
         return show(1,'删除权限成功');

    }
}