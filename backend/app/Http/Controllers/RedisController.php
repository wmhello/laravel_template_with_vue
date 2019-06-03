<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class RedisController extends Controller
{

    public function testRedis()
    {//
//        Redis::set('name', 'guwenjie');
//        $values = Redis::get('name');
//        //输出："guwenjie"
//        //加一个小例子比如网站首页某个人员或者某条新闻日访问量特别高，可以存储进redis，减轻内存压力

        if (Redis::exists('user_2')) {
            $values = Redis::get('user_2');
        } else {
            $values = User::find(2);//此处为了测试你可以将id=1200改为另一个id
            Redis::set('user_2', $values);
        }
        dump($values);
    }
}
