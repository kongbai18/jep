<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/1 0001
 * Time: 9:36
 */

namespace app\index\controller;

use app\common\lib\wxchat\WxPay;
use app\common\lib\email\Email;
use think\Controller;


class Test extends Controller
{
    public function index(){
        $email = new Email();
        $email->sendEmail('kongbai0108@126.com',896452);
    }
}