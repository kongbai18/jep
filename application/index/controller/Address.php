<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/29 0029
 * Time: 9:36
 */

namespace app\index\controller;

use app\common\model\Address as AddressModel;

class Address extends Base
{
    public function index(){
        try{
            $data = model('address')->where(['user_id'=>['eq',$this->userId]])->select();
        }catch (\Exception $e){
            return show(config('code.error'),$e->getMessage());
        }

        return show(config('code.success'),'获取收货地址信息成功',$data);
    }

    public function read($id){
        $data = model('address')->find($id);

        if(!$data || $data->user_id != $this->userId){
            return show(config('code.error'),'收货地址不存在!');
        }

        return show(config('code.success'),'获取收货地址成功！',$data);
    }

    public function save(){
        $data = input('post.');

        //validate
        $data['user_id'] = $this->userId;
        $AddressModel = new AddressModel();
        $result = $AddressModel->add($data);

        if(!$result){
            return show(config('code.error'),'添加收货地址失败！');
        }

        return show(config('code.success'),'添加收货地址成功！');
    }

    public function update($id){
        $address = model('address')->find($id);

        if(!$address || $address->user_id != $this->userId){
            return show(config('code.error'),'收货地址不存在!');
        }

        $data = input('put.');
        $data['id'] = $id;

        //validate
        $AddressModel = new AddressModel();
        $result = $AddressModel->edit($data);

        if($result !== false){
            return show(config('code.success'),'修改收货地址成功！');
        }
        return show(config('code.error'),'修改收货地址失败！');

    }

    public function delete($id){
        $address = model('address')->find($id);

        if(!$address || $address->user_id != $this->userId){
            return show(config('code.error'),'收货地址不存在!');
        }

        $AddressModel = new AddressModel();
        $result = $AddressModel->where(['id'=>['eq',$id]])->delete();

        if(!$result){
            return show(config('code.error'),'删除失败！');
        }

        return show(config('code.success'),'删除成功！');
    }
}