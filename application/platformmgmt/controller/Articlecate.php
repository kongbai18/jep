<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/12 0012
 * Time: 9:29
 */

namespace app\platformmgmt\controller;

use app\platformmgmt\model\ArticleCate as ArticleCateModel;
class Articlecate extends Base
{
    /**
     *
     * @SWG\Get(path="/platformMgmt/v1/articleCate",
     *   summary="获取分类树状列表信息",
     *   description="请求该接口需要先登录并且有此权限,不需要传参",
     *   @SWG\Response(response="返回json数组,包含状态码status,描述message，数据data，以及头部httpCode",
     *      description="状态码为 1 时成功返回数据data；为 0 时失败并返回提示失败原因message；为 -1 时为未登录；为 -2 时为无此权限 "
     *  )
     * )
     */
    public function index(){
        //实例化分类类
        $articleCateModel = New ArticleCateModel();

        //权限树状数据
        $articleCateList = $articleCateModel->getList();

        $data =  [
            'categoryData' => $articleCateList
        ];

        return show(config('code.success'), '获取文章分类列表成功', $data);
    }

    /**
     *
     * @SWG\Get(path="/platformMgmt/v1/articleCate/{id}",
     *   summary="获取分类信息",
     *   description="请求该接口需要先登录并且有此权限。",
     *   @SWG\Parameter(name="id",in="path",type="number",required="true",
     *      description="权限ID"
     *   ),
     *   @SWG\Response(response="返回json数组,包含状态码status,描述message，数据data，以及头部httpCode",
     *      description="状态码为 1 时成功返回数据data；为 0 时失败并返回提示失败原因message；为 -1 时为未登录；为 -2 时为无此权限 "
     *  )
     * )
     */
    public function read($id){
        //实例化分类类
        $articleCateModel = New ArticleCateModel();

        $articleCateData = $articleCateModel->find($id);

        if(empty($articleCateData)){
            return show(config('code.error'),'该文章分类不存在','', 404);
        }

        $data =  [
            'categoryData' => $articleCateData
        ];

        return show(config('code.success'), '获取文章分类信息成功', $data);

    }

    /**
     *
     * @SWG\Post(path="/platformMgmt/v1/articleCate/getChild",
     *   summary="获取分类子集及本身",
     *   description="请求该接口需要先登录并且有此权限。",
     *   @SWG\Parameter(name="id",in="formData",type="number",required="true",
     *      description="权限ID"
     *   ),
     *   @SWG\Response(response="返回json数组,包含状态码status,描述message，数据data，以及头部httpCode",
     *      description="状态码为 1 时成功返回数据data；为 0 时失败并返回提示失败原因message；为 -1 时为未登录；为 -2 时为无此权限 "
     *  )
     * )
     */
    public function getChild(){
        $id = input('post.id');
        $articleCateModel = New ArticleCateModel();

        $articleCateData = $articleCateModel->get($id);

        if(empty($articleCateData)){
            return show(config('code.error'),'该文章分类不存在');
        }

        $data = $articleCateModel->getChild($id);

        return show(config('code.success'),'获取文章分类子集成功',$data);
    }

    /**
     *
     * @SWG\Post(path="/platformMgmt/v1/articleCate",
     *   summary="添加分类",
     *   description="请求该接口需要先登录并且有此权限。",
     *   @SWG\Parameter(name="name",in="formData",type="string",required="true",
     *      description="权限名,不得超过30字符"
     *   ),
     *     @SWG\Parameter(name="parent_id",in="formData",type="number",required="true",
     *      description="上级权限ID"
     *   ),
     *   @SWG\Parameter(name="module_name",in="formData",type="string",
     *      description="模板名称"
     *   ),
     *   @SWG\Parameter(name="controller_name",in="formData",type="string",
     *      description="控制器名称"
     *   ),
     *     @SWG\Parameter(name="action_name",in="formData",type="string",
     *      description="方法名"
     *   ),
     *
     *   @SWG\Response(response="返回json数组,包含状态码status,描述message，数据data，以及头部httpCode",
     *      description="状态码为 1 时成功；为 0 时失败并返回提示失败原因message；为 -1 时为未登录；为 -2 时为无此权限 "
     *  )
     * )
     */
    public function save(){
        //实例化权限类
        $articleCateModel = New ArticleCateModel();

        $data = input('post.');

        // validate
        $validate = validate('Category');
        if(!$validate->check($data)) {
            return show(config('code.error'), $validate->getError());
        }


        //入库操作
        try {
            $id = $articleCateModel->add($data);
        }catch (\Exception $e) {
            return show(config('code.error'),'新增失败','',500);
        }

        if($id) {
            return show(config('code.success'), '新增文章分类成功');
        } else {
            return show(config('code.error'), '新增失败','',500);
        }
    }


