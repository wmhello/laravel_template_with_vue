<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class WechatController extends Controller
{
    //
    /**
     * 处理微信的请求消息
     *
     * @return string
     */
    public function serve()
    {
        $app = app('wechat.official_account');
        $app->server->push(function($message){
            return "欢迎关注 overtrue！";
        });

        return $app->server->serve();
        // 3.0版本使用
/*        $wechat = app('wechat');
        $userApi = $wechat->user;
        $wechat->server->setMessageHandler(function($message) use ($userApi) {
            switch ($message->MsgType) {
                case 'event':
                    switch ($message->Event){
                        case 'subscrible':
                            return '你好，欢迎关注！！';
                            break;
                        case 'CLICK':
                            if ($message->EventKey == 'YOU_CLICK_ME'){
                                return '你点击了我';
                            }
                            if ($message->EventKey == 'YOU_VOTE_ME'){
                                return '谢谢你的点赞';
                            }
                            break;
                    }
                    break;
                case 'text':
                    return '你好' . $userApi->get($message->FromUserName)->nickname;
                    break;
                case 'image':
                    return '收到图片消息';
                    break;
                case 'voice':
                    return '收到语音消息';
                    break;
                case 'video':
                    return '收到视频消息';
                    break;
                case 'location':
                    return '收到坐标消息';
                    break;
                case 'link':
                    return '收到链接消息';
                    break;
                // ... 其它消息
                default:
                    return '收到其它消息';
                    break;
            }
        });
        return $wechat->server->serve();*/
    }
}
