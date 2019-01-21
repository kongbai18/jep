<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/26 0026
 * Time: 10:55
 */

namespace app\index\controller;


class Brand
{
    public function index(){
        $data = model('brand')->where(['is_index'=>['eq','1']])->select();

        return show(config('code.success'),'获取品牌列表成功',$data);
    }
}