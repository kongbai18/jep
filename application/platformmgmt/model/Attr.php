<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/26 0026
 * Time: 10:51
 */

namespace app\platformmgmt\model;

use app\common\model\Attr as AttrModel;

class Attr extends AttrModel
{

    /**
     * 根据规格组名称查询规格id
     * @param $spec_name
     * @return mixed
     */
    public function getAttrIdByName($attr_name,$type_id)
    {
        $attrData = $this->field('id')->where(['attr_name'=>['eq',$attr_name],'type_id'=>['eq',$type_id]])->find();

        if(empty($attrData)){
            return false;
        }else{
            return $attrData->id;
        }
    }
}