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
    public function index(){
        $OrderModel = new OrderModel();
        $list = $OrderModel->getList();

        return show(config('code.success'),'获取订单列表成功！',$list);
    }

    public function changePayPrice(){
        $data = input('post.');

        $udata = [
            'id' => $data['id'],
            'pay_price' => $data['pay_price'],
        ];

        try{
            model('order')->update($udata);
        }catch (\Exception $e){
            return show(config('code.error'),$e->getMessage());
        }

        return show(config('code.success'),'修改支付金额成功');

    }

    public function goDelivery(){
        $data = input('post.');

        $udata = [
            'id' => $data['id'],
            'order_status' => 10,
            'express_no' => $data['express_no'],
        ];

        try{
            model('order')->update($udata);
        }catch (\Exception $e){
            return show(config('code.error'),$e->getMessage());
        }

        return show(config('code.success'),'发货成功');
    }

    public function deliveryList(){
        $where['pay_status'] = ['eq','10'];
        $where['order_status'] = ['eq','20'];


        $OrderModel = new OrderModel();
        $list = $OrderModel->getList($where);

        return show(config('code.success'),'获取未发货订单列表成功！',$list);
    }

    public function receiptList(){
        $where['pay_status'] = ['eq','10'];
        $where['order_status'] = ['eq','10'];


        $OrderModel = new OrderModel();
        $list = $OrderModel->getList($where);

        return show(config('code.success'),'获取未收货订单列表成功！',$list);
    }

    public function payList(){
        $where['pay_status'] = ['eq','20'];


        $OrderModel = new OrderModel();
        $list = $OrderModel->getList($where);

        return show(config('code.success'),'获取未支付订单列表成功！',$list);
    }

    public function cancelList(){
        $where['pay_status'] = ['eq','10'];
        $where['order_status'] = ['eq','70'];


        $OrderModel = new OrderModel();
        $list = $OrderModel->getList($where);

        return show(config('code.success'),'获取已取消订单列表成功！',$list);
    }

    public function completeList(){
        $where['pay_status'] = ['eq','10'];
        $where['order_status'] = ['eq','30'];


        $OrderModel = new OrderModel();
        $list = $OrderModel->getList($where);

        return show(config('code.success'),'获取已完成订单列表成功！',$list);
    }


    public function returnList(){
        $where['pay_status'] = ['eq','10'];
        $where['order_status'] = ['eq','40'];

        $OrderModel = new OrderModel();
        $list = $OrderModel->getList($where);

        return show(config('code.success'),'获取申请退货订单列表成功！',$list);
    }

    public function detail(){
        $id = input('post.id');
        $OrderModel = new OrderModel();
        $detail = $OrderModel->getDetail($id);
        if(!$detail){
            return show(config('code.error'),'获取订单详细信息失败！','',500);
        }

        return show(config('code.success'),'获取订单详细信息成功！',$detail);
    }


}