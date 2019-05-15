<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/10 0010
 * Time: 9:18
 */

namespace app\common\model;

use app\common\model\GoodsSpec;
use app\common\model\Category;

class Goods extends Base
{
    public function getGoodsData($data,$whereData = []{
        //搜索条件
        $whereData['is_delete'] = ['eq',0];

        if(isset($data['category_id']) && !empty($data['category_id'])){
            $categoryModel = new Category();
            $cateArr = $categoryModel->getChildSelf($data['category_id']);
            $cateArr =implode(',',$cateArr);
            $whereData['category_id'] = ['in',$cateArr];
        }

        if(isset($data['brand_id']) && !empty($data['brand_id'])){
            $whereData['brand_id'] = ['eq',$data['brand_id']];
        }

        if(isset($data['goods_status']) && !empty($data['goods_status'])){
            $whereData['goods_status'] = ['eq',$data['goods_status']];
        }

        if(isset($data['is_delete']) && !empty($data['is_delete'])){
            $whereData['is_delete'] = ['eq',$data['is_delete']];
        }

        if(isset($data['keywords']) && !empty($data['keywords'])){
            $whereData['goods_name'] = ['like','%'.trim($data['keywords']).'%'];
        }

        //排序方式
        $sortData = ['sort_id'=>'asc'];
        if(isset($data['sort_type']) && !empty($data['sort_type'])){
            if($data['sort_type'] == 'new'){
                $sortData = ['a.id'=>'desc'];
            }elseif ($data['sort_type'] == 'price'){
                if($data['sort_price']){
                    $sortData = ['goods_min_price'=>'desc'];
                }else{
                    $sortData = ['goods_min_price'=>'asc'];
                }
            }
        }

        $total = $this->getGoodsCount($whereData);
        $this->getPageAndSize($data);
        $pageTotal = ceil($total/$this->size);
        if(isset($data['page']) && $data['page'] > $pageTotal){
            $this->page = $pageTotal;
        }

        $goods = $this->getGoods($whereData,$sortData,$this->from,$this->size);

        $pageData = [
            'total' => $total,
            'page_total' => $pageTotal,
            'page_num' => $this->page,
            'page_size' => $this->size,
        ];

        $data = [
            'pageData' => $pageData,
            'goods' => $goods,
        ];

        return $data;
    }

    /**
     * 获取商品总数
     * @param $whereData
     * @return int|string
     */
    private function getGoodsCount($whereData){
        return $this->where($whereData)->count();
    }


    /**
     * 获取单页商品数据
     * @param array $whereData
     * @param array $sortData
     * @param $from
     * @param $size
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getGoods($whereData=[],$sortData=[],$from='',$size=''){
        // 多规格商品 最高价与最低价
        $GoodsSpec = model('goods_spec');
        $minPriceSql = $GoodsSpec->field(['MIN(goods_price)'])
            ->where('goods_id', 'EXP', "= `a`.`id`")->buildSql();
        $maxPriceSql = $GoodsSpec->field(['MAX(goods_price)'])
            ->where('goods_id', 'EXP', "= `a`.`id`")->buildSql();

        //商品图
        $GoodsImage = model('goods_image');
        $goodsPicSql =  $GoodsImage->field('image_url')
            ->where('goods_id', 'EXP', "= `a`.`id`")->limit(1)->buildSql();

        // 执行查询
        $list = $this->field(['a.*','d.name as brand_name','c.name as cate_name',
            "$minPriceSql AS goods_min_price",
            "$maxPriceSql AS goods_max_price",
            "$goodsPicSql AS goods_pic"
        ])->alias('a')
            ->join('goods_image b','a.id = b.goods_id','left')
            ->join('category c','a.category_id = c.id','left')
            ->join('brand d','a.brand_id = d.id','left')
            ->where($whereData)
            ->order($sortData)
            ->limit($from,$size)
            ->group('a.id')
            ->select()
            ->toArray();

        return $list;
    }


    public function getGoodsDetail($goods_id){
        // 多规格商品 最高价与最低价
        $GoodsSpec = model('goods_spec');
        $minPriceSql = $GoodsSpec->field(['MIN(goods_price)'])
            ->where('goods_id', 'EXP', "= `a`.`id`")->buildSql();
        $maxPriceSql = $GoodsSpec->field(['MAX(goods_price)'])
            ->where('goods_id', 'EXP', "= `a`.`id`")->buildSql();

        //获取商品基本信息
        $goods = $this->field(['a.*','b.name as cate_name',"$minPriceSql AS goods_min_price",
            "$maxPriceSql AS goods_max_price"])
            ->alias('a')
            ->join('category b','a.category_id = b.id','left')
            ->join('brand c','a.brand_id = c.id','left')
            ->find($goods_id)
            ->toArray();

        //获取商品图片
        $goodsImgModel = model('goods_image');
        $goodsImg = $goodsImgModel->field('id,image_url')->where(['goods_id'=>['eq',$goods_id]])->select()->toArray();

        //获取商品SKU
        $goodsSpec = $this->getManySpecData($goods_id);

        $data = [
            'goods' => $goods,
            'goodsImg' =>$goodsImg,
            'goodsSpecData' => $goodsSpec['goodsSpecData'],
            'goodsSpecRelData' => $goodsSpec['goodsSpecRelData'],
        ];

        return $data;
    }

    /**
     * 获取规格信息
     * @param \think\Collection $spec_rel
     * @param \think\Collection $skuData
     * @return array
     */
    public function getManySpecData($goods_id){
        $goodsSpecModel = model('goods_spec');
        $goodsSpecData = $goodsSpecModel->where(['goods_id'=>['eq',$goods_id]])->select()->toArray();

        $goodsSpecRelModel = model('goods_spec_rel');
        $goodsSpecRelData = $goodsSpecRelModel->field('a.spec_id,a.spec_value_id,b.spec_name,b.type_id,c.spec_value,c.spec_value_alt')
            ->alias('a')
            ->join('spec b','a.spec_id = b.id','left')
            ->join('spec_value c','a.spec_value_id = c.id','left')
            ->where(['goods_id'=>['eq',$goods_id]])->select()->toArray();

        $goodsSpec = [
            'goodsSpecData' => $goodsSpecData,
            'goodsSpecRelData' => $goodsSpecRelData,
        ];

        return $goodsSpec;
    }
}