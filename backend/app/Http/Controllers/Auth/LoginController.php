<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Proxy\TokenProxy;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';
    protected $proxy;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(TokenProxy $proxy)
    {
        $this->middleware('guest')->except('logout');
        $this->proxy = $proxy;
    }

    /**
     * @api {post} /api/login 用户登陆
     * @apiGroup login
     *
     * @apiParam {string} email 用户email
     * @apiParam {string} password 用户密码
     *
     * @apiSuccessExample 登陆成功
     * HTTP/1.1 200 OK
     * {
     * "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJS",
     * "expires_in": 900 // 过期时间
     * }
     *
     * @apiErrorExample 用户身份验证失败
     * HTTP/1.1 421 用户名或者密码输入错误
     * {
     * "status": "login error",
     * "status_code": 421,
     * "message": "Credentials not match"
     * }
     */

    public function login()
    {
        //$this->validateLogin(request());
        return $this->proxy->login(request('email'),request('password'));
    }

    /**
     * @api {post} /api/logout 注销用户登陆
     * @apiGroup login
     *
     *
     * @apiSuccessExample 注销成功
     * HTTP/1.1 200 OK
     * {
     * "status": "success",
     * "status_code": 200,
     * "message": "logout success"
     * }
     *
     */
    public function logout()
    {
        return $this->proxy->logout();
    }
    /**
     * @api {post} /api/token/refresh Token刷新
     * @apiGroup login
     *
     *
     * @apiSuccessExample 刷新成功
     * HTTP/1.1 200 OK
     * {
     * "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJS",
     * "expires_in": 900 // 过期时间
     * }
     *
     * @apiErrorExample 刷新失败
     * HTTP/1.1 401 未认证
     * {
     * "status": "login error",
     * "status_code": 401,
     * "message": "Credentials not match"
     * }
     */
    public function refresh()
    {
        return $this->proxy->refresh();
    }
}