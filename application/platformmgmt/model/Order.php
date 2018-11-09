<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/1 0001
 * Time: 14:00
 */

namespace app\platformmgmt\model;

use app\common\model\Order as OrderModel;

class Order extends OrderModel
{
    public function getList($where=[]){
        $list = $this->where($where)->select();

        return $list;
    }

    public function getDetail($id){
        $order = $this->find($id);
        $address = model('order_address')->where(['order_no'=>['eq',$order['order_no']]])->find();
        $goods = model('order_goods')->where(['order_no'=>['eq',$order['order_no']]])->find();
        $detail = [
            'order' => $order,
            'address' => $address,
            'goods' => $goods,
        ];

        return $detail;
    }
}