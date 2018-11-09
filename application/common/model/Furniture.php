<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/25 0025
 * Time: 15:31
 */

namespace app\common\model;


class Furniture extends Base
{

    /**
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getFurnitureData(){
        $list = $this->select()->toArray();

        return $list;
    }

    /**
     * @param $fur_id
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getFurnitureDetail($fur_id){
        //获取家具基本信息
        $furniture = $this->find($fur_id);

        //获取家具SKU
        $furnitureAttr = $this->getManyAttrData($fur_id);

        $data = [
            'furniture' => $furniture,
            'furnitureAttrData' => $furnitureAttr['furnitureAttrData'],
            'furnitureAttrRelData' => $furnitureAttr['furnitureAttrRelData'],
        ];

        return $data;
    }


    /**
     * 获取规格信息
     * @param \think\Collection $spec_rel
     * @param \think\Collection $skuData
     * @return array
     */
    private function getManyAttrData($fur_id){
        $furnitureAttrModel = model('furniture_attr');
        $furnitureAttrData = $furnitureAttrModel->where(['fur_id'=>['eq',$fur_id]])->select()->toArray();

        $furnitureAttrRelModel = model('furniture_attr_rel');
        $furnitureAttrRelData = $furnitureAttrRelModel->field('a.attr_id,a.attr_value_id,b.attr_name,b.type_id,c.attr_value')
            ->alias('a')
            ->join('attr b','a.attr_id = b.id','left')
            ->join('attr_value c','a.attr_value_id = c.id','left')
            ->where(['fur_id'=>['eq',$fur_id]])->select()->toArray();

        $goodsSpec = [
            'furnitureAttrData' => $furnitureAttrData,
            'furnitureAttrRelData' => $furnitureAttrRelData,
        ];

        return $goodsSpec;
    }
}