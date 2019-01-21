<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/31 0031
 * Time: 14:29
 */

namespace app\common\model;


class Order extends Base
{
    public function getList($where=[]){
        $list = $this->where($where)->select();

        $data = [];

        foreach ($list as $v){
            $detail = $this->getDetail($v['id']);
            $data[] = $detail;
        }

        return $data;
    }

    public function getDetail($id){
        $order = $this->find($id);
        $address = model('order_address')->where(['order_no'=>['eq',$order['order_no']]])->find();
        $goods = model('order_goods')->where(['order_no'=>['eq',$order['order_no']]])->select();
        $detail = [
            'order' => $order,
            'address' => $address,
            'goods' => $goods,
        ];

        return $detail;
    }
}