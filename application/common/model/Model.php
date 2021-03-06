<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/27 0027
 * Time: 13:14
 */

namespace app\common\model;


class Model extends Base
{
    public function getModelDetail($model_id){
        try{
            //获取基本信息
            $model = $this->find($model_id);
            //获取模型材料
            $modelMaterial = $this->getModelMaterial($model_id);
            //获取模型参数
            $modelParameter = $this->getModelParameter($model_id);
            //获取模型参数
            $modelFormula = $this->getModelFormula($model_id);
            //获取模型参数
            $modelExt = $this->getModelExt($model_id);
        }catch (\Exception $e){
            return false;
        }

        $data = [
            'model' => $model,
            'material' => $modelMaterial,
            'parameter' => $modelParameter,
            'formula' => $modelFormula,
            'ext' => $modelExt,
        ];

        return $data;
    }

    private function getModelMaterial($model_id){
        $modelMaterialModel = model('model_material');

        $material = $modelMaterialModel->where(['model_id'=>['eq',$model_id]])->select()->toArray();
       //商品图
        $GoodsImage = model('goods_image');
        $goodsPicSql =  $GoodsImage->field('image_url')
            ->where('goods_id', 'EXP', "= `a`.`id`")->limit(1)->buildSql();

        foreach ($material as &$v){
            $goods = model('goods')->field(['a.id','a.goods_name',"$goodsPicSql AS goods_pic"])->alias('a')->where(['id'=>['in',$v['material_goods']]])->select();
            $v['goods'] = $goods;
        }

        return $material;
    }

    private function getModelParameter($model_id){
        $modelParameterModel = model('model_parameter');

        $parameter = $modelParameterModel->where(['model_id'=>['eq',$model_id]])->select()->toArray();

        return $parameter;
    }

    private function getModelFormula($model_id){
        $modelFormulaModel = model('model_formula');

        $formula = $modelFormulaModel->where(['model_id'=>['eq',$model_id]])->select()->toArray();

        return $formula;
    }

    private function getModelExt($model_id){
        $modelExtModel = model('model_ext');

        $ext = $modelExtModel->where(['model_id'=>['eq',$model_id]])->select()->toArray();

        foreach ($ext as &$v){
            $value = model('model_ext_val')->where('ext_id','eq',$v['id'])->select();
            $v['value'] = $value;
        }

        return $ext;
    }
}