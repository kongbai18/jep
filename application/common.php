<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
/**
 * 通用化API接口数据输出
 * @param int $status 业务状态码
 * @param string $message 信息提示
 * @param [] $data  数据
 * @param int $httpCode http状态码
 * @return array
 */
function show($status, $message, $data=[], $httpCode=200) {

    $data = [
        'status' => $status,
        'message' => $message,
        'data' => $data,
    ];

    return json($data, $httpCode);
}

/**
 * 将IP转换为十进制数
 * @param $ip 传入IP
 * @return float|int 传出10进制数
 */
function ipton($ip)
{
    $ip_arr=explode('.',$ip);//分隔ip段
    $ipstr = '';
    foreach ($ip_arr as $value)
    {
        $iphex=dechex($value);//将每段ip转换成16进制
        if(strlen($iphex)<2)//255的16进制表示是ff，所以每段ip的16进制长度不会超过2
        {
            $iphex='0'.$iphex;//如果转换后的16进制数长度小于2，在其前面加一个0
            //没有长度为2，且第一位是0的16进制表示，这是为了在将数字转换成ip时，好处理
        }
        $ipstr.=$iphex;//将四段IP的16进制数连接起来，得到一个16进制字符串，长度为8
    }
    return hexdec($ipstr);//将16进制字符串转换成10进制，得到ip的数字表示
}

/**
 * 将数字转回IP
 * @param $n 传入数字
 * @return string 传出IP
 */
function ntoip($n)
{
    $iphex=dechex($n);//将10进制数字转换成16进制
    $len=strlen($iphex);//得到16进制字符串的长度
    if(strlen($iphex)<8)
    {
        $iphex='0'.$iphex;//如果长度小于8，在最前面加0
        $len=strlen($iphex); //重新得到16进制字符串的长度
    }
    //这是因为ipton函数得到的16进制字符串，如果第一位为0，在转换成数字后，是不会显示的
    //所以，如果长度小于8，肯定要把第一位的0加上去
    //为什么一定是第一位的0呢，因为在ipton函数中，后面各段加的'0'都在中间，转换成数字后，不会消失
    for($i=0,$j=0;$j<$len;$i=$i+1,$j=$j+2)
    {//循环截取16进制字符串，每次截取2个长度
        $ippart=substr($iphex,$j,2);//得到每段IP所对应的16进制数
        $fipart=substr($ippart,0,1);//截取16进制数的第一位
        if($fipart=='0')
        {//如果第一位为0，说明原数只有1位
            $ippart=substr($ippart,1,1);//将0截取掉
        }
        $ip[]=hexdec($ippart);//将每段16进制数转换成对应的10进制数，即IP各段的值
    }
    $ip = array_reverse($ip);

    return implode('.', $ip);//连接各段，返回原IP值
}