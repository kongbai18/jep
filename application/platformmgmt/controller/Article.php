<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/12 0012
 * Time: 9:42
 */

namespace app\platformmgmt\controller;

use app\platformmgmt\model\Article as ArticleModel;

class Article extends Base
{
    /**
     * @SWG\Get(path="/platformMgmt/v1/article",
     *   summary="获取文章列表信息",
     *   description="请求该接口需要先登录并且有此权限,不需要传参",
     *   @SWG\Response(response="返回json数组,包含状态码status,描述message，数据data，以及头部httpCode",
     *      description="状态码为 1 时成功返回数据data；为 0 时失败并返回提示失败原因message；为 -1 时为未登录；为 -2 时为无此权限"
     *   )
     * )
     */
    public function index(){
       $data = input('get.');

       $articleModel = new ArticleModel();

       $rdata = $articleModel->getArticleData($data);

       return show(config('code.success'),'获取文章列表成功',$rdata);
    }

    /**
     *
     * @SWG\Get(path="/platformMgmt/v1/article/{article_id}",
     *   summary="获取文章详细信息",
     *   description="请求该接口需要先登录并且有此权限。",
     *   @SWG\Parameter(name="article_id",in="path",type="number",required="true",
     *      description="管理员ID"
     *   ),
     *   @SWG\Response(response="返回json数组,包含状态码status,描述message，数据data，以及头部httpCode",
     *      description="状态码为 1 时成功返回数据data；为 0 时失败并返回提示失败原因message；为 -1 时为未登录；为 -2 时为无此权限 "
     *   )
     * )
     */
    public function read($id){
        $articleModel = new ArticleModel();

        $article = $articleModel->find($id);

        if(empty($article)){
            return show(config('code.error'),'该文章不存在','', 404);
        }

        $data = $articleModel->getArticleDetail($id);

        return show(config('code.success'),'获取文章详情成功',$data);
    }


    /**
     *
     * @SWG\Post(path="/platformMgmt/v1/article",
     *   summary="添加文章",
     *   description="请求该接口需要先登录并且有此权限。",
     *   @SWG\Parameter(name="article_name",in="formData",type="string",required="true",
     *      description="文章标题,不得超过50字符"
     *   ),
     *   @SWG\Parameter(name="article_brief",in="formData",type="string",required="true",
     *      description="文章简介，不得高于150位字符"
     *   ),
     *   @SWG\Parameter(name="cate_id",in="formData",type="string",required="true",
     *      description="文章所属分类，根据文章分类列表选择"
     *   ),
     *     @SWG\Parameter(name="image_url",in="formData",type="string",
     *      description="封面图，根据图片上传类获取得到的存储路径"
     *   ),
     *     @SWG\Parameter(name="sort_id",in="formData",type="string",
     *      description="排序"
     *   ),
     *     @SWG\Parameter(name="is_index",in="formData",type="number",required="true",
     *      description="是否展示"
     *   ),
     *
     *   @SWG\Response(response="返回json数组,包含状态码status,描述message，数据data，以及头部httpCode",
     *      description="状态码为 1 时成功；为 0 时失败并返回提示失败原因message；为 -1 时为未登录；为 -2 时为无此权限 "
     *   )
     * )
     */
    public function save(){
        $data = input('post.');

        // validate
        $validate = validate('Article');
        if(!$validate->check($data)) {
            return show(config('code.error'), $validate->getError());
        }

        $articleModel = new ArticleModel();
        if($articleModel->addArticle($data)){
            return show(config('code.success'),'添加成功');
        }

        return show(config('code.error'),'添加失败');
    }


    /**
     *
     * @SWG\Post(path="/platformMgmt/v1/admin/article",
     *   summary="修改文章",
     *   description="请求该接口需要先登录并且有此权限。",
     *   @SWG\Parameter(name="article_id",in="path",type="string",required="true",
     *      description="文章Id"
     *   ),
     *   @SWG\Parameter(name="article_name",in="query",type="string",required="true",
     *      description="文章标题,不得超过50字符"
     *   ),
     *   @SWG\Parameter(name="article_brief",in="query",type="string",required="true",
     *      description="文章简介，不得高于150位字符"
     *   ),
     *   @SWG\Parameter(name="cate_id",in="query",type="string",required="true",
     *      description="文章所属分类，根据文章分类列表选择"
     *   ),
     *     @SWG\Parameter(name="image_url",in="query",type="string",
     *      description="封面图，根据图片上传类获取得到的存储路径"
     *   ),
     *     @SWG\Parameter(name="sort_id",in="query",type="string",
     *      description="排序"
     *   ),
     *     @SWG\Parameter(name="is_index",in="query",type="number",required="true",
     *      description="是否展示"
     *   ),
     *
     *   @SWG\Response(response="返回json数组,包含状态码status,描述message，数据data，以及头部httpCode",
     *      description="状态码为 1 时成功；为 0 时失败并返回提示失败原因message；为 -1 时为未登录；为 -2 时为无此权限 "
     *   )
     * )
     */
    public function edit(){
        $data = input('post.');
        $id = $data['id'];
        $article = model('article')->find($id);

        if(empty($article)){
            return show(config('code.error'),'该文章不存在','', 404);
        }



        // validate
        $validate = validate('Article');
        if(!$validate->check($data)) {
            return show(config('code.error'), $validate->getError());
        }

        $articleModel = new ArticleModel();
        if($articleModel->editArticle($data)){
            return show(config('code.success'),'修改成功');
        }

        return show(config('code.error'),'修改失败');
    }

    /**
     *
     * @SWG\Delete(path="/platformMgmt/v1/admin/{article_id}",
     *   summary="删除文章",
     *   description="请求该接口需要先登录并且有此权限。",
     *   @SWG\Parameter(name="article_id",in="path",type="number",required="true",
     *      description="文章ID"
     *   ),
     *   @SWG\Response(response="返回json数组,包含状态码status,描述message，数据data，以及头部httpCode",
     *      description="状态码为 1 时成功；为 0 时失败并返回提示失败原因message；为 -1 时为未登录；为 -2 时为无此权限 "
     *  )
     * )
     */
    public function delete($id){
        $article = model('article')->find($id);

        if(empty($article)){
            return show(config('code.error'),'该文章不存在','', 404);
        }

        $articleModel = new ArticleModel();

        if($articleModel->remove($id)){
            return show(config('code.success'),'删除成功');
        }

        return show(config('code.error'),'删除失败');
    }
}