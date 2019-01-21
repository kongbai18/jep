<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/7 0007
 * Time: 13:12
 */

namespace app\platformmgmt\controller;

use app\platformmgmt\model\FurnitureAttr as FurnitureAttrModel;


class Furnitureattr extends Base
{
    public function read($id){
        $FurnitureAttrModel = new FurnitureAttrModel();
        $data = $FurnitureAttrModel->getlist($id);

        if($data !== false){
            return show(config('code.success'),'获取信息成功',$data);
        }

        return show(config('code.error'),'获取信息失败');
    }
    public function save(){
        $data = input('post.');

        $FurnitureAttrModel = new FurnitureAttrModel();
        $result = $FurnitureAttrModel->addFurnitureAttr($data['data'],$data['id']);

        if($result){
            return show(config('code.success'),'修改家具模型关系成功');
        }

        return show(config('code.error'),'修改家具模型关系失败');
    }

    public function delete($id){
        try{
            $FurnitureAttrModel = new FurnitureAttrModel();
            $FurnitureAttrModel->where('fur_id','eq',$id)->delete();
        }catch (\Exception $e){
            return show(config('code.error'),'删除失败');
        }

        return show(config('code.success'),'删除成功');
    }

}