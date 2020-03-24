<?php

namespace App\Http\Controllers\Auth;

use App\Events\UserLogin;
use App\Events\UserLogout;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Tool;
use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
   use Tool;
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

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
      // 1. 获取前端发来的用户名和密码
           $email = $request->input('email');
           $password = $request->input('password');
        // 2. 尝试使用用户名和密码进行登录
          if (Auth::attempt(['email' => $email, 'password'=> $password ])){
              // 3. 登录成功后，进行令牌发送
              $user = Auth::user();
              event( new UserLogin($user));
              DB::table('oauth_access_tokens')->where('user_id', $user->id)->update(['revoked' => 1]);

               return $this->proxy($email, $password);

          } else {
              //4. 不成功，则提示用户名或者密码出错
              return $this->errorWithInfo('用户名或者密码出错，请重新登录', 401);
          }

   }

    public function logout()
    {
//        1. 获取现在登录的用户信息

            $user = Auth::guard('api')->user();
            event(new UserLogout($user));
            $accessToken = $user->token();
//        2. 让刷新令牌失效
            DB::table('oauth_refresh_tokens')
                ->where('access_token_id', $accessToken->id)
                ->update([
                   'revoked' => true,
                ]);
            cookie()->forget('refresh_token');
//        3. 让活动令牌失效
          $accessToken->revoke();
//

        return $this->successWithInfo('退出成功');

   }

    public function refresh(Request $request)
    {
        $refreshToken = $request->input('refreshToken', '');
        if (empty($refreshToken)) {
            $refreshToken = $request->cookie('refreshToken');
        }
        $data = [
            'grant_type' => 'refresh_token',
            'refresh_token' => $refreshToken,
            'client_id' =>  env('PASSPORT_CLIENT_ID'),
            'client_secret' => env('PASSPORT_CLIENT_SECRET'),
            'scope' => '',
        ];
        return $this->token($data);

   }
   
   protected function proxy($username, $password){
       $data = [
           'grant_type' => 'password',
           'client_id' => env('PASSPORT_CLIENT_ID'),
           'client_secret' => env('PASSPORT_CLIENT_SECRET'),
           'username' => $username,
           'password' => $password,
           'scope' => '',
       ];

       return $this->token($data);

   }

   protected function token($data = []){

       $http = new Client();
       $web = $_SERVER['HTTP_HOST'];
       $url = 'http://'.$web.'/oauth/token';

       $result = $http->post($url, [
           'form_params' => $data
       ]);
       $result = json_decode((string) $result->getBody(), true);
       return response()->json([
           'token' => $result['access_token'],
           'express_in' => $result['expires_in'],
           'refresh_token' => $result['refresh_token'],
           'status' => 'success',
           'status_code' => 200
       ], 200)->cookie('refreshToken', $result['refresh_token'], 43200);
   }

}
