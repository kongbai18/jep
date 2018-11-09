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
Route::resource('platformmgmt/v1/goods','platformmgmt/goods',['only'=>['index','read','save','delete']]);
Route::resource('platformmgmt/v1/furniture','platformmgmt/furniture',['only'=>['index','read','save','edit','delete']]);
Route::resource('platformmgmt/v1/category','platformmgmt/category',['only'=>['index','read','save','edit','delete']]);
Route::resource('platformmgmt/v1/brand','platformmgmt/brand',['only'=>['index','read','save','update','delete']]);

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

//客户端
Route::resource('index/v1/cart','index/cart',['only'=>['index','read','save','update','delete']]);
Route::resource('index/v1/article','index/article',['only'=>['index','read']]);
Route::resource('index/v1/goods','index/goods',['only'=>['index','read']]);


