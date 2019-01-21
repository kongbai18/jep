<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/25 0025
 * Time: 15:31
 */

namespace app\platformmgmt\model;

use think\Db;
use app\common\model\Furniture as FurnitureModel;

class Furniture extends FurnitureModel
{

    public function addFurniture($data){
        // 开启事务
        Db::startTrans();
        try {
            // 添加家具基本信息
            $furnitureId = $this->add($data);
            // 添加扩展属性
            $this->addFurnitureAttr($data,$furnitureId);
            Db::commit();
            return true;
        } catch (\Exception $e) {
            Db::rollback();
        }
        return false;
    }


    public function editFurniture($data){
        // 开启事务
        Db::startTrans();
        try {
            // 修改家具
            $this->edit($data);
            // 需改家具扩展属性
            $this->addFurnitureAttr($data,$data['id'],true);
            Db::commit();
            return true;
        } catch (\Exception $e) {
            var_dump($e->getMessage());
            Db::rollback();
        }
        return false;
    }

    /**
     * 添加扩展属性
     * @param $data
     * @param $isUpdate
     * @throws \Exception
     */
    private function addFurnitureAttr($data,$furnitureId,$isUpdate = false)
    {
        // 更新模式: 先删除所有规格
        $AttrModel = new Attr();
        $isUpdate && $AttrModel->removeAll($furnitureId);
        // 添加规格数据
        $AttrModel->addAttr($data['attr'],$furnitureId);
    }

    public function remove($id){
        $attrModel =new Attr();
        // 开启事务
        Db::startTrans();
        try {
            // 删除家具
            $this->where('id','eq',$id)->delete();
            // 删除扩展属性
            $attrModel->removeAll($id);
            Db::commit();
            return true;
        } catch (\Exception $e) {
            var_dump($e->getMessage());
            Db::rollback();
        }
        return false;
    }

}