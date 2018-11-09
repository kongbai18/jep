<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/24 0024
 * Time: 10:47
 */

namespace app\index\controller;

use app\index\model\Cart as CartModel;
use think\Controller;


class Cart extends Controller
{

    public function index(){
        try{
            $data = model('cart')->where(['user_id'=>['eq',1],'is_delete'=>['eq',0]])->select();
        }catch (\Exception $e){
            return show(config('code.error'),$e->getMessage());
        }

        return show(config('code.success'),'获取购物车信息成功',$data);
    }

    public function save(){
        $data = input('post.');

        if($data['product_type'] == config('cart.goods')){
            $goods = model('goods_spec')->where(['goods_id'=>['eq',$data['product_id']],'spec_sku_id'=>['eq',$data['spec_sku_id']]])->find();
        }else if(data['product_type'] == config('cart.customization')){

        }

        if (!$goods || $goods->stock_num < $data['goods_num']){
            return show(config('code.error','商品不存在或库存不足!'));
        }

        try{
            $model = new CartModel();
            $model->addCart($data,$goods,1);
        }catch (\Exception $e){
            return show(config('code.error'),$e->getMessage(),'',500);
        }

        return show(config('code.success'),'添加购物车成功!');
    }

    public function delete($id){
        $cart = model('cart')->find($id);

        if(!$cart){
            return show(config('code.error'),'此购物车商品不存在');
        }

        if (1 != $cart->user_id){
            return show(config('code.error'),'您无权删除此购物车商品！');
        }

        $udata = [
            'id'=>$id,
            'is_delete' => 1,
        ];

        try{
            model('cart')->update($udata);
        }catch (\Exception $e){
            return show(config('code.error'),$e->getMessage());
        }

        return show(config('code.success'),'删除成功!');
    }
}