<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/27 0027
 * Time: 15:02
 */

namespace app\platformMgmt\model;

use app\common\model\Admin as AdminModel;
use think\Db;
class Admin extends AdminModel
{
    /**
     * 隐藏字段
     * @var array
     */
    protected $hidden = [
        'password'
    ];

    /**
     * 添加管理员
     * 添加对应角色
     * @param $data
     * @return bool
     */
    public function addAdmin($data){
        //开启事务
        Db::startTrans();
        try {
            $id = $this->add($data);
        }catch (\Exception $e) {
            Db::rollback();
            return false;
        }

        if($id) {
            //权限关联表入库
            $adminRoleModel = model('admin_role');
            foreach ($data['role_id'] as $k => $v){
                $result = $adminRoleModel->insert(['role_id'=>$v,'admin_id'=>$id]);
                if(!$result){
                    Db::rollback();
                    return false;
                }
            }
            Db::commit();
            return true;
        } else {
            return false;
        }
    }


    /**
     * 修改管理员信息
     * @param $data
     * @return bool
     */
    public function editAdmin($data){
        //开启事务
        Db::startTrans();
        try {
            $result = $this->edit($data);
        }catch (\Exception $e) {
            Db::rollback();
            halt($e->getMessage());
            return false;
        }

        if($result !== false) {
            $adminRoleModel = model('admin_role');
            //删除原权限
            try{
                $adminRoleModel->where(['admin_id'=>['eq',$data['id']]])->delete();
            }catch (\Exception $e){
                Db::rollback();
                halt($e->getMessage());
                return false;
            }
            //权限关联表入库

            foreach ($data['role_id'] as $k => $v){
                try{
                    $adminRoleModel->insert(['role_id'=>$v,'admin_id'=>$data['id']]);
                }catch (\Exception $e){
                    Db::rollback();
                    halt($e->getMessage());
                    return false;
                }
            }
            Db::commit();
            return true;
        } else {
            return false;
        }
    }

    /**
     * 删除管理员及对应角色关联表
     * @param $id
     * @return bool
     */
    public function remove($id){
        Db::startTrans();
        try{
            $this->where(['id'=>['eq',$id]])->delete($id);
        }catch (\Exception $e){
            Db::rollback();
            return false;
        }

        //删除关联角色
        try{
            $adminRoleModel = model('admin_role');
            $adminRoleModel->where(['admin_id'=>['eq',$id]])->delete();
        }catch (\Exception $e){
            Db::rollback();
            return false;
        }

        Db::commit();
        return true;

    }
    /**
     * 获取管理员信息及所属角色
     * @param $id 管理员ID
     * @return array
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getAdminRole($id){
        $list = $this->field('a.*,b.role_id')
            ->alias('a')
            ->join('admin_role b','a.id = b.admin_id','left')
            ->where(['a.id'=>['eq',$id]])
            ->find()
            ->toArray();
        return $list;
    }

    /**
     * 获取所有角色列表
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function adminList(){
        $list = $this->field('a.*,group_concat(c.name) as role')
            ->alias('a')
            ->join('admin_role b','a.id = b.admin_id','left')
            ->join('role c','c.id = b.role_id','left')
            ->group('a.id')
            ->select()
            ->toArray();

        return $list;
    }
}