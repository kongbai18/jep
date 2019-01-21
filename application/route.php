<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

use think\Route;
//后端
Route::resource('platformmgmt/v1/role','platformmgmt/role',['only'=>['index','read','save','edit','delete']]);
Route::resource('platformmgmt/v1/admin','platformmgmt/admin',['only'=>['index','read','save','edit','delete']]);
Route::resource('platformmgmt/v1/permission','platformmgmt/permission',['only'=>['index','read','save','edit','delete']]);
Route::resource('platformmgmt/v1/delivery','platformmgmt/delivery',['only'=>['index','read','save','edit','delete']]);
Route::resource('platformmgmt/v1/article','platformmgmt/article',['only'=>['index','read','save','delete']]);
Route::resource('platformmgmt/v1/articlecate','platformmgmt/articlecate',['only'=>['index','read','save','delete']]);
Route::resource('platformmgmt/v1/goods','platformmgmt/goods',['only'=>['index','read','save','delete']]);
Route::resource('platformmgmt/v1/furniture','platformmgmt/furniture',['only'=>['index','read','save','update','delete']]);
Route::resource('platformmgmt/v1/furnitureattr','platformmgmt/furnitureattr',['only'=>['read','save','delete']]);
Route::resource('platformmgmt/v1/category','platformmgmt/category',['only'=>['index','read','save','edit','delete']]);
Route::resource('platformmgmt/v1/brand','platformmgmt/brand',['only'=>['index','read','save','update','delete']]);
Route::resource('platformmgmt/v1/halfcustom','platformmgmt/halfcustom',['only'=>['index','read','save','update','delete']]);
Route::resource('platformmgmt/v1/halfcustomquote','platformmgmt/halfcustomquote',['only'=>['index','read','save','update','delete']]);
Route::resource('platformmgmt/v1/model','platformmgmt/model',['only'=>['index','read','save','update','delete']]);
Route::resource('platformmgmt/v1/modelparameter','platformmgmt/modelparameter',['only'=>['read','save']]);
Route::resource('platformmgmt/v1/customquote','platformmgmt/customquote',['only'=>['index','save','delete']]);
Route::resource('platformmgmt/v1/carousel','platformmgmt/carousel',['only'=>['index','read','save','update','delete']]);
Route::resource('platformmgmt/v1/theme','platformmgmt/theme',['only'=>['index','read','save','update','delete']]);

Route::post('platformmgmt/v1/login','platformmgmt/login/index');
Route::get('platformmgmt/v1/logout','platformmgmt/login/logout');
Route::post('platformmgmt/v1/upload','platformmgmt/image/upload');
Route::post('platformmgmt/v1/addSpec','platformmgmt/spec/addSpec');
Route::post('platformmgmt/v1/addSpecVal','platformmgmt/image/addSpecVal');
Route::get('platformmgmt/v1/region','platformmgmt/region/index');
Route::post('platformmgmt/v1/setStatus','platformmgmt/admin/setStatus');
Route::get('platformmgmt/v1/getAdminData','platformmgmt/login/getAdminData');
Route::get('platformmgmt/v1/getMenu','platformmgmt/login/getMenu');
Route::post('platformmgmt/v1/permission/getChild','platformmgmt/permission/getChild');
Route::post('platformmgmt/v1/category/getChild','platformmgmt/category/getChild');
Route::post('platformmgmt/v1/article/edit','platformmgmt/article/edit');
Route::post('platformmgmt/v1/goods/edit','platformmgmt/goods/edit');
Route::post('platformmgmt/v1/getAllGoods','platformmgmt/goods/getAllGoods');
Route::post('platformmgmt/v1/deliveryList','platformmgmt/order/deliveryList');
Route::post('platformmgmt/v1/receiptList','platformmgmt/order/receiptList');
Route::post('platformmgmt/v1/cancelList','platformmgmt/order/cancelList');
Route::post('platformmgmt/v1/completeList','platformmgmt/order/completeList');
Route::post('platformmgmt/v1/returnList','platformmgmt/order/returnList');
Route::post('platformmgmt/v1/payList','platformmgmt/order/payList');
Route::post('platformmgmt/v1/detail','platformmgmt/order/detail');
Route::post('platformmgmt/v1/order','platformmgmt/order/index');
Route::post('platformmgmt/v1/changePayPrice','platformmgmt/order/changePayPrice');
Route::post('platformmgmt/v1/isLogin','platformmgmt/login/isLogin');

//客户端
Route::resource('index/v1/cart','index/cart',['only'=>['index','read','save','update','delete']]);
Route::resource('index/v1/article','index/article',['only'=>['index','read']]);
Route::resource('index/v1/goods','index/goods',['only'=>['read']]);
Route::resource('index/v1/address','index/address',['only'=>['index','read','save','update','delete']]);
Route::resource('index/v1/order','index/order',['only'=>['index','save','update','delete']]);
Route::resource('index/v1/user','index/user',['only'=>['index','save']]);
Route::resource('index/v1/brand','index/brand',['only'=>['index']]);
Route::resource('index/v1/category','index/category',['only'=>['index']]);
Route::resource('index/v1/customquote','index/customquote',['only'=>['index','save','delete']]);
Route::resource('index/v1/halfcustom','index/halfcustom',['only'=>['index','read']]);
Route::resource('index/v1/halfcustomquote','index/halfcustomquote',['only'=>['index','save','delete']]);
Route::resource('index/v1/furniture','index/furniture',['only'=>['index','read']]);
Route::resource('index/v1/furnitureattr','index/furnitureattr',['only'=>['read']]);
Route::resource('index/v1/model','index/model',['only'=>['read']]);
Route::resource('index/v1/carousel','index/carousel',['only'=>['index']]);
Route::resource('index/v1/theme','index/theme',['only'=>['index']]);



Route::post('index/v1/login','index/login/login');
Route::post('index/v1/logout','index/login/logout');
Route::post('index/v1/getPayCode','index/order/getPayCode');
Route::post('index/v1/getWxpayStatus','index/order/getWxpayStatus');
Route::post('index/v1/getEmailCode','index/user/getEmailCode');
Route::post('index/v1/deliveryList','index/order/deliveryList');
Route::post('index/v1/receiptList','index/order/receiptList');
Route::post('index/v1/payList','index/order/payList');
Route::post('index/v1/completeList','index/order/completeList');
Route::post('index/v1/returnList','index/order/returnList');
Route::post('index/v1/confirmOrder','index/order/confirmOrder');
Route::post('index/v1/cancelBack','index/order/cancelBack');
Route::post('index/v1/goods','index/goods/index');
Route::post('index/v1/islog','index/login/islog');
Route::post('index/v1/editpass','index/user/editpass');
Route::post('index/v1/drawback','index/order/drawback');
Route::post('index/v1/wxlogin','index/wxlogin/login');
Route::post('index/v1/getjsapi','index/order/getJsApi');

