<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/25 0025
 * Time: 15:31
 */

namespace app\platformmgmt\model;

use thinl\Db;
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
    private function addFurnitureAttr(&$data,$furnitureId,$isUpdate = false)
    {
        // 更新模式: 先删除所有规格
        $furnitureAttrModel = model('furniture_attr');
        $isUpdate && $furnitureAttrModel->removeAll($furnitureId);
        // 添加规格数据
        if ($data['attr_type'] === '10') {
            // 单规格
            $furnitureAttrModel->allowField(true)->save($data['attr']);
        } else if ($data['spec_type'] === '20') {
            // 添加商品与规格关系记录
            $furnitureAttrModel->addFurnitureAttrRel($furnitureId, $data['attr_many']['attr_attr']);
            // 添加商品sku
            $furnitureAttrModel->addSkuList($furnitureId, $data['attr_many']['attr_list']);
        }
    }

    public function remove($fur_id){
        $FurnitureExt = model('fur_ext_attr');
        // 开启事务
        Db::startTrans();
        try {
            // 删除家具
            $this->delete($fur_id);
            // 删除扩展属性
            $FurnitureExt->removeAll($fur_id);
            Db::commit();
            return true;
        } catch (\Exception $e) {
            Db::rollback();
        }
        return false;
    }
}