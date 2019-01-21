<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/27 0027
 * Time: 13:14
 */

namespace app\platformmgmt\model;

use think\Db;
use app\common\model\Model as ModelModel;
use app\common\model\ModelMaterial as ModelMaterialModel;
use app\common\model\ModelParameter as ModelParameterModel;
use app\common\model\ModelFormula as ModelFormulaModel;
use app\common\model\ModelExt as ModelExtModel;

class Model extends ModelModel
{
    public function addModel($data){
        // 开启事务
        Db::startTrans();
        try {
            // 添加基本信息
            $modelId = $this->add($data);
            // 添加相关材料
            $this->addModelMaterial($data['material'],$modelId);
            //添加相关参数
            $this->addModelParameter($data['parameter'],$modelId);
            //添加相关计算公式
            $this->addModelFormula($data['formula'],$modelId);
            //添加相关扩展
            $this->addModelExt($data['ext'],$modelId);
            Db::commit();
            return true;
        } catch (\Exception $e) {
            var_dump($e->getMessage());
            Db::rollback();
        }
        return false;
    }

    public function editModel($data){
        // 开启事务
        Db::startTrans();
        try {
            // 添加基本信息
            $this->edit($data);
            // 添加相关材料
            $this->addModelMaterial($data['material'],$data['id'],true);
            //添加相关参数
            $this->addModelParameter($data['parameter'],$data['id']);
            //添加相关计算公式
            $this->addModelFormula($data['formula'],$data['id'],true);
            //添加相关扩展
            $this->addModelExt($data['ext'],$data['id'],true);
            Db::commit();
            return true;
        } catch (\Exception $e) {
            Db::rollback();
        }
        return false;
    }

    private function addModelMaterial($material,$modelId,$isUpdate = false){
        $modelMaterialModel = new ModelMaterialModel();
        $isUpdate && $modelMaterialModel->removeAll($modelId);

        $udata = [];
        foreach ($material as $v){
            $udata[] = [
                'model_id' => $modelId,
                'material_name' => $v['name'],
                'material_para' => $v['para'],
                'material_goods' => $v['goods'],
            ];
        }
        $modelMaterialModel->saveAll($udata);
    }


    private function addModelParameter($parameter,$modelId){
        $modelParameterModel = new ModelParameterModel();
        $oldPara = $modelParameterModel->field('id')->where('model_id','eq',$modelId)->select()->toArray();
        $upOldPara = [];
        $udata = [];
        foreach ($parameter as $v){
            if(!empty($v['id'])){
                $upOldPara[] = $v['id'];
                $udata[] = [
                    'id' => $v['id'],
                    'model_id' => $modelId,
                    'parameter' => $v['para'],
                ];
            }else{
                $udata[] = [
                    'model_id' => $modelId,
                    'parameter' => $v['para'],
                ];
            }
        }
        foreach ($oldPara as $v){
            $isDel = true;
            foreach ($upOldPara as $item){
                if($v['id'] == $item){
                    $isDel = false;
                }
            }
            if($isDel){
                $modelParameterModel->where('id','eq',$v['id'])->delete();
            }
        }
        $modelParameterModel->saveAll($udata);
    }

    private function addModelFormula($formula,$modelId,$isUpdate = false){
        $modelFormulaModel = new ModelFormulaModel();
        $isUpdate && $modelFormulaModel->removeAll($modelId);

        $udata = [];
        foreach ($formula as $v){
            $udata[] = [
                'model_id' => $modelId,
                'formula_name' => $v['formula_name'],
                'number' => $v['number'],
                'price' => $v['price'],
                'unit' => $v['unit'],
                'remark' => $v['remark'],
            ];
        }
        $modelFormulaModel->saveAll($udata);
    }

    private function addModelExt($ext,$modelId,$isUpdate = false){
        $modelExtModel = new ModelExtModel();
        $isUpdate && $modelExtModel->removeAll($modelId);


        foreach ($ext as $v){
            $udata = [
                'model_id' => $modelId,
                'type_id' => $v['type_id'],
                'ext_name' => $v['ext_name'],
                'ext_para' => $v['ext_para'],
            ];
            $extId = $modelExtModel->insertGetId($udata);

            if($v['type_id'] == 1 || $v['type_id'] == 2){
                foreach ($v['value'] as $item){
                    $udata = [
                        'ext_id' => $extId,
                        'par_name' => $item['par_name'],
                        'ext_val' => $item['ext_val'],
                    ];
                    model('model_ext_val')->insert($udata);
                }
            }

        }
    }

    public function remove($model_id){
        // 开启事务
        Db::startTrans();
        try {
            $modelMaterialModel = new ModelMaterialModel();
            $modelParameterModel = new ModelParameterModel();
            $modelFormulaModel = new ModelFormulaModel();
            $modelExtModel = new ModelExtModel();
            // 删除基本信息
            $this->where('id','eq',$model_id)->delete();
            // 删除相关材料
            $modelMaterialModel->removeAll($model_id);
            // 删除相关参数
            $modelParameterModel->removeAll($model_id);
            // 删除相关公式
            $modelFormulaModel->removeAll($model_id);
            // 删除相关扩展
            $modelExtModel->removeAll($model_id);
            Db::commit();
            return true;
        } catch (\Exception $e) {
            Db::rollback();
        }
        return false;
    }
}