    /**
     *
     * @SWG\Get(path="/platformMgmt/v1/articleCate/{id}/edit",
     *   summary="修改权限信息",
     *   description="请求该接口需要先登录并且有此权限。",
     *   @SWG\Parameter(name="id",in="path",type="string",required="true",
     *      description="权限Id"
     *   ),
     *   @SWG\Parameter(name="name",in="query",type="string",required="true",
     *      description="权限名,不得超过30字符"
     *   ),
     *     @SWG\Parameter(name="parent_id",in="query",type="number",required="true",
     *      description="上级ID"
     *   ),
     *   @SWG\Parameter(name="module_name",in="query",type="string",
     *      description="模板名称"
     *   ),
     *   @SWG\Parameter(name="controller_name",in="query",type="string",
     *      description="控制器名称"
     *   ),
     *     @SWG\Parameter(name="action_name",in="query",type="string",
     *      description="方法名"
     *   ),
     *
     *   @SWG\Response(response="返回json数组,包含状态码status,描述message，数据data，以及头部httpCode",
     *      description="状态码为 1 时成功；为 0 时失败并返回提示失败原因message；为 -1 时为未登录；为 -2 时为无此权限 "
     *  )
     * )
     */
    public function edit($id){
        $articleCateData = model('article_cate')->find($id);

        if(empty($articleCateData)){
            return show(config('code.error'),'该文章分类不存在','', 404);
        }

        //实例化权限类
        $articleCateModel = New ArticleCateModel();
        $data = input('get.');
        $data['id'] = $id;

        // validate
        $validate = validate('Category');
        if(!$validate->check($data)) {
            return show(config('code.error'), $validate->getError());
        }

        //入库操作
        try {
            $result = $articleCateModel->edit($data);
        }catch (\Exception $e) {
            return show(config('code.error'), '更新失败','',500);
        }

        if($result !== false) {
            return show(config('code.success'), '更新文章分类成功');
        } else {
            return show(config('code.error'), '更新失败','',500);
        }
    }

    /**
     *
     * @SWG\Delete(path="/platformMgmt/v1/articleCate/{id}",
     *   summary="删除权限",
     *   description="请求该接口需要先登录并且有此权限。",
     *   @SWG\Parameter(name="id",in="path",type="number",required="true",
     *      description="权限ID"
     *   ),
     *   @SWG\Response(response="返回json数组,包含状态码status,描述message，数据data，以及头部httpCode",
     *      description="状态码为 1 时成功；为 0 时失败并返回提示失败原因message；为 -1 时为未登录；为 -2 时为无此权限 "
     *  )
     * )
     */
    public function delete($id){
        $articleCateData = model('article_cate')->find($id);

        if(empty($articleCateData)){
            return show(config('code.error'),'该文章分类不存在','', 404);
        }

        //判断分类下是否存在文章
        $count=model('article')->where(['cate_id'=>['eq',$id]])->count();


        if($count > 0){
            return show(config('code.error'),'有文章包含此分类，不可删除');
        }


        $articleCateModel = New ArticleCateModel();
        //判断是否存在子集权限
        try{
            $child=$articleCateModel->where(['parent_id'=>['eq',$id]])->count();
        }catch (\Exception $e){
            return show(config('code.error'),'删除失败','',500);
        }

        if($child > 0){
            return show(config('code.error'),'删除失败,有子集分类未删除');
        }

        //执行删除
        try{
            $articleCateModel->where(['id'=>['eq',$id]])->delete();
        }catch(\Exception $e){
            return show(config('code.error'),'删除失败','',500);
        }
        return show(config('code.success'),'删除文章分类成功');

    }
}