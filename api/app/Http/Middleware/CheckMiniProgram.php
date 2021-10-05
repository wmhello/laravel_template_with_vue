<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Support\Facades\Cache;

class CheckMiniProgram
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $auth = $request->header('Authorization');
        $arrTemp = explode(' ', $auth);
        if (count($arrTemp)===2 && $arrTemp[0] === 'Bearer'){
            // 根据令牌获取openid，然后去查看是否有该用户
            $token = $arrTemp[1];
            $openId = $token;
            // 用户没有注册，也许是使用postman等直接调用的
            $member = User::where('open_id', $openId)->first();
            if (!$member){
                return response()->json( [
                    'msg' => '没有找到指定的内容，请用微信登录',
                    'status' => 'error',
                    'status_code' => 400
                ], 400);
            }
            return $next($request);
        } else {
            return response()->json( [
                'msg' => '指定的令牌不对',
                'status' => 'error',
                'status_code' => 400
            ], 400);
        }

    }
}
