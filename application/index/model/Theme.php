<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/16 0016
 * Time: 11:25
 */

namespace app\index\model;

use app\common\model\Theme as ThemeModel;

class Theme extends ThemeModel
{
    public function getTheme(){
        try{
            $rdata = [];
            $data = $this->field("theme_id,GROUP_CONCAT(cate_id) as cate")->where('is_index','eq',1)->group('theme_id')->select();
            $GoodsSpec = model('goods_spec');
            $minPriceSql = $GoodsSpec->field(['MIN(goods_price)'])
                ->where('goods_id', 'EXP', "= `a`.`id`")->buildSql();
            //商品图
            $GoodsImage = model('goods_image');
            $goodsPicSql =  $GoodsImage->field('image_url')
                ->where('goods_id', 'EXP', "= `a`.`id`")->limit(1)->buildSql();
            $whereData = [];
            $whereData['is_delete'] = ['eq',0];
            $whereData['goods_status'] = ['eq',10];
            foreach ($data as $k => $v){
                $cate = explode(',',$v['cate']);
                $rdata[$k]['info'] = [];
                $rdata[$k]['theme_id'] = $v['theme_id'];
                foreach ($cate as $c){
                    $cateData = model('category')->find($c);
                    $whereData['category_id'] = ['eq',$c];
                    $goods = model('goods')->field(['a.id','a.goods_name',
                        "$minPriceSql AS goods_min_price",
                        "$goodsPicSql AS goods_pic"
                    ])->alias('a')
                        ->join('category b','a.category_id = b.id','left')
                        ->where($whereData)
                        ->limit('8')
                        ->select()
                        ->toArray();
                    $rdata[$k]['info'][] = [
                        'cate_id' => $c,
                        'goods' => $goods,
                        'cate_name' => $cateData['name'],
                        'cate_en_name' => $cateData['en_name'],
                        'image_url' => $cateData['image_url'],
                    ];
                }
            }
        }catch (\Exception $e){
            return false;
        }

        return $rdata;
    }
}