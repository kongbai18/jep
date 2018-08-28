<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/27 0027
 * Time: 9:46
 */

namespace app\platformMgmt\model;

use app\common\model\Role as RoleModel;
use think\Db;
class Role extends RoleModel
{
    /**
     * 添加角色
     * 添加关联权限
     * @param $data 表单数据
     * @return mixed
     */
    public function addRole($data){
        //开启事务
        Db::startTrans();
        try {
            $id = $this->add($data);
        }catch (\Exception $e) {
            Db::rollback();
            halt($e->getMessage());
            return false;
        }

        if($id) {
            //权限关联表入库
            $rolePerModel = model('role_permission');
            foreach ($data['per_id'] as $k => $v){
                $result = $rolePerModel->insert(['role_id'=>$id,'per_id'=>$v]);
                if(!$result){
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
     * 更新角色数据及权限
     * @param $data 需要更新的数据
     * @return bool
     */
    public function editRole($data){

        //开启事务
        Db::startTrans();
        try {
            $result = $this->edit($data);
        }catch (\Exception $e) {
            Db::rollback();
            return false;
        }

        if($result !== false) {
            $rolePerModel = model('role_permission');
            //删除原关联权限
            try{
                $rolePerModel->where(['role_id'=>['eq',$data['id']]])->delete();
            }catch (\Exception $e){
                Db::rollback();
                return false;
            }
            //权限关联表入库
            foreach ($data['per_id'] as $k => $v){
                try{
                    $rolePerModel->insert(['role_id'=>$data['id'],'per_id'=>$v]);
                }catch (\Exception $e){
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
     * 删除角色及对应权限关联表
     * @param $id
     * @return bool
     */
    public function remove($id){
        Db::startTrans();
        try{
            $this->where(['id'=>['eq',$id]])->delete();
        }catch (\Exception $e){
            Db::rollback();
            halt($e->getMessage());
            return false;
        }

        //删除关联角色
        try{
            $rolePerModel = model('role_permission');
            $rolePerModel->where(['role_id'=>['eq',$id]])->delete();
        }catch (\Exception $e){
            Db::rollback();
            halt($e->getMessage());
            return false;
        }

        Db::commit();
        return true;

    }

    /**
     * 角色列表及对应管理员
     * @return mixed
     */
    public function roleList(){
        $list = $this->field('a.*,group_concat(c.username) as username')
            ->alias('a')
            ->join('admin_role b','a.id = b.role_id','left')
            ->join('admin c','c.id = b.admin_id','left')
            ->group('a.id')
            ->select()
            ->toArray();

        return $list;
    }

    /**
     * 获取单个角色信息及权限信息
     * @param $id
     * @return array
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getRolePer($id){

        $list = $this->field('a.*,b.per_id')
            ->alias('a')
            ->join('role_permission b','a.id = b.role_id','left')
            ->where(['a.id'=>['eq',$id]])
            ->find()
            ->toArray();
        return $list;
    }

}