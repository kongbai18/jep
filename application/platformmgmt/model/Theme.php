<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/16 0016
 * Time: 10:44
 */

namespace app\platformmgmt\model;

use app\common\model\Theme as ThemeModel;

class Theme extends ThemeModel
{
    public function getTheme(){
       try{
           $data = $this->field('a.id,a.theme_id,a.sort_id,a.is_index,b.name as cate_name')
               ->alias('a')
               ->join('category b','a.cate_id = b.id','left')
               ->select();
       }catch (\Exception $e){
           return false;
       }
       return $data;
    }


    public function addTheme($data){
        try{
            $this->save($data);
        }catch (\Exception $e){
            return false;
        }
        return true;
    }

    public function editTheme($data){
        try{
            $this->edit($data);
        }catch (\Exception $e){
            return false;
        }
        return true;
    }
}