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

class Role extends Controller
{
    /*
     * 角色列表
     */
    public function index(){
        return $this->fetch();
    }

    /*
     * 添加角色
     */
    public function add(){
        //实例化权限类
        $permissionModel = New PermissionModel();

        if(request()->isPost()) {
            $data = input('post.');
halt($data);
            // validate
            $validate = validate('Permission');
            if(!$validate->check($data)) {
                return $this->result('', 0, $validate->getError());
            }

            //入库操作
            try {
                $id = $permissionModel->add($data);
            }catch (\Exception $e) {
                return $this->result('', 0, '新增失败');
            }

            if($id) {
                return $this->result(['jump_url' => url('permission/index')], 1, '新增权限成功');
            } else {
                return $this->result('', 0, '新增失败');
            }
        }else{
            //权限树状数据
            $permissionData = $permissionModel->_getThree();

            return $this->fetch('', [
                'permissionData' => $permissionData
            ]);
        }
    }
}