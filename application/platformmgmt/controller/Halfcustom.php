<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/4 0004
 * Time: 10:11
 */

namespace app\platformmgmt\controller;

use app\platformmgmt\model\HalfCustom as HalfCustomModel;


class Halfcustom extends Base
{
    public function index(){
        $HalfCustom = new HalfCustomModel();

        $result = $HalfCustom->getHalfCustomList();

        if($result){
            return show(config('code.success'),'获取半定制列表成功',$result);
        }

        return show(config('code.error'),'获取半定制列表失败');
    }

    public function read($id){
        $HalfCustom = new HalfCustomModel();

        $result = $HalfCustom->getHalfCustomDetail($id);

        if($result){
            return show(config('code.success'),'获取半定制信息成功',$result);
        }

        return show(config('code.error'),'获取半定制信息失败');
    }

    public function save(){
        $data = input('post.');

        $HalfCustom = new HalfCustomModel();

        $result = $HalfCustom->addHalfCustom($data);

        if($result){
            return show(config('code.success'),'添加半定制成功');
        }

        return show(config('code.error'),'添加半定制失败');
    }

    public function update($id){
        $data = input('put.');
        $data['id'] = $id;

        $HalfCustom = new HalfCustomModel();

        $result = $HalfCustom->editHalfCustom($data);

        if($result){
            return show(config('code.success'),'修改半定制成功');
        }

        return show(config('code.error'),'修改半定制失败');
    }

    public function delete($id){
        $HalfCustom = new HalfCustomModel();
        $result = $HalfCustom->remove($id);

        if($result){
            return show(config('code.success'),'删除半定制成功');
        }

        return show(config('code.error'),'删除半定制失败');
    }
}