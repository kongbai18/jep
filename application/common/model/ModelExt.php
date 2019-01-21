<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/27 0027
 * Time: 16:14
 */

namespace app\common\model;


class ModelExt extends Base
{
    public function removeAll($model_id){
        $ext = $this->where('model_id','eq',$model_id)->select();
        foreach ($ext as $v){
            model('model_ext_val')->where('id','eq',$v['id'])->delete();
        }
        $this->where(['model_id'=>['eq',$model_id]])->delete();
    }
}