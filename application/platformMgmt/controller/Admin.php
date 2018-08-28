<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/26 0026
 * Time: 9:57
 */

namespace app\platformMgmt\controller;

use think\Controller;
use app\platformMgmt\model\Role as RoleModel;
use app\platformMgmt\model\Admin as AdminModel;
use app\common\lib\IAuth;

class Admin extends Controller
{
    /**
     * 管理员列表
     */
    public function index(){
        $adminModel = new AdminModel();

        try{
            $adminData = $adminModel->adminList();
        }catch (\Exception $e) {
            return show(0, '获取管理员列表失败', '', 500);
        }

        $data = [
            'adminData' => $adminData
        ];

        return show(0,'获取管理员列表成功',$data);
    }

    /**
     * 添加管理员
     * @return mixed|void
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function save(){
        $data = input('post.');

        // validate
        $validate = validate('Admin');
        if(!$validate->check($data)) {
            return show(0, $validate->getError());
        }

        //密码加密
        $data['password'] = IAuth::setPassword($data['password']);

        //入库操作
        $adminModel = new AdminModel();
        if($adminModel->addAdmin($data)){
            return show( 1, '新增管理员成功');
        }else{
            return show( 0, '新增失败','',500);
        }
    }


    /**
     * 管理员信息及角色修改
     * @param $id 传入管理员id
     * @return mixed|void
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function edit($id){
        if($id == 1){
            return show(0,'初始用户数据不可修改');
        }
        $data = input('get.');
        $data['id'] = $id;

        // validate
        $validate = validate('Admin');
        if(!$validate->scene('edit')->check($data)) {
            return show(0, $validate->getError());
        }



        //密码加密
        if(array_key_exists("password",$data)){
            $data['password'] = IAuth::setPassword($data['password']);
        }


        //入库操作
        $adminModel = new AdminModel();
        if($adminModel->editAdmin($data)){
            return show( 1, '修改管理员成功');
        }else{
            return show(0, '修改失败','',500);
        }

    }

    /**
     * 删除管理员
     * @param $id
     */
    public function delete($id){
        if($id == 1){
            return show(0,'初始管理员不可删除');
        }
        $adminModel = new AdminModel();

        if($adminModel->remove($id)){
            return show(1,'删除管理员成功');
        }
        return show(0,'删除失败'.'',500);
    }
}