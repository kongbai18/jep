<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/4 0004
 * Time: 14:20
 */

namespace app\platformmgmt\model;

use app\common\model\SpecValue as SepcValueModel;

class SpecValue extends SepcValueModel
{
    /**
     * 根据规格组名称查询规格id
     * @param $spec_id
     * @param $spec_value
     * @return mixed
     */
    public function getSpecValueIdByName($spec_id,$spec_value_alt)
    {
        $sepcValueData = $this->field('id')->where(['spec_value_alt'=>['eq',$spec_value_alt],'spec_id'=>['eq',$spec_id]])->find();

        if(empty($sepcValueData)){
            return false;
        }else{
            return $sepcValueData->id;
        }
    }
}