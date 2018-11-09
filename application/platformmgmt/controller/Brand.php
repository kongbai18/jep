<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/24 0024
 * Time: 16:03
 */

namespace app\platformmgmt\controller;

use app\platformmgmt\model\Brand as BrandModel;

class Brand extends Base
{
    public function index(){
        try{
            $data = model('brand')->select();
        }catch (\Exception $e){
            return show(config('code.error'),'获取品牌列表失败!',500);
        }

        return show(config('code.success'),'获取品牌列表成功',$data);
    }

    public function read($id){
        try{
            $data = model('brand')->find($id);
        }catch (\Exception $e){
            return show(config('code.error'),$e->getMessage());
        }

        if (!$data){
            return show(config('code.error'),'该品牌不存在!');
        }

        return show(config('code.success'),'获取品牌信息成功!',$data);
    }

    public function save(){
        $data = input('post.');

        //validate

        try{
            $model = new BrandModel();
            $model->addBrand($data);
        }catch (\Exception $e){
            return show(config('code.error'),$e->getMessage());
        }

        return show(config('code.success'),'添加品牌成功!');
    }

    public function update($id){
        $data = input('put.');

        $brand = model('brand')->find($id);

        if(!$brand){
            return show(config('code.error'),'修改品牌不存在！');
        }

        $data['id'] = $id;

        try{
            $model = new BrandModel();
            $model->editBrand($data);
        }catch (\Exception $e){
            return show(config('code.error'),$e->getMessage());
        }

        return show(config('code.success'),'修改品牌信息成功！');
    }
}