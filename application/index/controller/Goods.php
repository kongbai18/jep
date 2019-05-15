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
        $data = input('post.');

        $goodsModel = new GoodsModel();

        $whereData['goods_status'] = ['eq',10];
        $rdata = $goodsModel->getGoodsData($data,$whereData);

        return show(config('code.success'),'获取商品列表成功',$rdata);
    }

    public function read($id){
        $goodsModel = new GoodsModel();

        $data = $goodsModel->getGoodsDetail($id);

        return show(config('code.success'),'获取商品详情成功',$data);
    }
}