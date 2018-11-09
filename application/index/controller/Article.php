<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/24 0024
 * Time: 9:56
 */

namespace app\index\controller;

use app\index\model\Article as ArticleModel;

use think\Controller;

class Article extends Controller
{
    public function index(){
        $data = input('get.');

        $articleModel = new ArticleModel();

        $rdata = $articleModel->getArticleData($data);

        return show(config('code.success'),'获取文章列表成功',$rdata);
    }

    public function read($id){
        $articleModel = new ArticleModel();

        $article = $articleModel->find($id);

        if(empty($article)){
            return show(config('code.error'),'该文章不存在','', 404);
        }

        $data = $articleModel->getArticleDetail($id);

        return show(config('code.success'),'获取文章详情成功',$data);
    }
}