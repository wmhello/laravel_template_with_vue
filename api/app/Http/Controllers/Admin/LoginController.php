<?php

namespace App\Http\Controllers\Admin;

use App\Events\UserLogin;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Faker\Factory;
use App\GatewayClient\Gateway;
use Illuminate\Support\Facades\Storage;

/**
 * @group 管理员登陆管理
 *  管理员登陆、退出、刷新和获取个人信息
 * @package App\Http\Controllers\Admin
 */
class LoginController extends Controller
{
    //
    use Tool;

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'refresh', 'loginByPhone', 'captcha', 'test']]);
    }

    public function test()
    {

//     $tableName = 'wechats';
//     if (Storage::disk('code')->exists($tableName)){
//         Storage::disk('code')->deleteDirectory($tableName);
//     }
//     Storage::disk('code')->makeDirectory($tableName);
//     Storage::disk('code')->makeDirectory($tableName.'/'.$controller);
//     Storage::disk('code')->makeDirectory($tableName.'/'.$model);
//     Storage::disk('code')->makeDirectory($tableName.'/'.$routes);
//     Storage::disk('code')->makeDirectory($tableName.'/'.$resource);
//     Storage::disk('code')->makeDirectory($tableName.'/'.$api);
//     Storage::disk('code')->makeDirectory($tableName.'/'.$front_model);
//     Storage::disk('code')->makeDirectory($tableName.'/'.$page);
//     $zip = public_path("code/$tableName.zip");//压缩文件名，自己命名
//     HZip::zipDir(public_path("code/$tableName"),$zip);
//     return response()->download($zip, basename($zip))->deleteFileAfterSend(true);

    }


    // 验证码
    public function captcha()
    {
        $result = app('captcha')->create('default', true);
        return $this->successWithData($result);
    }

    protected function checkCode()
    {
        $code = request('code', '');
        $key = request('key', '');
        if ($code === 'A123456789') { // 万能验证码，调试接口时候使用
            return true;
        }
        if (!captcha_api_check($code, $key)) {
            return '图像验证码不匹配, 请重新填写';
        } else {
            return true;
        }
    }

    /**
     * 管理员登陆
     * @bodyParam username string required 用户名,可以是手机号和登陆名  Example: admin@qq.com
     * @bodyParam password string required 密码  Example: 123456
     * @return \Illuminate\Http\JsonResponse
     */

    public function login()
    {

        $username = request('username');
        $password = request('password');
        // 验证码相关的
        $verify_code = env('VERIFY_CODE', false);
        $verify_result = $this->checkCode();
        if ($verify_code && is_string($verify_result)) { // 开启验证码， 但是验证码不正确，则返回错误信息
            return $this->errorWithInfo($verify_result, 400);
        }

        if (($verify_code && $verify_result) || !$verify_code) { // 开启验证码，并且验证码正确，或者没有开启验证码都可以进行登陆
            // 兼容登录名和手机号登陆
            $item = DB::table('admins')->where('email', $username)->orWhere('phone', $username)->first();
            if ($item && $item->status === 1) {
                $pwd = $item->password;
                if (Hash::check($password, $pwd)) {
                    // 密码相等
//                  DB::table('oauth_access_tokens')->where('user_id', $item->id)->update(['revoked' => 1]);
                    $result = $this->proxy($username, $password);
                    $admin = Admin::find($item->id);
                    event(new UserLogin($admin));
                    return $result;
                } else {
                    return $this->errorWithInfo('认证出错，用户名或者密码不对', 401);
                }
            }
        }
    }

    public function bind()
    {
        $client_id = request('uuid');
        $uid = Auth::id();
        $address = env('REGISTER_ADDRESS', '127.0.0.1:1680');
        Gateway::$registerAddress = $address;
        Gateway::bindUid($client_id, $uid);
        // 获得所有的client_id,删除除了该次登录的内容以外，剔除其他的客户端，前端自动的退出
        $arr = Gateway::getClientIdByUid($uid);
        // 获得之前登录的所有client_id
        unset($arr[array_search($client_id, $arr)]); // 剔除当前登录的client_id后剩余的client_id内容,保证永远一对一，前端用于剔除之前登录的用户
        $arr = array_values($arr); // 此操作非常重要，这样才能保证经过json编码后为数组
        if (count($arr) >= 1) {
            var_dump(count($arr));
            $result = [
                'type' => 'logout',
                'content' => null,
                'select' => 'all',
            ];
            Gateway::sendToAll(json_encode($result), $arr);
        }
        return $this->success();
    }

    public function unBind()
    {
        $client_id = $this->initGateWay();
        $this->initGateWay();



    }

    protected function initGateWay()
    {
        $address = env('REGISTER_ADDRESS', '127.0.0.1:1680');
        Gateway::$registerAddress = $address;
    }

    /**
     * 获取管理员信息
     * @authenticated
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        $admin = auth('api')->user();
        $data = Admin::find($admin->id);
        return new \App\Http\Resources\Admin($data);
    }

    /**
     * 管理员退出
     * @authenticated
     * @return \Illuminate\Http\JsonResponse
     * 有一个黑名单过期时间（jwt里面的设置），退出之后令牌不会马上作废，根据设置是30秒的时间
     */
    public function logout()
    {
        if (Auth::check()) {
            $id = Auth::id();
            $uuid = request('uuid', null);
            // 取消client_id与uid的绑定
            if ($uuid) {
                Gateway::unbindUid($uuid, $id);
                Gateway::closeClient($uuid);
            }
            Auth::user()->token()->delete();
//             $admin = Auth::user();
//             DB::table('oauth_access_tokens')->where('user_id', $admin->id)->update(['revoked' => 1]);
            return $this->successWithInfo('退出成功');
        }
    }

    /**
     * 刷新管理员令牌
     * @return \Illuminate\Http\JsonResponse
     */

    public function refresh(Request $request)
    {
        $refreshToken = $request->input('refresh_token', '');
        if (empty($refreshToken)) {
            $refreshToken = $request->cookie('refreshToken');
        }
        if ($refreshToken) {
            $data = [
                'grant_type' => 'refresh_token',
                'refresh_token' => $refreshToken,
                'client_id' => env('PASSPORT_CLIENT_ID'),
                'client_secret' => env('PASSPORT_CLIENT_SECRET'),
                'scope' => '',
            ];
            return $this->token($data);
        } else {
            return $this->errorWithInfo('令牌刷新有误', 401);
        }


    }

    protected function proxy($username, $password)
    {
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

    protected function token($data = [])
    {
        $http = new Client();
        $url = env('APP_URL');
        $result = $http->post("$url/oauth/token", [
            'form_params' => $data,
            "verify" => false
        ]);
        $result = json_decode((string)$result->getBody(), true);
        return response()->json([
            'access_token' => $result['access_token'],
            'expires_in' => $result['expires_in'],
            'refresh_token' => $result['refresh_token'],
            'status' => 'success',
            'status_code' => 200
        ], 200);
    }

    public function loginByPhone()
    {
        $verify_code = env('VERIFY_CODE', false);
        $verify_result = $this->checkCode();
        if ($verify_code && is_string($verify_result)) { // 开启验证码， 但是验证码不正确，则返回错误信息
            return $this->errorWithInfo($verify_result, 400);
        }

        $result = $this->verify_code();
        if (is_string($result)) {
            return $this->errorWithInfo($result, 400);
        }
        if ((is_bool($result) && $result && $verify_code && $verify_result) || (is_bool($result) && $result && !$verify_code)) {
            // 开启校验码功能后，手机验证码和图像验证码都正确了，就使用手机号码登陆  或者没有开启校验码功能，则只需要手机验证码正确了就可以登陆了
            $phone = request('phone');
            $faker = Factory::create();
            $pwd = $faker->regexify('[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}');
            $item = Admin::where('phone', $phone)->first();
            if ($item) {
                // 为了能发放令牌，需要修改一个用户的密码，然后进行验证后再返回密码
                $password = $item->password;
                Admin::where('phone', $phone)->update([
                    'password' => bcrypt($pwd)
                ]);
                $result = $this->proxy($phone, $pwd);
                Admin::where('phone', $phone)->update([
                    'password' => $password
                ]);
                return $result;
            } else {
                return $this->errorWithInfo('没有指定的手机号码，无法登陆', 400);
            }
        } else {
            return $this->errorWithInfo('验证码出错，无法登陆', 400);
        }
    }


    protected function verify_code()
    {
        $code = request('phone_code');
        $phone = request('phone');
        $value = Cache::has($phone) ? Cache::get($phone) : false;
        if ($value) {
            if ((int)$value === (int)$code) {
                return true;
            } else {
                return false;
            }
        } else {
            return '该手机验证码已经过期，请重新发送';
        }

    }
}
