<?php

namespace App\Http\Controllers\Admin;


use App\Events\ThreeLogin;
use Illuminate\Http\Request;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;
use Laravel\Socialite\Facades\Socialite;

class OauthController extends Controller
{
    public $github_id = '52c2e3cc172fee5bccd7';
    public $github_secret = 'fa5a77a1ddbea1728fc1c33bb53eef6e8b1e5757';
    public $github_callback = 'http://localhost:8006/oauth/github/callback';

    public $gitee_id = '9fb826c130d2c709f579a07ac75a0961055fdb756e8c2107202f50bb634deb88';
    public $gitee_secret = 'bdaeab8f1427802f5f30b4e3b524ec513ea143648b1d5c54b5dae8caa7865489';
    public $gitee_callback = "https://lv6.halian.net/api/admin/oauth/gitee";
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     public $uuid = null;

    public function test1()
    {
       global $uuid;
       $uuid = request('uuid');
       $uuid = $uuid;

    }

    public function test2()
    {
        dd($this->uuid);
    }

    public function redirectToProvider()
    {
        Cache::set('github_url', request('address'));
        return Socialite::driver('github')->stateless()->redirect();
    }

    public function getUserInfoByGithub()
    {
        $result = Socialite::driver('github')->stateless()->user();
        $token = $result->token;
        $user = $result->user;
        Cache::set($token, $user['id']);
        $url = Cache::get('github_url').'?token='.$token;
        Header("HTTP/1.1 303 See Other");
        header("Location: ".$url);
        exit();
    }

    public function redirectToGitee()
    {
        Cache::set('gitee_url', request('address'));
        $uuid = request('uuid');
        $redirect = $this->gitee_callback."?uuid=$uuid";
        $url = "https://gitee.com/oauth/authorize?client_id=".$this->gitee_id."&redirect_uri=".$redirect."&response_type=code";
        Header("HTTP/1.1 303 See Other");
        header("Location: $url");
        exit();
    }

    public function getUserInfoByGitee()
    {

        $code = request('code');
        $uuid = request('uuid');
//        $_SERVER['REDIRECT_QUERY_STRING'] = "code=$code";
//        $_SERVER['QUERY_STRING'] = "code=$code";
//        $_SERVER['REQUEST_URI'] = "/api/admin/oauth/gitee?code=$code";
//        request()->except('uuid');
        $client = new Client();
        // 根据code取得令牌
        $response = $client->request("POST", "https://gitee.com/oauth/token", [
            "form_params" => [
                'grant_type' => 'authorization_code',
                'code' => $code,
                'client_id' => $this->gitee_id,
                'redirect_uri' => $this->gitee_callback."?uuid=$uuid",
                'client_secret' => $this->gitee_secret
        ]]);
        $result = json_decode($response->getBody()->getContents(), true);
        $access_token = $result['access_token'];
        // 根据令牌取得个人信息，并存储于缓存
        $response = $client->request("GET", "https://gitee.com/api/v5/user?access_token=$access_token");
        $result = json_decode($response->getBody()->getContents(), true);
        Cache::set("gitee_$access_token", $result['id']);
        Header("HTTP/1.1 303 See Other");
        broadcast(new ThreeLogin('gitee', $uuid));
        $url = Cache::get('gitee_url')."?token=$access_token";
        header("Location: ".$url);
        exit();
    }
    
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
