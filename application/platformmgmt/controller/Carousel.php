<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/16 0016
 * Time: 9:28
 */

namespace app\platformmgmt\controller;

use app\platformmgmt\model\Carousel as CarouselModel;

class Carousel extends Base
{
    public function index(){
        $CarouselModel = new CarouselModel();
        try{
            $carouselData = $CarouselModel->order('sort_id asc')->select();
        }catch (\Exception $e){
            return show(config('code.error'),'获取轮播图失败');
        }

        return show(config('code.success'),'获取轮播图成功',$carouselData);
    }

    public function read($id){
        $CarouselModel = new CarouselModel();
        try{
            $carouselData = $CarouselModel->find($id);
        }catch (\Exception $e){
            return show(config('code.error'),'获取轮播图失败');
        }

        return show(config('code.success'),'获取轮播图成功',$carouselData);
    }

    public function save(){
        $data = input('post.');

        $CarouselModel = new CarouselModel();

        $result = $CarouselModel->addCarousel($data);

        if($result){
            return show(config('code.success'),'添加轮播图成功');
        }

        return show(config('code.error'),'添加轮播图失败');
    }

    public function update($id){
        $data = input('put.');
        $data['id'] = $id;

        $CarouselModel = new CarouselModel();

        $result = $CarouselModel->editCarousel($data);

        if($result){
            return show(config('code.success'),'修改轮播图成功');
        }

        return show(config('code.error'),'修改轮播图失败');
    }

    public function delete($id){
        try{
            $CarouselModel = new CarouselModel();
            $CarouselModel->where('id','eq',$id)->delete();
        }catch (\Exception $e){
            return show(config('code.error'),'删除轮播图失败');
        }
        return show(config('code.success'),'删除轮播图成功');
    }
}