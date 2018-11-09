<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/24 0024
 * Time: 10:13
 */

namespace app\index\controller;


use think\Controller;
use app\index\model\Category as CategoryModel;

class Category extends Controller
{
    public function index(){
        $model = new CategoryModel();
        try{
            //权限树状数据
            $data = $model->getList();
        }catch (\Exception $e){
            return show(config('code.error'),$e->getMessage());
        }

        return show(config('code.success'), '获取分类列表成功', $data);
    }

}