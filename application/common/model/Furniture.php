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
            'attrData' => $furnitureAttr,
        ];

        return $data;
    }


    /**
     * 获取规格信息
     * @return array
     */
    private function getManyAttrData($fur_id){
        $attrModel = model('attr');
        $attrData = $attrModel->field('id,attr_name,type_id')->where('furniture_id','eq',$fur_id)->select();

        $attrValModel = model('attr_value');
        foreach ($attrData as &$v){
            $val = $attrValModel->field('id,attr_value')->where('attr_id','eq',$v['id'])->select();
            $v['value'] = $val;
        }

        return $attrData;
    }
}