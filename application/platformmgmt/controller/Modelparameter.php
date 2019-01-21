<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/10 0010
 * Time: 9:09
 */

namespace app\platformmgmt\controller;

use app\platformmgmt\model\ModelParameter as ModelParameterModel;

class Modelparameter extends Base
{
    public function read($id){
        $ModelParameterModel = new ModelParameterModel();
        $list = $ModelParameterModel->where('model_id','eq',$id)->select();

        if($list){
            return show(config('code.success'),'获取参数列表成功',$list);
        }

        return show(config('code.error'),'获取参数列表失败');
    }

    public function save(){
        $data = input('post.');
        $ModelParameterModel = new ModelParameterModel();

        $result = $ModelParameterModel->updateAll($data);

        if($result){
            return show(config('code.success'),'修改参数取值范围成功');
        }

        return show(config('code.error'),'修改参数取值范围失败');
    }
}