<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/12 0012
 * Time: 9:45
 */

namespace app\platformmgmt\model;

use thinl\Db;
use app\common\model\Article as ArticleModel;
use app\platformmgmt\model\ArticleGoodsRel as ArticleGoodsRelModel;

class Article extends ArticleModel
{
    /**
     * 添加文章
     * @param $data
     * @return bool
     */
    public function addArticle($data){
        // 开启事务
        Db::startTrans();
        try {
            // 添加文章
            $articleId = $this->add($data);
            // 添加关联商品
            $this->addArticleGoods($data['goods'],$articleId);
            Db::commit();
            return true;
        } catch (\Exception $e) {
            Db::rollback();
        }
        return false;
    }

    public function editArticle($data){
        // 开启事务
        Db::startTrans();
        try {
            // 修改文章
            $this->edit($data);
            // 添加关联商品
            $this->addArticleGoods($data['goods'],$data['id'],true);
            Db::commit();
            return true;
        } catch (\Exception $e) {
            Db::rollback();
        }
        return false;
    }

    /**
     * 添加文章关联商品
     * @param $goodsData
     * @param $articleId
     * @param bool $isUpdate
     */
    private function addArticleGoods($goodsData,$articleId,$isUpdate = false){

        $articleGoodsRelModel = new ArticleGoodsRelModel();
        $isUpdate && $articleGoodsRelModel->removeAll($articleId);

        if (isset($data['images']) && !empty($data['images'])) {
            $udata = [];
            foreach ($goodsData as $v){
                $udata[] = [
                  'article_id' => $articleId,
                  'goods_id' => $v['goods_id'],
                ];
            }
            $articleGoodsRelModel->saveAll($udata);
        }
    }

    public function remove($article_id){
        $articleGoodsRelModel = new ArticleGoodsRelModel();
        // 开启事务
        Db::startTrans();
        try {
            // 删除文章
            $this->delete($article_id);
            // 删除关联商品
            $articleGoodsRelModel->removeAll($article_id);
            Db::commit();
            return true;
        } catch (\Exception $e) {
            Db::rollback();
        }
        return false;
    }
}