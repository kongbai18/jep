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

Route::post('platformmgmt/v1/login','platformmgmt/login/index');
Route::get('platformmgmt/v1/logout','platformmgmt/login/logout');
Route::post('platformmgmt/v1/upload','platformmgmt/image/upload');
Route::post('platformmgmt/v1/addSpec','platformmgmt/spec/addSpec');
Route::post('platformmgmt/v1/addSpecVal','platformmgmt/image/addSpecVal');
Route::get('platformmgmt/v1/region','platformmgmt/region/index');

Route::rule('platformmgmt/v1/logout','platformmgmt/login/logout','OPTIONS');
Route::rule('platformmgmt/v1/upload','platformmgmt/image/upload','OPTIONS');
Route::rule('platformmgmt/v1/addSpec','platformmgmt/spec/addSpec','OPTIONS');
Route::rule('platformmgmt/v1/addSpecVal','platformmgmt/image/addSpecVal','OPTIONS');
Route::rule('platformmgmt/v1/region','platformmgmt/region/index','OPTIONS');
