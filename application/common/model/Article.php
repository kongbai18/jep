<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/12 0012
 * Time: 9:44
 */

namespace app\common\model;


class Article extends Base
{
    /**
     * 获取文章分页数据
     * @param $data
     * @return array
     */
    public function getArticleData($data){
        $whereData['a.is_index'] = ['eq',1];
        if(isset($data['cate_id']) && !empty($data['cate_id'])){
            $whereData['cate_id'] = ['eq',$data['cate_id']];
        }

        if(isset($data['is_index'])){
            $whereData['a.is_index'] = ['eq',$data['is_index']];
        }

        if(isset($data['keywords']) && !empty($data['keywords'])){
            $whereData['article_name'] = ['like','%'.trim($data['keywords']).'%'];
        }

        //获取分页数据
        $total = $this->getArticleCount($whereData);
        $this->getPageAndSize($data);
        $pageTotal = ceil($total/$this->size);
        if(isset($data['page']) && $data['page'] > $pageTotal){
            $this->page = $pageTotal;
        }


        $article = $this->getArticle($whereData,$this->from,$this->size);

        $pageData = [
            'total' => $total,
            'page_total' => $pageTotal,
            'page_num' => $this->page,
            'page_size' => $this->size,
        ];

        $data = [
            'pageData' => $pageData,
            'article' => $article,
        ];

        return $data;
    }

    /**
     * 或者文章总数
     * @param $whereData
     * @return int|string
     */
    public function getArticleCount($whereData){
        return $this->alias('a')->where($whereData)->count();
    }


    /**
     * 获取当前页文章数据
     * @param $whereData
     * @param $from
     * @param $size
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getArticle($whereData,$from,$size){
        $result = $this->field('a.id,a.article_name,a.article_brief,a.image_url,b.name')
            ->alias('a')
            ->join('article_cate b','a.cate_id = b.id','left')
            ->where($whereData)
            ->limit($from,$size)
            ->select();

        return $result;
    }


    public function getArticleDetail($article_id){
        $article = $this->find($article_id);

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
        $goods = model('goods')->field(['a.id','a.goods_name',
            "$minPriceSql AS goods_min_price",
            "$maxPriceSql AS goods_max_price",
            "$goodsPicSql AS goods_pic"
        ])->alias('a')
            ->join('goods_image b','a.id = b.goods_id','left')
            ->join('article_goods_rel c','a.id = c.goods_id','right')
            ->where(['c.article_id'=>['eq',$article_id]])
            ->group('a.id')
            ->select()
            ->toArray();

        $data = [
            'article' => $article,
            'goods' => $goods,
        ];

        return $data;
    }
}