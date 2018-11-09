<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/24 0024
 * Time: 9:51
 */

namespace app\index\controller;


use think\Controller;
use app\index\model\Goods as GoodsModel;

class Goods extends Controller
{
    public function index(){
        $data = input('get.');

        $goodsModel = new GoodsModel();

        $rdata = $goodsModel->getGoodsData($data);

        return show(config('code.success'),'获取商品列表成功',$rdata);
    }

    public function read($id){
        $goodsModel = new GoodsModel();

        $data = $goodsModel->getGoodsDetail($id);

        return show(config('code.success'),'获取商品详情成功',$data);
    }
}