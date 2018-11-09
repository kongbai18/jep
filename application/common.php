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
use think\Request;

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

/*
     * 创建毫秒级订单号
     */
function create_unique() {
    list($usec, $sec) = explode(" ", microtime());
    $usec = substr(str_replace('0.', '', $usec), 0 ,4);
    $str  = rand(10,99);
    return date("YmdHis").$usec.$str;
}

/**
 * 获取当前域名及根路径
 * @return string
 */
function base_url() {
    $request = Request::instance();
    $subDir = str_replace('\\', '/', dirname($request->server('PHP_SELF')));
    return $request->scheme() . '://' . $request->host() . $subDir . ($subDir === '/' ? '' : '/');
}