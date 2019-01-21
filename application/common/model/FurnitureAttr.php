<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/26 0026
 * Time: 11:16
 */

namespace app\common\model;


class FurnitureAttr extends Base
{
    public function getlist($id){
        try{
            $data = $this->where('fur_id','eq',$id)->select();
        }catch (\Exception $e){
            return false;
        }

        return $data;
    }
}