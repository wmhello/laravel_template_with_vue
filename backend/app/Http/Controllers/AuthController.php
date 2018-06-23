<?php

namespace App\Http\Controllers;

use App\Events\UserLogin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Overtrue\LaravelSocialite\Socialite;
use App\Models\ThreeLogin;

class AuthController extends Controller
{
    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return Response
     */

    public function redirectToProvider(Request $request)
    {
        $request->session()->put('uuid', $request->input('time')) ;
        return Socialite::driver('github')->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return Response
     */
    public function handleProviderCallback()
    {
        $user = Socialite::driver('github')->user()->toArray();
        $provider = $user['provider'];
        $id = $user['id'];
         // 查询返回的结果
        if (ThreeLogin::where('provider', $provider)->where('platform_id', $id)->first()){
           // 找到后登录
            $admin = ThreeLogin::where('provider', $provider)->where('platform_id', $id)->first();
            $admin = $admin->toArray();
            $id = $admin['user_id'];
            // 自动登录
            $userInstance = User::where('id', $id)->firstOrFail();
            Auth::login($userInstance);

            $token = $userInstance->createToken('token')->accessToken;
            event(new UserLogin());
            // 得到$token
            $data['time'] =  session('uuid') ;
            $data['token'] = $token;
            event(new \App\Events\GithubLoginSuccess($data));
        } else {
            // 没有找到

            $user['time'] =  session('uuid') ;
            event(new \App\Events\GithubLogin($user));
        }

        // $user->token;
    }
}
