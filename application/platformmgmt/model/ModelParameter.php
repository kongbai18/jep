<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/10 0010
 * Time: 9:09
 */

namespace app\platformmgmt\model;

use app\common\model\ModelParameter as ModelParameterModel;

class ModelParameter extends ModelParameterModel
{
    public function updateAll($data){
        try{
            foreach ($data as $v){
                $udata = [
                    'min' => $v['min'],
                    'max' => $v['max'],
                ];

                $result = $this->where('id',$v['id'])->update($udata);

                if ($result === false){
                    return false;
                }
            }
        }catch (\Exception $e){
            return false;
        }

        return true;
    }
}