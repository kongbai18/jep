<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/31 0031
 * Time: 14:29
 */

namespace app\index\model;

use app\common\model\Order as OrderModle;
use app\common\lib\exception\ApiException;
use app\common\model\OrderGoods as OrderGoodsModel;
use think\Db;

class Order extends  OrderModle
{
    public function addOrder($data,$userId){
        // 开启事务
        Db::startTrans();
        try {
            $orderNo = create_unique();
            // 订单地址
            $this->addOrderAddress($data['address_id'],$orderNo);
            // 订单商品
            if(is_string($data['goods'])){
                $data['goods'] = json_decode($data['goods'],true);
            }
            $price = $this->addOrderGoods($data['goods'],$orderNo,$userId);
            //订单
            $udata = [
                'order_no' => $orderNo,
                'user_id' => $userId,
                'total_price' => $price,
                'pay_price' => $price,
            ];
            $id = $this->add($udata);
        } catch (\Exception $e) {
            Db::rollback();
            return false;
        }

        Db::commit();
        return $id;
    }

    private function addOrderAddress($addressId,$orderNo){
        $address = model('address')->find($addressId)->toArray();
        unset($address['id']);
        unset($address['user_id']);
        unset($address['create_time']);
        unset($address['update_time']);
        $address['order_no'] = $orderNo;
        model('order_address')->save($address);
    }

    private function addOrderGoods($goods,$orderNo,$userId){
        $price = 0;
         $cartModel = model('cart');
         $cartWhere['user_id'] = ['eq',$userId];
         foreach ($goods as $v){
             $cartWhere['product_type'] = ['eq',$v['product_type']];
             $cartWhere['product_id'] = ['eq',$v['product_id']];
             $cartWhere['spec_sku_id'] = ['eq',$v['spec_sku_id']];
             $cartModel->where($cartWhere)->delete();
             if($v['product_type'] == 1){
                 $spec = model('goods_spec')->where(['goods_id'=>['eq',$v['product_id']],'spec_sku_id'=>['eq',$v['spec_sku_id']]])->find();

                 if(!$spec || $spec['stock_num'] < $v['goods_num']){
                     throw new ApiException($v['goods_name'].'库存不足！',200,config('code.error'));
                 }

                 $udata = [
                     'order_no' => $orderNo,
                     'product_type' => $v['product_type'],
                     'product_id' => $v['product_id'],
                     'spec_sku_id' => $v['spec_sku_id'],
                     'goods_name' => $v['goods_name'],
                     'goods_num' => $v['goods_num'],
                     'image_url' => $spec['image_url'],
                     'goods_price' => $spec['goods_price'],
                     'create_time' => time(),
                 ];

                 if($v['spec_sku_id'] == 0){
                     $udata['goods_attr'] = '默认';
                 }else{
                     $specArr = explode('_',$v['spec_sku_id']);

                     $specVal = model('spec_value')->field('a.spec_value_alt,b.spec_name')
                         ->alias('a')
                         ->where(['a.id'=>['in',$specArr]])
                         ->join('spec b','a.spec_id = b.id','left')
                         ->select()
                         ->toArray();

                     $attr = [];

                     foreach($specVal as $item){
                         $attr[] = $item['spec_name'].':'.$item['spec_value_alt'];
                     }

                     $udata['goods_attr'] = implode(';',$attr);
                 }

                 $OrderGoodsModel =new OrderGoodsModel();
                 $OrderGoodsModel->save($udata);


                 $price = $price + $spec['goods_price'] * $v['goods_num'];
             }
         }

         return $price;
    }
}