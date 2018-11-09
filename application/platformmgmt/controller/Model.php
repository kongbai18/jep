<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/27 0027
 * Time: 13:14
 */

namespace app\platformmgmt\controller;

use app\platformmgmt\model\Model as ModelModel

class Model extends Base
{
    public function index(){
        $modelModle = new ModelModel();

        try{
            $data = $modelModle->select()->toArray();
        }catch (\Exception $e){
            return show(config('code.error'),'获取模型列表失败');
        }

        return show(config('code.success'),'获取模型列表成功',$data);
    }

    public function read($id){
        $model = model('model')->find($id);

        if(empty($model)){
            return show(config('code.error'),'该模型不存在','', 404);
        }

        $modelModle = new ModelModel();

        if($data = $modelModle->getModelDetail($id)){
            return show(config('code.success'),'获取模型详细信息成功',$data);
        }

        return show(config('code.error'),'获取模型详细信息失败');
    }

    public function save(){
        $data = input('post.');

        //validata
        $validate = validate('Model');
        if(!$validate->check($data)) {
            return show(config('code.error'), $validate->getError());
        }

        $modelModle = new ModelModel();

        try{
            $result = $modelModle->addModel($data);
        }catch (\Exception $e){
            return show(config('code.error'),'添加失败');
        }

        if(!$result){
            return show(config('code.error'),'添加失败');
        }

        return show(config('code.success'),'添加成功');
    }


    public function edit($id){
        $model = model('model')->find($id);

        if(empty($model)){
            return show(config('code.error'),'该模型不存在','', 404);
        }

        $data = input('get.');
        $data['id'] = $id;

        //validata
        $validate = validate('Model');
        if(!$validate->check($data)) {
            return show(config('code.error'), $validate->getError());
        }

        $modelModle = new ModelModel();

        try{
            $result = $modelModle->editModel($data);
        }catch (\Exception $e){
            return show(config('code.error'),'添加失败');
        }

        if(!$result){
            return show(config('code.error'),'添加失败');
        }

        return show(config('code.success'),'添加成功');
    }

    public function delete($id){
        $modelModle = new ModelModel();

        try{
            $result = $modelModle->remove($id);
        }catch (\Exception $e){
            return show(config('code.error'),'删除失败');
        }

        if(!$result){
            return show(config('code.error'),'删除失败');
        }

        return show(config('code.success'),'删除成功');
    }
}