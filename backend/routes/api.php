<?php

use Illuminate\Http\Request;
use \Illuminate\Http\Response;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', 'UserController@getUserInfo')->name('admin.userInfo');
Route::post('/login', 'Auth\LoginController@login')->name('login.login');
Route::post('/token/refresh', 'Auth\LoginController@refresh')->name('login.refresh');
Route::post('/logout', 'Auth\LoginController@logout')->name('login.logout');
Route::post('/test', 'SmsController@send')->name('soft.test');
Route::middleware('auth:api')->group(function() {
    // 用户管理
    Route::Resource('admin', 'UserController', ['only' => ['index', 'show', 'store', 'update', 'destroy']]);
    Route::post('/admin/modify', 'UserController@modify' )->name('admin.modify');
    Route::post('/admin/{id}/reset', 'UserController@reset')->name('admin.reset');
    Route::post('/admin/uploadAvatar', 'UserController@uploadAvatar')->name('admin.uploadAvatar');
    Route::post('/admin/upload', 'UserController@upload')->name('admin.upload');
    Route::post('/admin/export', 'UserController@export')->name('admin.export');
    Route::post('/admin/exportAll', 'UserController@exportAll')->name('admin.exportAll');
    Route::post('/admin/deleteAll', 'UserController@deleteAll')->name('admin.deleteAll');

    // 角色管理
    Route::Resource('role', 'RoleController', ['only' => ['index', 'show', 'store', 'update', 'destroy']]);
    Route::get('getRoles', 'RoleController@getRoles')->name('role.get');

    // 其他支持API
    Route::get('/getSession', 'SessionController@getSession')->name('session.get'); // 获取所有学期
    Route::get('/getDefaultSession', 'SessionController@getDefaultSession')->name('session.getDefault'); //获得当前学期
    Route::get('/getClassNumByGrade', 'SessionController@getClassNumByGrade')->name('session.getClassNum'); // 根据年级获取最大班级数


    // 学期管理
    Route::Resource('session', 'SessionController', ['only' => ['index', 'show', 'store', 'update', 'destroy']]);
    Route::post('/session/upload', 'SessionController@upload')->name('session.upload');

    // 程序功能管理
    Route::Resource('permissions', 'PermissionController', ['only' => ['index', 'show', 'store', 'update', 'destroy']]);
    Route::post('/permissions/addGroup', 'PermissionController@addGroup')->name('permissions.addGroup');
    Route::post('/permissions/getGroup', 'PermissionController@getGroup')->name('permissions.getGroup');
    Route::post('/permissions/deleteAll', 'PermissionController@deleteAll')->name('permissions.deleteAll') ;
    Route::post('/permissions/getPermissionByTree', 'PermissionController@getPermissionByTree')->name('Permission.getPermissionByTree');

    // 手机信息管理
    Route::post('/sms/send', 'SmsController@send')->name('sms.send');
    Route::post('/sms/verify', 'SmsController@verify')->name('sms.verify');

});
