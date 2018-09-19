<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/31 0031
 * Time: 13:36
 */

namespace app\platformmgmt\behavior;


class Test
{
    public function responseSend(&$params)
    {    // 响应头设置 我们就是通过设置header来跨域的 这就主要代码了 定义行为只是为了前台每次请求都能走这段代码

        $origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '';

        $allow_origin = array(
            'http://jiihomeapidec.shimentown.com',
            'http://localhost:8002'
        );

        if(in_array($origin, $allow_origin)){
            header('Access-Control-Allow-Origin:'.$origin);
            header('Access-Control-Allow-Methods:POST, GET, OPTIONS, PUT, DELETE');
            header('Access-Control-Allow-Headers:Accept,Referer,Host,Keep-Alive,User-Agent,X-Requested-With,Cache-Control,Content-Type,Cookie,token');
            header('Access-Control-Allow-Credentials:true');

            if($_SERVER['REQUEST_METHOD'] == 'OPTIONS'){
                exit;
            }
        }
    }

}