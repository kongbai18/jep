<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/6 0006
 * Time: 10:30
 */

namespace app\platformmgmt\model;

use app\common\model\DeliveryRule as DeliveryRuleModel;

class DeliveryRule extends DeliveryRuleModel
{
    /**
     * 添加模板区域及运费
     * @param $data
     * @return int
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function createDeliveryRule($data)
    {
        $save = [];
        $connt = count($data['region']);
        for ($i = 0; $i < $connt; $i++) {
            $save[] = [
                'region' => $data['region'][$i],
                'delivery_id' => $data['delivery_id'],
                'first' => $data['first'][$i],
                'first_fee' => $data['first_fee'][$i],
                'additional' => $data['additional'][$i],
                'additional_fee' => $data['additional_fee'][$i],
            ];
        }

        $this->where(['delivery_id'=>['eq',$data['delivery_id']]])->delete();

        if($this->saveAll($save)){
            return true;
        }

        return false;
    }
}