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
    {
        //跨域访问的时候才会存在此字段
        $origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '';

        $allow_origin = array(
            'http://jiihomeapidec.shimentown.com',
            'http://localhost:8002'
        );

        if(in_array($origin, $allow_origin)) {
            header('Access-Control-Allow-Origin:' . $origin);
            header('Access-Control-Allow-Methods:POST,GET,PUT,DELETE,OPTIONS');
            header('Access-Control-Allow-Headers:Accept,Referer,Host,Keep-Alive,User-Agent,X-Requested-With,Cache-Control,Content-Type,Cookie,token');
            header('Access-Control-Allow-Credentials:true');

            if($_SERVER['REQUEST_METHOD'] == 'OPTIONS'){
                exit;
            }
        }
    }

}
