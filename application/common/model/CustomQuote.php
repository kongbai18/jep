<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/10 0010
 * Time: 13:29
 */

namespace app\common\model;

use think\Db;

class CustomQuote extends  Base
{
    public function getQuote($where = []){
        $where['is_delete'] = ['eq',0];
        try{
            $quote = $this->field('a.id,a.user_type,a.user_id,a.fur_id,a.attr_sku_id,b.model_id,b.image_url,c.fur_name,c.cate_id')
                ->alias('a')
                ->join('furniture_attr b','a.fur_id = b.fur_id AND a.attr_sku_id = b.attr_sku_id','left')
                ->join('furniture c','a.fur_id = c.id','left')
                ->where($where)
                ->select();

            $data = [];
            foreach ($quote as &$v){
                $v['total_price'] = 0;
                $attr = explode('_',$v['attr_sku_id']);
                $sttrSku = [];
                foreach ($attr as $ind => $item){
                    $attrVal = model('attr_value')->field('a.attr_value,b.attr_name')
                        ->alias('a')
                        ->join('attr b','b.id = a.attr_id','left')
                        ->where('a.id','eq',$item)
                        ->find();
                    if($attrVal){
                        $sttrSku[] = $attrVal['attr_name'].':'.$attrVal['attr_value'];
                        if($ind == 1){
                            $mult = $attrVal['attr_value'];
                        }
                    }else{
                        $sttrSku[] = '默认';
                    }
                }

                $v['attr_sku_val'] = implode(';',$sttrSku);

                $customPara = model('custom_parameter')->where('cust_id','eq',$v['id'])->select();
                foreach ($customPara as $para){
                    $$para['parameter'] = $para['value'];
                }

                $customMaterial = model('custom_material')->where('cust_id','eq',$v['id'])->select();
                $goodsSpec = [];
                foreach ($customMaterial as $mater){
                    $modelMaterial = model('model_material')->find($mater['material_id']);
                    $goods = model('goods_spec')->field('goods_price')->where(['goods_id'=>['eq',$mater['goods_id']],'spec_sku_id'=>['eq',$mater['spec_sku_id']]])->find();
                    $$modelMaterial['material_para'] = $goods['goods_price'];

                    $goodsData = model('goods')->field('goods_name')->find($mater['goods_id']);
                    $spec = explode('_',$mater['spec_sku_id']);
                    $goodsSpecAttr = [];
                    foreach ($spec as $item){
                        $specVal = model('spec_value')->field('a.spec_value_alt,b.spec_name')
                            ->alias('a')
                            ->join('spec b','b.id = a.spec_id','left')
                            ->where('a.id','eq',$item)
                            ->find();
                        if($specVal){
                            $goodsSpecAttr[] = $specVal['spec_name'].':'.$specVal['spec_value_alt'];
                        }else{
                            $goodsSpecAttr[] = '默认';
                        }

                    }


                    $goodsSpec[] = [
                        'material_name' => $modelMaterial['material_name'],
                        'material_val' => $goodsData['goods_name'].'['.implode(';',$goodsSpecAttr).']',
                    ];
                }

                $customExt = model('custom_ext')->where('cust_id','eq',$v['id'])->select();

                $extName = [];
                foreach ($customExt as $ext){
                    $modelExt = model('model_ext')->find($ext['ext_id']);
                    if($modelExt['type_id'] == 3){
                        $$modelExt['ext_para'] =  $ext['ext_val'];
                    }else{
                        $$modelExt['ext_para'] = 0;
                        $extNameAttr = [];
                        $customExtVal = model('model_ext_val')->where('id','in',$ext['ext_val'])->select();
                        foreach ($customExtVal as $val){
                            $$modelExt['ext_para'] += $val['ext_val'];
                            $extNameAttr[] = $val['par_name'];
                        }
                        $extName[] = [
                            'ext_name' => $modelExt['ext_name'],
                            'ext_value' => implode(',',$extNameAttr),
                        ];
                    }
                }

                $formula = [];
                $modelFormula = model('model_formula')->where('model_id','eq',$v['model_id'])->select();
                foreach ($modelFormula as $forml){
                    $formula[] = [
                        'name' => $forml['formula_name'],
                        'number' => eval($forml['number']),
                        'price' => eval($forml['price']),
                        'unit' => $forml['unit'],
                        'remake' => $forml['remark'],
                    ];

                    $v['total_price'] = $v['total_price'] + eval($forml['number'])*eval($forml['price']);
                }

                $model = model('model')->find($v['model_id']);

                $v['project_area'] = eval($model['project_area']);

                $data[] = [
                    'quote' => $v,
                    'goodsSpec' => $goodsSpec,
                    'extName' => $extName,
                ];
            }
        }catch (\Exception $e){
            return false;
        }

        return $data;
    }

    public function addCustomQuote($data){
        Db::startTrans();
        try{
            $custId = $this->add($data);

            if(is_string($data['para'])){
                $data['para'] = json_decode($data['para'],true);
            }
            $this->addCustomPara($data['para'],$custId);

            if(is_string($data['material'])){
                $data['material'] = json_decode($data['material'],true);
            }
            $this->addCustommaterial($data['material'],$custId);

            if($data['ext']){
                if(is_string($data['ext'])){
                    $data['ext'] = json_decode($data['ext'],true);
                }
                $this->addCustomExt($data['ext'],$custId);
            }
        }catch (\Exception $e){
            Db::rollback();
            return false;
        }
        Db::commit();
        return true;
    }

    private function addCustomPara($para,$custId){
        foreach ($para as $v){
            $udata[] = [
                'cust_id' => $custId,
                'parameter' => $v['parameter'],
                'value' => $v['value'],
            ];
        }
        model('custom_parameter')->insertAll($udata);
    }

    private function addCustommaterial($material,$custId){
        foreach ($material as $v){
            $udata[] = [
                'cust_id' => $custId,
                'material_id' => $v['material_id'],
                'goods_id' => $v['goods_id'],
                'spec_sku_id' => $v['spec_sku_id'],
            ];
        }
        model('custom_material')->insertAll($udata);
    }

    private function addCustomExt($ext,$custId){
        foreach ($ext as $v){
            $udata[] = [
                'cust_id' => $custId,
                'ext_id' => $v['ext_id'],
                'ext_val' => $v['ext_val'],
            ];
        }
        model('custom_ext')->insertAll($udata);
    }
}