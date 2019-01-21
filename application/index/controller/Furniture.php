<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/19 0019
 * Time: 11:16
 */

namespace app\index\controller;

use app\common\model\Furniture as FurnitureModel;
use think\Controller;

class Furniture extends Controller
{
    public function index(){
        $Furniture = new FurnitureModel();

        $data = $Furniture->getFurnitureData();

        return show(config('code.success'),'获取家具信息成功',$data);
    }

    public function read($id){
        $FurnitureModel = new FurnitureModel();

        $furniture = $FurnitureModel->find($id);

        if(empty($furniture)){
            return show(config('code.error'),'该家具不存在','', 404);
        }

        try{
            $data = $FurnitureModel->getFurnitureDetail($id);
        }catch (\Exception $e){
            return show(config('code.error'),'获取家具信息失败');
        }

        return show(config('code.success'),'获取家具信息成功',$data);

    }
}