<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/24 0024
 * Time: 16:04
 */

namespace app\platformmgmt\model;

use app\common\model\Brand as BrandModel;

class Brand extends BrandModel
{
    public function addBrand($data){
        $this->add($data);
    }

    public function editBrand($data){
        $this->edit($data);
    }
}