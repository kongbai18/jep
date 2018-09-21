<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/5 0005
 * Time: 14:13
 */

namespace app\platformmgmt\model;

use app\common\model\Delivery as DeliveryModel;

class Delivery extends DeliveryModel
{
    /**
     * 添加新记录
     * @param $data
     * @return bool|int
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function addDelivery($data)
    {
        if (!isset($data['rule']) || empty($data['rule'])) {
            $this->error = '请选择可配送区域';
            return false;
        }
        $data['wxapp_id'] = self::$wxapp_id;
        if ($this->allowField(true)->save($data)) {
            return $this->createDeliveryRule($data['rule']);
        }
        return false;
    }


}