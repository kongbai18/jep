<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/16 0016
 * Time: 9:29
 */

namespace app\platformmgmt\model;

use app\common\model\Carousel as CarouselModel;

class Carousel extends CarouselModel
{
    public function addCarousel($data){
        try{
            $this->add($data);
        }catch (\Exception $e){
            return false;
        }
        return true;
    }

    public function editCarousel($data){
        try{
            $this->edit($data);
        }catch (\Exception $e){
            return false;
        }
        return true;
    }
}