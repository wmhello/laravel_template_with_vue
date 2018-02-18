<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/disk1', 'DiskController@disk1');
Route::get('/disk/copy', 'DiskController@copy');
Route::get('/disk/move', 'DiskController@move');
Route::get('/file/update', 'FileController@update');
Route::get('/file/updateXls', 'FileController@updateXls');
Route::get('/file/files', 'FileController@files');
Route::post('/file/store', 'FileController@store');
Route::post('/file/storeXls', 'FileController@storeXls');

Route::any('/wechat', 'WechatController@serve');
Route::any('/menu', 'MenuController@menu');
Route::any('/menu/all', 'MenuController@all');

// 微信网页认证页面
Route::middleware(['web', 'wechat.oauth'])->group(function () {
});
