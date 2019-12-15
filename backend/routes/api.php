<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
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

Route::middleware(['auth:api'])->get('/user',"UserController@info")->name('users.info');
Route::get('test', 'OrderController@index')->name('soft.test');
Route::middleware(['auth:api', 'role'])->group(function(){
    Route::get('orders/export', 'OrderController@export')->name('orders.export');  // 导出数据
    Route::delete('orders/delete', 'OrderController@deleteAll')->name('orders.deleteAll');  // 使用json的方式来发送数据
    Route::post('orders/import', 'OrderController@import')->name('orders.import');  // 导入数据
    Route::apiResource('orders', 'OrderController');

    Route::post('logout', 'Auth\LoginController@logout')->name('users.logout');
    Route::post('/users/modify', 'UserController@modify' )->name('users.modify');
    Route::post('/users/{id}/reset', 'UserController@reset')->name('users.reset');
    Route::post('/users/uploadAvatar', 'UserController@uploadAvatar')->name('users.uploadAvatar');
    Route::get('users/export', 'UserController@export')->name('users.export');   // 导出数据
    Route::get('users/download', 'UserController@download')->name('users.download');  // 下载模板
    Route::post('users/delete', 'UserController@deleteAll')->name('users.delete');
    Route::post('users/import', 'UserController@import')->name('users.import');
    Route::apiResource('users', 'UserController');

    Route::get('getRoles', 'RoleController@getRoles')->name('role.get');
    Route::apiResource('roles', 'RoleController');

    // 功能组管理
    Route::get('/permissions/info', 'PermissionController@info')->name('permissions.info');
    Route::post('/permissions/addGroup', 'PermissionController@addGroup')->name('permissions.addGroup');
    Route::post('/permissions/getGroup', 'PermissionController@getGroup')->name('permissions.getGroup');
    Route::post('/permissions/delete', 'PermissionController@deleteAll')->name('permissions.deleteAll') ;
//    Route::post('/permissions/getPermissionByTree', 'PermissionController@getPermissionByTree')->name('Permission.getPermissionByTree');
    Route::apiResource('permissions', 'PermissionController');

    Route::get('logs/show', 'LogController@show')->name('logs.show'); // 操作日志
    Route::get('logs/index', 'LogController@index')->name('logs.index');  // 登录日志

    // 聊天室功能
    Route::post('/chat', function(){
        $msg = request()->only(['name', 'time', 'timezone', 'content']);
        broadcast(new \App\Events\Chat($msg))->toOthers();
    })->name('chat.index');

    Route::post('/kefu', function(){
        $msg = request()->only(['sendName', 'receiveName', 'time', 'timezone', 'content']);
        broadcast(new \App\Events\CustomerService($msg))->toOthers();
    })->name('chat.kefu');

});
Route::get('refresh', "Auth\LoginController@refresh")->name('users.refresh');
Route::post('login', 'Auth\LoginController@login')->name('users.login');






   //
