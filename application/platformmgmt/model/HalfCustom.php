<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/4 0004
 * Time: 10:13
 */

namespace app\platformmgmt\model;

use app\common\model\HalfCustom as HalfCustomModel;
use think\Db;

class HalfCustom extends HalfCustomModel
{
    public function addHalfCustom($data){
        // 开启事务
        Db::startTrans();
        try {
            // 添加半定制
            $halfCusId = $this->add($data);
            // 添加半定制详情
            $this->addHalfCusDtl($data['detail'],$halfCusId);
            Db::commit();
            return true;
        } catch (\Exception $e) {
            Db::rollback();
        }
        return false;
    }

    public function editHalfCustom($data){
        // 开启事务
        Db::startTrans();
        try {
            // 修改半定制
            $this->edit($data);
            // 修改半定制详情
            $this->addHalfCusDtl($data['detail'],$data['id'],true);
            Db::commit();
            return true;
        } catch (\Exception $e) {
            Db::rollback();
        }
        return false;
    }

    protected function addHalfCusDtl($data,$halfCusId,$isUpdate = false){

        // 更新模式: 先删除
        $HalfCustomdtlModel = new HalfCustomdtl();
        $isUpdate && $HalfCustomdtlModel->removeAll($halfCusId);

        foreach ($data as $k => $v){
            $udata[] = [
                'halfcus_id' => $halfCusId,
                'type_name' => $v['type_name'],
                'num' => $v['num'],
                'goods' => $v['goods'],
            ];
        }
        $HalfCustomdtlModel->saveAll($udata);
    }

    public function remove($id){
        // 开启事务
        Db::startTrans();
        try{
            $this->where('id','eq',$id)->delete();

            $HalfCustomdtlModel = new HalfCustomdtl();
            $HalfCustomdtlModel->removeAll($id);
        }catch (\Exception $e){
            Db::rollback();
            return false;
        }
        Db::commit();
        return true;
    }
}