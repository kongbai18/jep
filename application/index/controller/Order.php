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
    public function index(){
        $OrderModel = new OrderModel();
        $where['user_id'] = ['eq',$this->userId];
        $where['order_status'] = ['neq','70'];

        $list = $OrderModel->getList($where);

        return show(config('code.success'),'获取未发货订单列表成功！',$list);

    }

    public function read($id){
        $OrderModel = new OrderModel();
        $detail = $OrderModel->getDetail($id);
        if($detail['order']['user_id'] == $this->userId){
            return show(config('code.success'),'获取订单详情成功！',$detail);
        }

        return show(config('code.error'),'获取订单详情失败！');
    }

    public function save(){
        $data = input('post.');

        //validata


        $OrderModel = new OrderModel();
        $result = $OrderModel->addOrder($data,$this->userId);

        if(!$result){
            return show(config('code.error'),'添加订单失败！');
        }

        return show(config('code.success'),'添加订单成功!',$result);
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
                'pay_status' => 10,
                'pat_time' => time(),
            ];

            $result = model('order')->update($udata,$id);

            if($result !== false){
                return show(config('code.error'),'订单支付失败！');
            }

            return show(config('code.success'),'订单支付成功！');
        }

        return show(config('code.error'),'订单支付失败！');
    }


    public function delete($id){
        $order = model('order')->find($id);

        if($order['user_id'] != $this->userId){
            return show(config('code.error'),'您没有修改权限');
        }

        $udata = [
            'order_status' => 70,
        ];

        $result = model('order')->where('id','eq',$id)->update($udata);

        if($result !== false){
            return show(config('code.success'),'删除成功');
        }

        return show(config('code.error'),'删除失败');
    }

    public function drawBack(){
        $id = input('post.id');
        $orderData = model('order')->find($id);
        if($orderData['user_id'] != $this->userId){
            return show(config('code.error'),'无申请权限');
        }
        $udata = [
            'order_status' => 40,
        ];
        try{
            model('order')->where('id','eq',$id)->update($udata);
        }catch (\Exception $e){
            return show(config('code.error'),'申请退货失败');
        }

        return show(config('code.success'),'申请退货成功');
    }
    public function getPayCode(){
        $id = input('post.id');

        $order = model('order')->find($id);

        if($order['pay_status'] == 20) {
            $wxpayNo = create_unique();
            $Wxpay = new WxPay();
            $result = $Wxpay->unifiedorder($wxpayNo, '', $order['pay_price'], 'NATIVE');

            $udata=[
                'id' => $id,
                'wxpay_no' => $wxpayNo,
            ];

            $res = model('order')->update($udata);

            if($res === false){
                return show(config('code.error'),'获取支付二维码链接失败!');
            }

            return show(config('code.success'), '获取支付二维码链接成功!', $result);
        }

        return show(config('code.error'),'获取支付二维码链接失败!');
    }

    /**
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getJsApi(){
        $id = input('post.id');
        $order = model('order')->find($id);

        $wxInfo = model('wxuser')->field('open_id')->where('user_id','eq',$this->userId)->find();

        if($order['pay_status'] == 20) {
            $wxpayNo = create_unique();
            $Wxpay = new WxPay();
            $result = $Wxpay->unifiedorder($wxpayNo, $wxInfo['open_id'], $order['pay_price'], 'JSAPI');

            $udata=[
                'id' => $id,
                'wxpay_no' => $wxpayNo,
            ];

            $res = model('order')->update($udata);

            if($res === false){
                return show(config('code.error'),'获取支付信息失败');
            }

            return show(config('code.success'), '获取支付信息成功!', $result);
        }

        return show(config('code.error'),'获取支付信息失败!');
    }

    public function getWxpayStatus(){
        $id = input('post.id');

        $order = model('order')->find($id);

        $Wxpay = new WxPay();

        $result = $Wxpay->orderquery($order['wxpay_no']);

        if($result['return_code'] !== 'SUCCESS' || $result['trade_state'] !== 'SUCCESS'){
            return show(config('code.error'),'尚未支付成功');
        }

        return show(config('code.success'),'支付成功');
    }

    public function deliveryList(){
        $where['pay_status'] = ['eq','10'];
        $where['order_status'] = ['eq','20'];
        $where['user_id'] = ['eq',$this->userId];


        $OrderModel = new OrderModel();
        $list = $OrderModel->getList($where);

        return show(config('code.success'),'获取待发货订单列表成功！',$list);
    }

    public function receiptList(){
        $where['pay_status'] = ['eq','10'];
        $where['order_status'] = ['eq','10'];
        $where['user_id'] = ['eq',$this->userId];

        $OrderModel = new OrderModel();
        $list = $OrderModel->getList($where);

        return show(config('code.success'),'获取待收货订单列表成功！',$list);
    }

    public function payList(){
        $where['pay_status'] = ['eq','20'];
        $where['order_status'] = ['eq','20'];
        $where['user_id'] = ['eq',$this->userId];

        $OrderModel = new OrderModel();
        $list = $OrderModel->getList($where);

        return show(config('code.success'),'获取未支付订单列表成功！',$list);
    }

    public function completeList(){
        $where['pay_status'] = ['eq','10'];
        $where['order_status'] = ['eq','30'];
        $where['user_id'] = ['eq',$this->userId];

        $OrderModel = new OrderModel();
        $list = $OrderModel->getList($where);

        return show(config('code.success'),'获取已完成订单列表成功！',$list);
    }


    public function returnList(){
        $where['pay_status'] = ['eq','10'];
        $where['order_status'] = ['eq','40'];
        $where['user_id'] = ['eq',$this->userId];

        $OrderModel = new OrderModel();
        $list = $OrderModel->getList($where);

        return show(config('code.success'),'获取申请退货订单列表成功！',$list);
    }

    public function confirmOrder(){
        $id = input('post.id');

        $order = model('order')->find($id);

        if($order['user_id'] != $this->userId){
            return show(config('code.error'),'您没有修改权限');
        }

        if($order['pay_status'] != '10' || $order['order_status'] != '10'){
            return show(config('code.error'),'订单不可确认');
        }

        $udata = [
            'order_status' => 30,
        ];

        $result = model('order')->where('id','eq',$id)->update($udata);

        if($result !== false){
            return show(config('code.success'),'确认收货成功');
        }

        return show(config('code.error'),'确认收货失败');
    }

    public function cancelBack(){
        $id = input('post.id');

        $order = model('order')->find($id);

        if($order['user_id'] != $this->userId){
            return show(config('code.error'),'您没有修改权限');
        }

        if($order['express_no']){
            $orderStatus = 10;
        }else{
            $orderStatus = 20;
        }

        $udata = [
            'order_status' => $orderStatus,
        ];

        $result = model('order')->where('id','eq',$id)->update($udata);

        if($result !== false){
            return show(config('code.success'),'取消退货成功');
        }

        return show(config('code.error'),'取消退货失败');
    }
}