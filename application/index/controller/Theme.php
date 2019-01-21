<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/16 0016
 * Time: 11:24
 */

namespace app\index\controller;

use think\Controller;
use app\index\model\Theme as ThemeModel;

class Theme extends Controller
{
    public function index(){
        $themeModel = new ThemeModel();

        $result = $themeModel->getTheme();

        if($result !== false){
            return show(config('code.success'),'获取信息成功',$result);
        }
        return show(config('code.error'),'获取信息失败');
    }
}