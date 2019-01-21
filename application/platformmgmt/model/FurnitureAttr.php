<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/26 0026
 * Time: 11:07
 */

namespace app\platformmgmt\model;

use app\common\model\FurnitureAttr as FurnitureAttrModel;
class FurnitureAttr extends FurnitureAttrModel
{
    protected  $autoWriteTimestamp = true;


    public function addFurnitureAttr($data,$furId){
        try{
            foreach ($data as $v){
                $udata = [
                    'fur_id' => $furId,
                    'model_id' => $v['model_id'],
                    'attr_sku_id' => $v['attr_sku_id'],
                    'image_url' => $v['image_url'],
                ];

                if($v['id']){
                    $udata['id']= $v['id'];
                    $this->update($udata);
                }else{
                    $this->insert($udata);
                }
            }
        }catch (\Exception $e){
            var_dump($e->getMessage());
            return false;
        }

        return true;
    }
}