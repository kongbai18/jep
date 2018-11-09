<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/27 0027
 * Time: 16:13
 */

namespace app\common\model;


class ModelMaterial extends Base
{
    public function removeAll($model_id){
        $this->where(['model_id'=>['eq',$model_id]])->delete();
    }
}