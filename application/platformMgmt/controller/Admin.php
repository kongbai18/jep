<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/26 0026
 * Time: 9:57
 */

namespace app\platformMgmt\controller;

use think\Controller;
class Admin extends Controller
{
    /*
     * 管理员列表
     */
    public function index(){
        return $this->fetch();
    }

    /*
     * 添加管理员
     */
    public function add(){
        return $this->fetch();
    }
}