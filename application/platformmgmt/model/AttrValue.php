<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/26 0026
 * Time: 10:52
 */

namespace app\platformmgmt\model;

use app\common\model\AttrValue as AttrValueModel;

class AttrValue extends AttrValueModel
{
    /**
     * 根据规格组名称查询规格id
     * @param $spec_id
     * @param $spec_value
     * @return mixed
     */
    public function getAttrValueIdByName($attr_id,$attr_value)
    {
        $attrValueData = $this->field('id')->where(['attr_value'=>['eq',$attr_value],'attr_id'=>['eq',$attr_id]])->find();

        if(empty($attrValueData)){
            return false;
        }else{
            return $attrValueData->id;
        }
    }
}