<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/4 0004
 * Time: 10:13
 */

namespace app\common\model;


class HalfCustom extends Base
{
    public function getHalfCustomList(){
        try{
            $where['is_index'] = ['eq',1];
            //商品图
            $GoodsImage = model('goods_image');
            $goodsPicSql =  $GoodsImage->field('image_url')
                ->where('goods_id', 'EXP', "= `a`.`goods_id`")->limit(1)->buildSql();

            $list = $this->field(['id','goods_id','halfcust_name','sort_id','is_index',"$goodsPicSql AS goods_pic"])
                ->alias('a')->where($where)->order('sort_id asc')->select();
        }catch (\Exception $e){
            return false;
        }

        return $list;
    }

    public function getHalfCustomDetail($id){
        try{
            //商品图
            $GoodsImage = model('goods_image');
            $goodsPicSql =  $GoodsImage->field('image_url')
                ->where('goods_id', 'EXP', "= `goods_id`")->limit(1)->buildSql();

            $halfcust = $this->field(['id','goods_id','halfcust_name','sort_id','is_index',"$goodsPicSql AS goods_pic"])
                ->where(['id'=>['eq',$id]])->find();

            $hlafDetail = model('half_customdtl')->where(['halfcus_id'=>['eq',$id]])->select();

            //商品图
            $GoodsImage = model('goods_image');
            $goodsPicSql =  $GoodsImage->field('image_url')
                ->where('goods_id', 'EXP', "= `a`.`id`")->limit(1)->buildSql();

            foreach ($hlafDetail as $k => $v){
                $hlafDetail[$k]['goods_list'] = model('goods')->field(['a.id','a.goods_name',"$goodsPicSql AS goods_pic"])
                    ->alias('a')
                    ->where(['a.id'=>['in',$v['goods']]])
                    ->select();
            }

            $frameGoods = model('goods')->getGoodsDetail($halfcust['goods_id']);
            unset($frameGoods['goods']);
            unset($frameGoods['goodsImg']);
        }catch (\Exception $e){
            return false;
        }

        return [
            'halfcust' => $halfcust,
            'halfDetail' => $hlafDetail,
            'frameGoods' => $frameGoods,
        ];
    }
}