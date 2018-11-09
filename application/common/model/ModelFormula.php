<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/27 0027
 * Time: 16:14
 */

namespace app\common\model;


class ModelFormula extends Base
{
    public function removeAll($model_id){
        $this->where(['model_id'=>['eq',$model_id]])->delete();
    }
}