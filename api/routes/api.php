<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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


// 后台管理系统 不需要登陆
Route::group(['prefix' => 'admin', 'namespace' => 'Admin'], function(){
    // 不需要登陆
    Route::post('login', 'LoginController@login')->name('admin.login');
    Route::post('login/phone', 'LoginController@loginByPhone')->name('login.phone'); // 手机登陆
    Route::post('logout', 'LoginController@logout')->name('admin.logout');
    Route::post('refresh', 'LoginController@refresh')->name('admin.refresh');
    Route::post('cp', 'LoginController@captcha')->name('admin.captcha');  // 验证码
    Route::get('test', 'LoginController@test')->name('admin.test');
    Route::post('sms/send', 'SmsController@send_code')->name('sms.send_code');
    Route::post('sms/verify', 'SmsController@verify_code')->name('sms.verify_code');
    Route::get('oauth/github', 'OauthController@getUserInfoByGithub')->name('oauth.github');
    Route::get('login/github', 'OauthController@redirectToProvider')->name('login.github');
    Route::get('login/gitee', 'OauthController@redirectToGitee')->name('login.gitee');
    Route::get('oauth/gitee', 'OauthController@getUserInfoByGitee')->name('login.gitee');
    Route::get('oauth/test1', 'OauthController@test1')->name('login.test1');
    Route::get('oauth/test2', 'OauthController@test2')->name('login.test2');
    Route::post('user/bind', "LoginController@bind")->name('login.bind');

});
// 需要登录，但不需要角色认证的接口，只要登录都可以调用的接口
Route::middleware(['auth:admin'])->prefix('admin')->namespace('Admin')->group(function(){
  Route::get('tables/list', 'TableController@getAllTable')->name("tables.list");
  Route::get('table_configs/columns', 'TableConfigController@getColumnByTable')->name("tables.column");
});


Route::middleware(['auth:admin', 'role'])->prefix('admin')->namespace('Admin')->group(function(){
//  需要登陆
   Route::get('me', 'LoginController@me')->name('admins.me');
   // 模块和权限管理
   Route::apiResource('modules', 'ModuleController');
   // 角色管理
   Route::apiResource('roles', 'RoleController');
   // 管理员管理
    Route::post('admins/export', "AdminController@export")->name("admins.export");
    Route::post('admins/import', "AdminController@import")->name("admins.import");
    Route::post('admins/modify', "AdminController@modify")->name("admins.modify");
    Route::apiResource('admins', 'AdminController');
    // 数据图像等上传接口
    Route::post('medias', "MediaController@store")->name("medias.store");
    // 微信的配置
    Route::apiResource('wechat', "WechatController", [
       'only' => ['index', "update"]
    ]);
    // 文章管理
    Route::apiResource('article_categories', 'ArticleCategoryController');
    Route::apiResource('articles', 'ArticleController');
    // 轮播图
    Route::apiResource('carousels', 'CarouselController');


    //  系统工具  代码生成
    Route::post('tables/export', 'TableController@download')->name("tables.export");
    Route::apiResource('tables', 'TableController');
    Route::apiResource('codes', 'CodeController');
    Route::apiResource('code_configs', 'CodeConfigController');
    Route::apiResource('code_snippets', 'CodeSnippetController');
    // 应用程序的业务接口

});


// 微信小程序登陆
Route::group(['prefix' => '/mp', 'namespace' => 'MP'], function () {
    Route::post('/user/code', 'LoginController@getCode');  // 获得code， 用于之后的信息解码
    Route::post('/user/info', 'LoginController@getInfo');  // 获得解码后的个人信息
    Route::post('/user/store', 'LoginController@saveUserInfo');  // 从前端保存个人信息
    Route::post('/user/phone', 'LoginController@getPhone');   // 获得手机号码
});


Route::middleware(['mp'])->prefix('mp')->namespace('MP')->group(function(){
// api/admin  api/mp

});


// 微信公众号相关接口
Route::group(['prefix' => '/wx', 'namespace' => 'Wx'], function () {
Route::any('wechat', 'WxController@serve');  // 公众号入口
Route::post('getId', 'WxController@getOpenId');
Route::get('menu', 'WxController@createMenu'); // 生成菜单
Route::get('customMenu', 'WxController@customMenu'); // 生成自定义菜单


// 支付情况
Route::post('pay_callback', 'WxController@pay_callback');
Route::get('pay', 'WxController@pay');
Route::get('spa-pay', 'WxController@spaPay');

// SPA网页授权
Route::get('/start_oauth', 'WxController@start_oauth');
Route::get('/oauth', 'WxController@oauth');
// SPA中的jssdk
Route::post('/jssdk/config', 'WxController@config');
});
Route::middleware(['auth:admin','role'])->prefix('admin')->namespace('Admin')->group(function(){
    Route::apiResource('wechats', 'WechatController');
});
Route::middleware(['auth:admin','role'])->prefix('admin')->namespace('Admin')->group(function(){
    Route::apiResource('table_configs', 'TableConfigController');
});
