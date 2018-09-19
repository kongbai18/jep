<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/12 0012
 * Time: 14:33
 */

namespace app\platformmgmt\model;

use app\common\model\ArticleGoodsRel as ArticleGoodsRelModel;
class ArticleGoodsRel extends ArticleGoodsRelModel
{
    public function removeAll($article_id){
        $this->where(['article_id'=>['eq',$article_id]])->delete();
    }
}