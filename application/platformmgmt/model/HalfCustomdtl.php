<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/5 0005
 * Time: 9:32
 */

namespace app\platformmgmt\model;

use app\common\model\HalfCustomdtl as HalfCustomdtlModel;

class HalfCustomdtl extends HalfCustomdtlModel
{
    public function removeAll($halfCusId)
    {
        $this->where('halfcus_id','=', $halfCusId)->delete();
    }
}