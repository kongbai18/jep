<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/19 0019
 * Time: 11:21
 */

namespace app\index\controller;

use app\common\model\FurnitureAttr as FurnitureAttrModel;

class Furnitureattr extends Base
{
    public function read($id){
        $FurnitureAttrModel = new FurnitureAttrModel();
        $data = $FurnitureAttrModel->getlist($id);

        if($data !== false){
            return show(config('code.success'),'获取信息成功',$data);
        }

        return show(config('code.error'),'获取信息失败');
    }
}