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
        $whereData = [];

        if(isset($data['cate_id']) && !empty($data['cate_id'])){
            $whereData['cate_id'] = ['eq',$data['cate_id']];
        }

        if(isset($data['is_index']) && !empty($data['is_index'])){
            $whereData['is_index'] = ['eq',$data['is_index']];
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
        return $this->where($whereData)->count();
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
        $result = $this->field('a.*,b.name')
            ->alias('a')
            ->join('article_cate b','a.cate_id = b.id','left')
            ->where($whereData)
            ->limit($from,$size)
            ->select();

        return $result;
    }


    public function getArticleDetail($article_id){
        $article = $this->find($article_id);

        $goodsId = model('article_goods_rel')->where(['article_id'=>['eq',$article_id]])->select()->toArray();

        //$goodsData = model('goods')->getGoodsDataById($goodsId);

        $data = [
            'article' => $article,
            'goods' => $goodsId,
        ];

        return $data;
    }
}