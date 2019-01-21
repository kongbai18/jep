<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/5 0005
 * Time: 14:51
 */

namespace app\common\model;


class HalfCustomQuotedtl extends Base
{
    public function removeAll($hcqId)
    {
        $this->where('hcq_id','=', $hcqId)->delete();
    }
}