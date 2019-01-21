<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/19 0019
 * Time: 11:19
 */

namespace app\index\controller;

use app\common\model\Model as ModelModel;

class Model extends Base
{
    public function read($id){
        $model = model('model')->find($id);

        if(empty($model)){
            return show(config('code.error'),'该模型不存在','', 404);
        }

        $modelModle = new ModelModel();

        if($data = $modelModle->getModelDetail($id)){
            return show(config('code.success'),'获取模型详细信息成功',$data);
        }

        return show(config('code.error'),'获取模型详细信息失败');
    }
}