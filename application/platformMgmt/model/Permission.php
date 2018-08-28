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

}