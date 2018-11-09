<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/24 0024
 * Time: 14:21
 */

namespace app\index\model;

use app\common\model\Cart as CartModel;

class Cart extends CartModel
{
    public function addCart($data,$goods,$userId){
        $where = [];
        $where['user_id'] = ['eq',$userId];
        $where['is_delete'] = ['eq',0];
        $where['product_type'] = ['eq',$data['product_type']];
        $where['product_id'] = ['eq',$data['product_id']];
        $where['spec_sku_id'] = ['eq',$data['spec_sku_id']];

        $cart = $this->where($where)->find();

        if($cart){
            $udata = [
                'id' => $cart['id'],
                'goods_num' => $data['goods_num'] + $cart['goods_num'],
            ];

            $this->update($udata);
        }else{
            if($data['product_type'] == config('cart.goods')){
                $data['image_url'] = $goods['image_url'];
                $data['goods_price'] = $goods['goods_price'];
                $data['user_id'] = $userId;

                $goodsData = model('goods')->find($data['product_id']);
                $data['goods_name'] = $goodsData['goods_name'];

                if($data['spec_sku_id'] == 0){
                    $data['goods_attr'] = '默认';
                }else{
                    $spec = explode('_',$data['spec_sku_id']);

                    $specVal = model('spec_value')->field('a.spec_value_alt,b.spec_name')
                        ->alias('a')
                        ->where(['a.id'=>['in',$spec]])
                        ->join('spec b','a.spec_id = b.id','left')
                        ->select()
                        ->toArray();

                    $attr = [];

                    foreach($specVal as $v){
                        $attr[] = $v['spec_name'].':'.$v['spec_value_alt'];
                    }

                    $data['goods_attr'] = implode(';',$attr);
                }

                $this->add($data);
            }
        }
    }
}