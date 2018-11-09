<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/1 0001
 * Time: 13:58
 */

namespace app\platformmgmt\controller;

use app\platformmgmt\model\Order as OrderModel;

class Order extends Base
{
    public function delivery_list(){
        $where['pay_status'] = ['eq','10'];
        $where['order_status'] = ['eq','20'];

        try{
            $OrderModel = new OrderModel();
            $list = $OrderModel->getList($where);
        }catch (\Exception $e){
            return show(config('code.error'),$e->getMessage(),'',500);
        }

        return show(config('code.success'),'获取未发货订单列表成功！',$list);
    }

    public function receipt_list(){
        $where['pay_status'] = ['eq','10'];
        $where['order_status'] = ['eq','10'];

        try{
            $OrderModel = new OrderModel();
            $list = $OrderModel->getList($where);
        }catch (\Exception $e){
            return show(config('code.error'),$e->getMessage(),'',500);
        }

        return show(config('code.success'),'获取未收货订单列表成功！',$list);
    }

    public function pay_list(){
        $where['pay_status'] = ['eq','20'];

        try{
            $OrderModel = new OrderModel();
            $list = $OrderModel->getList($where);
        }catch (\Exception $e){
            return show(config('code.error'),$e->getMessage(),'',500);
        }

        return show(config('code.success'),'获取未支付订单列表成功！',$list);
    }

    public function cancel_list(){
        $where['pay_status'] = ['eq','10'];
        $where['order_status'] = ['eq','70'];n

        try{
            $OrderModel = new OrderModel();
            $list = $OrderModel->getList($where);
        }catch (\Exception $e){
            return show(config('code.error'),$e->getMessage(),'',500);
        }

        return show(config('code.success'),'获取已取消订单列表成功！',$list);
    }

    public function complete_list(){
        $where['pay_status'] = ['eq','10'];
        $where['order_status'] = ['eq','30'];

        try{
            $OrderModel = new OrderModel();
            $list = $OrderModel->getList($where);
        }catch (\Exception $e){
            return show(config('code.error'),$e->getMessage(),'',500);
        }

        return show(config('code.success'),'获取已完成订单列表成功！',$list);
    }


    public function return_list(){
        $where['pay_status'] = ['eq','10'];
        $where['order_status'] = ['eq','40'];

        try{
            $OrderModel = new OrderModel();
            $list = $OrderModel->getList($where);
        }catch (\Exception $e){
            return show(config('code.error'),$e->getMessage(),'',500);
        }

        return show(config('code.success'),'获取申请退货订单列表成功！',$list);
    }

    public function detail($id){
        try{
            $OrderModel = new OrderModel();
            $detail = $OrderModel->getDetail($id);
        }catch (\Exception $e){
            return show(config('code.error'),$e->getMessage(),'',500);
        }

        return show(config('code.success'),'获取订单详细信息成功！',$detail);
    }

}