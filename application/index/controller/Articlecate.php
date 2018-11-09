<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/24 0024
 * Time: 10:18
 */

namespace app\index\controller;

use think\Controller;
use app\index\model\Articlecate as ArticleCateModle;

class Articlecate extends Controller
{
    public function index(){
        //实例化分类类
        $model = New ArticleCateModle();

        try{
            //权限树状数据
            $data = $model->getList();
        }catch (\Exception $e){
            return show(config('code.error'),$e->getMessage());
        }

        return show(config('code.success'), '获取文章分类列表成功', $data);
    }

}