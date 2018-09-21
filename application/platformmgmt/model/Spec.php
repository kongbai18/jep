<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/4 0004
 * Time: 14:16
 */

namespace app\platformmgmt\model;

use app\common\model\Spec as SpecModel;

class Spec extends SpecModel
{
    /**
     * 根据规格组名称查询规格id
     * @param $spec_name
     * @return mixed
     */
    public function getSpecIdByName($spec_name,$type_id)
    {
        $sepcData = $this->field('id')->where(['spec_name'=>['eq',$spec_name],'type_id'=>['eq',$type_id]])->find();

        if(empty($sepcData)){
            return false;
        }else{
            return $sepcData->id;
        }
    }
}