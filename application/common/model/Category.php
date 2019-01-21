<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/3 0003
 * Time: 9:08
 */

namespace app\common\model;

use app\common\model\Base;

class Category extends Base
{
    /**
     * 获取树状分类数据
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
     * 对分类数据进行树状化
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
     * 获取分类本身及全部子集数组集合
     * @param $id
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getChild($id){
        //获得所有分类数据
        $data = $this->getList();
        $child = $this->_getChild($id,$data,true);
        $child[] = $id;
        $rdata = $data;
        foreach ($data as $k => $v){
            foreach ($child as $item){
                if($v['id'] == $item){
                    unset($rdata[$k]);
                }
            }
        }
        return $rdata;
    }

    public function getChildSelf($id){
        //获得所有分类数据
        $data = $this->getList();
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
}