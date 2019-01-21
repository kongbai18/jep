<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/19 0019
 * Time: 11:13
 */

namespace app\index\controller;

use app\common\model\HalfCustom as HalfCustomModel;
use think\Controller;

class Halfcustom extends Controller
{
    public function index(){

        $HalfCustom = new HalfCustomModel();
        $result = $HalfCustom->getHalfCustomList();

        if($result){
            return show(config('code.success'),'获取半定制列表成功',$result);
        }

        return show(config('code.error'),'获取半定制列表失败');
    }

    public function read($id){
        $HalfCustom = new HalfCustomModel();

        $result = $HalfCustom->getHalfCustomDetail($id);

        if($result){
            return show(config('code.success'),'获取半定制信息成功',$result);
        }

        return show(config('code.error'),'获取半定制信息失败');
    }
}