<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/26 0026
 * Time: 10:51
 */

namespace app\platformmgmt\model;

use app\common\model\Attr as AttrModel;

class Attr extends AttrModel
{
    public function addAttr($data,$furnitureId){
        $attrValModel = model('attr_value');
        foreach ($data as $v){
            $udata = [
                'furniture_id' => $furnitureId,
                'attr_name' => $v['attr_name'],
                'type_id' => $v['type_id'],
            ];
            $attrId = $this->insertGetId($udata);

            foreach ($v['value'] as $item){
                $udata = [
                    'attr_id' => $attrId,
                    'attr_value' => $item['attr_value'],
                ];
                $attrValModel->allowField(true)->insert($udata);
            }
        }
    }

    public function removeAll($furnitureId){
        $attr = $this->field('id')->where('furniture_id','eq',$furnitureId)->select();
        $attrValModel = new AttrValue();
        foreach ($attr as $v){
            $attrValModel->where('attr_id','eq',$v['id'])->delete();
        }
        $this->where('furniture_id','eq',$furnitureId)->delete();
    }
}