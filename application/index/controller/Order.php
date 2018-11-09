<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/31 0031
 * Time: 13:19
 */

namespace app\index\controller;

use app\index\model\Order as OrderModel;
use app\common\lib\wxchat\WxPay;

class Order extends Base
{
    public function save(){
        $data = input('post.');

        //validata

        try{
            $OrderModel = new OrderModel();
            $OrderModel->addOrder($data,$this->userId);
        }catch (\Exception $e){
            return show(config('code.error'),'添加订单失败！');
        }

        return show(config('code.success'),'添加订单成功!');
    }

    public function update($id){
        $order = model('order')->find($id);

        if(!$order || $order['pay_status'] == 10){
            return show(config('code.error'),'订单不存在或已支付');
        }

        $WxPay = new WxPay();
        $responde = $WxPay->orderquery($order['wxpay_no']);

        if($responde['return_code'] == 'SUCCESS' && $responde['result_code'] == 'SUCCESS' && $responde['trade_state'] == 'SUCCESS' && $responde['total_fee'] >= $order['pay_price']){
            $udata = [
                'pay_status' = 10,
                'pat_time' = time();
            ];
            try{
                model('order')->update($udata,$id);
            }catch (\Exception $e){
                return show(config('code.error'),$e->getMessage(),'',500);
            }

            return show(config('code.success'),'订单支付成功！');
        }

        return show(config('code.error'),'订单支付失败！');
    }
}