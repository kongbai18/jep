<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/26 0026
 * Time: 10:49
 */

namespace app\platformMgmt\controller;

use think\Controller;
use app\platformMgmt\model\Permission as PermissionModel;
use app\platformMgmt\model\Role as RoleModel;

class Role extends Controller
{
    /**
     * 角色列表
     */
    public function index(){
        //实例化类
        $roleModel = new RoleMOdel();

        //获取数据
        try{
            $roleData = $roleModel->roleList();
        }catch (\Exception $e){
            return show(1,'获取角色数据列表失败','',500);
        }

        $data = [
            'roleData' => $roleData
        ];

        return show(1,'获取角色数据列表成功',$data);
    }

    /**
     * 添加角色
     */
    public function save(){
        $data = input('post.');

        // validate
        $validate = validate('Role');

        if(!$validate->check($data)) {
            return show(0, $validate->getError());
        }

        //入库操作
        $roleModel = new RoleMOdel();
         if($roleModel->addRole($data)){
             return show(1, '新增角色成功','',201);
         }else{
             return show(0, '新增失败');
         }
    }

    /**
     * 修改角色
     * @param $id
     * @return mixed|void
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
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
            return show(0, $validate->getError());
        }

        //入库操作
        $roleModel = new RoleModel();
        if($roleModel->editRole($data)){
            return show(1,'修改成功');
        }else{
            return show(0,'修改是失败','',500);
        }
    }

    /**
     * 删除角色
     * @param $id
     */
    public function delete($id){

        if($id == 1){
            return show(0,'超级管理员不可删除');
        }

        //判断角色下是否存在管理员
        $adminRoleModel = model('admin_role');
        try{
            $count=$adminRoleModel->field('count(*) as count ')->where(['role_id'=>['eq',$id]])->find()->toArray();
        }catch (\Exception $e){
            return show(0,'删除失败','',500);
        }

        if($count['count'] !== 0){
            return show(0,'该角色下存在管理员，不可删除');
        }

        $roleModel = new RoleModel();
        if($roleModel->remove($id)){
            return show(1,'删除角色成功');
        }
        return show(0,'删除失败','',500);
    }
}