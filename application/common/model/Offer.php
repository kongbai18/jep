<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/8 0008
 * Time: 9:22
 */

namespace app\common\model;

use think\Db;

class Offer extends Base
{
    public function getOffer($role = false,$user = false){
        $where = [];
        $where['is_delete'] = ['eq',0];
        if($role){
            $where['role_id'] = ['eq',$role];
        }

        if($user){
            $where['user_id'] = ['eq',$user];
        }
        // 开启事务
        Db::startTrans();
        try {
            //获取基本信息
            $info = $this->getOfferInfo($where);
            //处理报价数据

            Db::commit();
            return true;
        } catch (\Exception $e) {
            Db::rollback();
        }
        return false;
    }

    private function getOfferInfo($where){
        $data = $this
            ->where($where)
            ->select()
            ->toArray();
        return $data;
    }

    private function calculateOffer($info){
        foreach ($info as $v){

        }
    }

    public function addOffer($role,$data){
        //获取家具属性ID
        $furAttrData = model('furniture_attr')->field('id,model_id')->where(['fur_id'=>['eq',$datap['fur_id']],'attr_sku_id'=>['eq',$data['attr_sku_id']]])->find();
        //处理材料
        $materialData = model('model_material')->where(['model_id'=>['eq',$furAttrData['model_id']]])->select()->toArray();
        foreach ($materialData as $v){
            if(empty($data['material'][$v['material_para']])){
                $this->error('请选择完整的材料');
            }
            $material[$v['material_para']] = [$data['material'][$v['material_para']]['goods_id'],$data['material'][$v['material_para']]['spec_sku_id']];
        }
        //处理参数
        $parameterData = model('model_parameter')->where(['model_id'=>['eq',$furAttrData['model_id']]])->select()->toArray();
        foreach ($parameterData as $v){
            if(empty($data['parameter'][$v['parameter']])){
                $this->error('请填写完整的参数');
            }
            $parameter[$v['parameter']] = [$data['parameter'][$v['parameter']]];
        }
        //处理扩展参数
        $extData = model('model_ext')->where(['model_id'=>['eq',$furAttrData['model_id']]])->select()->toArray();
        foreach ($extData as $v){
            if(empty($data['ext'][$v['ext_para']])){
                $this->error('请填写完整的参数');
            }
            $ext[$v['ext_para']] = [$data['ext'][$v['ext_para']]];
        }
        $data['material'] = json_encode($material);
        $data['parameter'] = json_encode($parameter);
        $data['ext'] = json_encode($ext);
        $data['role_id'] = $role;
        $data['fur_attr_id'] = $furAttrData['id'];

        $this->add($data);

        return true;
    }
}