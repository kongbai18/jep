<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/26 0026
 * Time: 9:39
 */

namespace app\platformmgmt\controller;

use think\Controller;
use app\platformmgmt\controller\Base;

class Index extends Base
{
    public function index(){
        return $this->fetch();
    }

    public function welcome(){
        return $this->fetch();
    }
}