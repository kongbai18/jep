<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/25 0025
 * Time: 15:30
 */

namespace app\platformmgmt\controller;

use app\platformmgmt\model\Furniture as FurnitureModel;


class Furniture extends Base
{
    public function index(){
        $Furniture = new FurnitureModel();

        $data = $Furniture->getFurnitureData();

        return show(config('code.success'),'获取家具信息成功',$data);
    }

    public function read($id){
        $FurnitureModel = new FurnitureModel();

        $furniture = $FurnitureModel->find($id);

        if(empty($furniture)){
            return show(config('code.error'),'该家具不存在','', 404);
        }

        try{
            $data = $FurnitureModel->getFurnitureDetail($id);
        }catch (\Exception $e){
            return show(config('code.error'),'获取家具信息失败');
        }

        return show(config('code.success'),'获取家具信息成功',$data);

    }

    public function save(){
        $data = input('post.');
        //validata

        $Furniture = new FurnitureModel();

        try{
            $result = $Furniture->addFurniture($data);
        }catch (\Exception $e){
            return show(config('code.error'),'添加失败');
        }

        if(!$result){
            return show(config('code.error'),'添加失败');
        }

        return show(config('code.success'),'添加家具成功');

    }

    public function update($id){
        $FurnitureModel = new FurnitureModel();
        $furniture = $FurnitureModel->find($id);

        if(empty($furniture)){
            return show(config('code.error'),'该家具不存在','', 404);
        }

        $data = input('put.');
        $data['id'] = $id;

        //validata

        try{
            $result = $FurnitureModel->editFurniture($data);
        }catch (\Exception $e){
            return show(config('code.error'),'修改失败');
        }

        if(!$result){
            return show(config('code.error'),'修改失败');
        }

        return show(config('code.success'),'修改家具成功');
    }


    public function delete($id){
        $FurnitureModel = new FurnitureModel();
        $furniture = $FurnitureModel->find($id);

        if(empty($furniture)){
            return show(config('code.error'),'该家具不存在','', 404);
        }

        $result = $FurnitureModel->remove($id);

        if($result){
            return show(config('code.success'),'删除成功');
        }

        return show(config('code.error'),'删除失败');
    }
}