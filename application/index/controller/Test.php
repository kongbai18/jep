<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/1 0001
 * Time: 9:36
 */

namespace app\index\controller;

use app\common\lib\wxchat\WxPay;
use think\Controller;


class Test extends Controller
{
    public function index(){
        $Wxpay = new WxPay();
        return $Wxpay->orderquery('5346554646');
    }
}