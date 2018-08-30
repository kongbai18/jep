<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/26 0026
 * Time: 13:17
 */

namespace app\platformMgmt\model;

use app\common\model\Permission as PermissionModel;

class Permission extends PermissionModel
{
    /**
     * 查询权限数据
     * @param string $search 查询条件
     * @return array|false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getList($search = ''){
        // 筛选条件
        $filter = [];
        !empty($search) && $filter['name'] = ['like', '%' . trim($search) . '%'];

        // 排序规则
        $sort = ['sort_id' => 'asc'];

        // 执行查询
        $list = $this
            ->where($filter)
            ->order($sort)
            ->select()
            ->toArray();

        //树状化
        $list = $this->_getTree($list);
        return $list;
    }

    /**
     * 对权限数据进行树状化
     * @param $data 权限数据
     * @param int $parentId 第一级父类ID
     * @param int $level 第一级级别
     * @return array
     */
    private function _getTree($data,$parentId=0,$level=0){
        static $ret =array();
        foreach($data as $k => $v){
            if($v['parent_id']==$parentId){
                $v['level'] = $level;
                $ret[] = $v;
                //找子分类
                $this->_getTree($data,$v['id'],$level+1);
            }
        }
        return $ret;
    }

    /**
     * 获取权限本身及全部子集数组集合
     * @param $id
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getChild($id){
        //获得所有分类数据
        $data = $this->select()->toArray();
        $child = $this->_getChild($id,$data,true);
        $child[] = $id;
        return $child;
    }
    private function _getChild($id,$data,$isClear = FALSE){
        static $child = array();
        if($isClear){
            $child = [];
        }
        //循环从数据中找出子类
        foreach($data as $k => $v){
            if($v['parent_id']==$id){
                $child[] = $v['id'];
                $this->_getChild($v['id'],$data,FALSE);
            }
        }
        return $child;
    }
    /**
     * 获取权限三级分类
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function _getThree(){
        $data = $this->order(['sort_id' => 'asc'])->select()->toArray();

        $ret = array();
        foreach ($data as $k => &$v) {
            if($v['parent_id'] == 0){
                $v['child'] = [];
                foreach ($data as $k1 => &$v1) {
                    if ($v1['parent_id'] == $v['id']) {
                        $v1['child'] = [];
                        foreach ($data as $k2 => $v2){
                            if($v2['parent_id'] === $v1['id'] ){
                                $v1['child'][] = $v2;
                            }
                        }
                        $v['child'][] = $v1;
                    }
                }
                $ret[] = $v;
            }
        }
        return $ret;
    }


    /**
     * 获取管理员前两级权限列表
     * @param $id
     * @return array
     */
    public function getBtns($adminId){

        $roleData = model('admin_role')->field('group_concat(role_id) as role_id')->where(['admin_id'=>['eq',$adminId]])->group('admin_id')->find()->toArray();
        $roleData = explode(',',$roleData['role_id']);

        if(in_array('1',$roleData)){
            $permissionData = $this->field('id,name,parent_id')->order('sort_id asc')->select()->toArray();
        }else{
            //取出当前角色权限
            $adminRoleModel = model('admin_role');
            $permissionData = $adminRoleModel->alias('a')
                ->field('c.id,c.name,c.parent_id')
                ->join('role_permission b','a.role_id = b.role_id','left')
                ->join('permission c','b.per_id = c.id','left')
                ->where(['a.admin_id'=>['eq',$adminId]])
                ->order('c.sort_id asc')
                ->group('c.id')
                ->select()
                ->toArray();
        }

        $btns =  array();
        foreach($permissionData as $k => $v){
            if($v['parent_id'] === 0){
                //找子集
                foreach($permissionData as $k1 => $v1){
                    if($v1['parent_id'] == $v['id']){
                        $v['children'][] = $v1;
                    }
                }
                $btns[] = $v;
            }
        }
        return $btns;
    }


    /**
     * 检测管理员访问权限
     * @return bool
     */
    public function chkPri(){
        //获取要访问的模型，控制器，方法
        $admin = session(config('admin.session_user'));

        $roleData = model('admin_role')->field('group_concat(role_id) as role_id')->where(['admin_id'=>['eq',$admin['id']]])->group('admin_id')->find()->toArray();
        $roleData = explode(',',$roleData['role_id']);

        //如果是超级管理员直接pass
        if(in_array('1',$roleData)){
            return true;
        }

        //如果时修改个人信息密码，直接通过
        if(strtolower(request()->action()) == 'setpassword'){
            return true;
        }

        $adminRoleModel = model('admin_role');

        $has = $adminRoleModel->alias('a')
            ->join('role_permission b','a.role_id = b.role_id','left')
            ->join('permission c','b.per_id = c.id','left')
            ->where([
                'a.admin_id'=>['eq',$admin['id']],
                'c.module_name'=>['eq',strtolower(request()->module())],
                'c.controller_name'=>['eq',strtolower(request()->controller())],
                'c.action_name'=>['eq',strtolower(request()->action())],
            ])
           ->count();
        if($has > 0){
            return true;
        }else{
            return false;
        }
    }
}