<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/16 0016
 * Time: 9:27
 */

namespace app\index\controller;


use think\Controller;

class Carousel extends Controller
{
    public function index(){
        try{
            $carouselData = model('carousel')->where('is_index','eq',1)->select();
        }catch (\Exception $e){
            return show(config('code.error'),'获取轮播图失败');
        }

        return show(config('code.success'),'获取轮播图成功',$carouselData);
    }
}