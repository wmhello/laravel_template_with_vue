<?php

namespace App\Http\Controllers\Wx;

use Carbon\Carbon;
use Illuminate\Http\Request;
use EasyWeChat\Kernel\Messages\Message;
use EasyWeChat\Kernel\Messages\Image;
use EasyWeChat\Factory;
use Illuminate\Support\Facades\Cache;

class WxController extends Controller
{
    //
    protected function textHandle($msg) {
        switch ($msg) {
            case '时间':
                $data = Carbon::now();
                return $data->format('Y-m-d');
                break;
            case '卡通':
                return new Image("PNUS_gthOqh84QBcEPZh1tzAgAxZDg3_VffU1kzE9Rw");
                break;
            default:
                return '谢谢使用，请输入其它信息查询';
        }
    }

    protected  function  eventHandle($event, $message){
        switch ($event) {
            case 'subscribe':
                return '谢谢你的关注';
                break;
            case 'unsubscribe':
                return "取消关注";
                break;
            case 'CLICK':
                return $message['EventKey'];
                break;
            case 'scancode_push':

                return $message['EventKey'];
                break;
            case 'scancode_waitmsg':
                // logger($message);
                return $message['ScanCodeInfo']['ScanResult'];
                break;
            case 'pic_sysphoto':
                return $message['SendPicsInfo']['count'];
                break;
            case 'pic_photo_or_album':
                return $message['SendPicsInfo']['count'];
                break;
            case 'pic_weixin':
                return $message['SendPicsInfo']['count'];
                break;
            case 'SCAN':
                //return header("Location:http://www.baidu.com");
                return $message['EventKey'];
                break;
            default:
                return '收到事件消息';
        }
    }

    public function serve()
    {
        $app = app('wechat.official_account');

        $app->server->push(function ($message) use($app) {
            switch ($message['MsgType']) {
                case 'event':
                    return $this->eventHandle($message['Event'],$message);
                    break;
                case 'text':
                    return $this->textHandle($message['Content']);
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
                case 'file':
                    return '收到文件消息';
                // ... 其它消息
                default:
                    return '收到其它消息';
                    break;
            }

            // ...
        });
        return $app->server->serve();
    }

    public function createMenu()
    {
        $app = app('wechat.official_account');
        $buttons = [
            [
              "name" => "扫码",
              "sub_button" => [
                [
                    "type" => "scancode_waitmsg",
                    "name" => "扫码带提示",
                    "key" =>"rselfmenu_0_0"
                ],
                [
                    "type" => "scancode_push",
                    "name" => "扫码推事件",
                    "key" => "rselfmenu_0_1"
                ]
              ]
            ],
            [
                "name" => "发图",
                "sub_button" => [
                    [
                        "type"=> "pic_sysphoto",
                        "name"=> "系统拍照发图",
                        "key"=> "rselfmenu_1_0",
                    ],
                    [
                        "type"=> "pic_photo_or_album",
                        "name"=> "拍照或者相册发图",
                        "key"=> "rselfmenu_1_1"
                    ],
                    [
                        "type"=> "pic_weixin",
                        "name"=> "微信相册发图",
                        "key"=> "rselfmenu_1_2"
                    ]
                ]
            ],
            [
                "name"       => "菜单",
                "sub_button" => [
                    [
                        "type" => "view",
                        "name" => "H5页面",
                         "url" => "http://wechat.halian.net/"
                    ]
                ],
            ],
        ];
        $app->menu->create($buttons);
    }

    public function customMenu()
    {
        $app = app('wechat.official_account');
        $buttons = [
            [
              "name" => "扫码",
              "sub_button" => [
                [
                    "type" => "scancode_waitmsg",
                    "name" => "扫码带提示",
                    "key" =>"rselfmenu_0_0"
                ],
                [
                    "type" => "scancode_push",
                    "name" => "扫码推事件",
                    "key" => "rselfmenu_0_1"
                ]
              ]
            ],
            [
                "name"       => "菜单",
                "sub_button" => [
                    [
                        "type" => "view",
                        "name" => "百度",
                        "url"  => "http://www.baidu.com/"
                    ],
                    [
                        "type" => "view",
                        "name" => "业务(SPA)",
                        "url" => "http://wechat.halian.net/"
                    ]
                ],
            ],
        ];

        $matchRule = [
            "tag_id" => "100"
        ];
        $app->menu->create($buttons, $matchRule);
    }

    public function show()
    {
        $app = app('wechat.official_account');
        $oauth = $app->oauth;
        $oauth->redirect()->send();
    }

    public function callback()
    {
       $app = app('wechat.official_account');
       $oauth = $app->oauth;
     // 获取 OAuth 授权结果用户信息
        $user = $oauth->user()->toArray();
        $user_original = $user['original'];
        $token = $user['token'];
        $openId = $user['id'];
        $expireTime = Carbon::now()->addMinutes(120);
        Cache::put($token, $openId, $expireTime);
        $url = env('FRONTEND_CALLBACK')."?token=$token";
        header('location: '.$url);
        exit;
        dd($user);
        // 保存个人信息
        // 设置token与openid的缓存
        // 后端带着token跳转到前端

        return view('user',compact(['user','user_original']));
    }

    public function uploadImg()
    {
        $result = $app->material->uploadImage("d:/wmhello.mynatapp.cc/wx/public/img/kt.jpg");
//        => [
//        "media_id" => "PNUS_gthOqh84QBcEPZh1tzAgAxZDg3_VffU1kzE9Rw",
//        "url" => "http://mmbiz.qpic.cn/mmbiz_jpg/06Lwyviae1AYfW237R4p9yOmHwzvAK8hbISn3lCRgfsdAMMohSRGrZlXK3eWYJwfLNl4gjJwbvO1M5ep5tetPIA/0?wx_fmt=jpeg",
//        "item" => [],
//    ]

    }

    public function oauth1()
    {
        $config = [
            // ...
            'app_id' => config('wechat.official_account.default.app_id', 'your-app-id'),         // AppID
            'secret' => config('wechat.official_account.default.secret', 'your-app-secret'),    // AppSecret
            'token' => config('wechat.official_account.default.token', 'your-token'),           // Token
            'aes_key' => config('wechat.official_account.default.aes_key', ''),                 // EncodingAESKey
            'oauth' => [
                'scopes'   => ['snsapi_userinfo'],
                'callback' => 'http://wmhello.mymynatapp.cc/api/callback1',
            ],
        ];
        $app = Factory::officialAccount($config);
        // $oauth = $app->oauth;
        $response = $app->oauth->scopes(['snsapi_userinfo'])
            ->redirect('http://wmhello.mynatapp.cc/api/callback1');
        return $response;
    }

    public function callback1()
    {
        $config = [
            // ...
            'app_id' => config('wechat.official_account.default.app_id', 'your-app-id'),         // AppID
            'secret' => config('wechat.official_account.default.secret', 'your-app-secret'),    // AppSecret
            'token' => config('wechat.official_account.default.token', 'your-token'),           // Token
            'aes_key' => config('wechat.official_account.default.aes_key', ''),                 // EncodingAESKey
            'oauth' => [
                'scopes'   => ['snsapi_userinfo'],
                'callback' => 'http://wmhello.mymynatapp.cc/api/callback1',
            ],
        ];
        $app = Factory::officialAccount($config);
        $oauth = $app->oauth;

        // 获取 OAuth 授权结果用户信息
        $user = $oauth->user()->toArray();
        return view('jssdk', compact(['app', 'user']));
    }

    public function pay_callback()  // 支付完成后的回调
    {

        $config = config('wechat.payment.default');
        $pay = Factory::payment($config);
        return $pay->handlePaidNotify(
            function ($message, $fail) {
                logger($message);
                if ($message['result_code'] === 'FAIL') {
                    logger()->warning('WXPAY_CALLBACK', ['FAIL', $message]);
                    return true;
                } else if ($message['return_code'] === 'SUCCESS') {
                    // TODO: 你的发货逻辑
//                    [2020-02-02 03:18:43] local.DEBUG: array (
//                      'appid' => 'wxbd2f8115c5684e21',
//                      'bank_type' => 'OTHERS',
//                      'cash_fee' => '1',
//                      'fee_type' => 'CNY',
//                      'is_subscribe' => 'Y',
//                      'mch_id' => '1564743201',
//                      'nonce_str' => '5e363f8acc401',
//                      'openid' => 'ofLgTxGoQtXnjaVC5nFf14ofA0QI',
//                      'out_trade_no' => 'no_80110',
//                      'result_code' => 'SUCCESS',
//                      'return_code' => 'SUCCESS',
//                      'sign' => '79173E6161A055A776CC1A70B625BA38',
//                      'time_end' => '20200202112205',
//                      'total_fee' => '1',
//                      'trade_type' => 'JSAPI',
//                      'transaction_id' => '4200000488202002024179085143',
//                    )
                    $payId = $message['transaction_id'];  // 微信订单的流水号，对于微信支付来说是唯一

                    return true;
                }
            }
        );

    }

    public function pay(Request $request)  // 生成预支付订单
    {
//        $config = [
//    // 必要配置
//            'app_id'             => env('WECHAT_PAYMENT_APPID', ''),
//            'mch_id'             => env('WECHAT_PAYMENT_MCH_ID', 'your-mch-id'),
//            'key'                => env('WECHAT_PAYMENT_KEY', 'key-for-signature'),  // API 密钥
//
//            // 如需使用敏感接口（如退款、发送红包等）需要配置 API 证书路径(登录商户平台下载 API 证书)
//             'cert_path'          => env('WECHAT_PAYMENT_CERT_PATH', 'path/to/cert/apiclient_cert.pem'),    // XXX: 绝对路径！！！！
//             'key_path'           => env('WECHAT_PAYMENT_KEY_PATH', 'path/to/cert/apiclient_key.pem'),      // XXX: 绝对路径！！！！
//             'notify_url'         => env('WECHAT_PAYMENT_NOTIFY_URL','http://example.com/payments/wechat-notify')
//        ];
//
//        $app = Factory::payment($config);
        $pay = $request->input('priec');
        $app = app('wechat.payment');
        $open_id = $request->input('open_id');
        $result = $app->order->unify([
            'body' => '动物认养',
            'out_trade_no' => 'no_'.random_int(10000, 99999),  // 日期时分秒+6位
            'total_fee' => 1,  // 价格  以分为单位
            'trade_type' => 'JSAPI', // 请对应换成你的支付方式对应的值类型
            'openid' => $open_id,
        ]);
        if ($result['return_code'] === 'FAIL') {
            return $result;
        }
        if ($result['result_code'] === 'FAIL') {
            return $result;
        }
        $payId = $result['prepay_id'];
        $jssdk = $app->jssdk;
        $config = $jssdk->bridgeConfig($payId);
        return $config;
    }

    public function spaPay(Request $request)  // 生成预支付订单
    {
//        $config = [
//    // 必要配置
//            'app_id'             => env('WECHAT_PAYMENT_APPID', ''),
//            'mch_id'             => env('WECHAT_PAYMENT_MCH_ID', 'your-mch-id'),
//            'key'                => env('WECHAT_PAYMENT_KEY', 'key-for-signature'),  // API 密钥
//
//            // 如需使用敏感接口（如退款、发送红包等）需要配置 API 证书路径(登录商户平台下载 API 证书)
//             'cert_path'          => env('WECHAT_PAYMENT_CERT_PATH', 'path/to/cert/apiclient_cert.pem'),    // XXX: 绝对路径！！！！
//             'key_path'           => env('WECHAT_PAYMENT_KEY_PATH', 'path/to/cert/apiclient_key.pem'),      // XXX: 绝对路径！！！！
//             'notify_url'         => env('WECHAT_PAYMENT_NOTIFY_URL','http://example.com/payments/wechat-notify')
//        ];
//

        logger(' 支付测试  TEST');
        $pay = $request->input('price');
        $token = $request->header('Authorization');
        $open_id = $request->input('open_id');

        $payment = app('wechat.payment');
          $result = $payment->order->unify([
            'body' => '动物认养',
            'out_trade_no' => 'no_'.random_int(10000, 99999).'_'.random_int(10000, 99999),  // 日期时分秒+6位
            'total_fee' => 1,  // 价格  以分为单位
            'trade_type' => 'JSAPI', // 请对应换成你的支付方式对应的值类型
            'openid' => $open_id,
          ]);
            if ($result['return_code'] === 'FAIL') {
                return $result;
            }
            if ($result['result_code'] === 'FAIL') {
                return $result;
            }
        logger($result);
        $payId = $result['prepay_id'];
        $jssdk = $payment->jssdk;
        $config = $jssdk->sdkConfig($payId);
        // $config = $jssdk->bridgeConfig($payId);
         logger($config);
        return $config;
    }

    public function getOpenId(Request $request) {
        //$params = explode(' ', $token);
        $t = $request->input('token');
        if (Cache::has($t)){
            $openId = Cache::get($t);
            if (empty($openId)) {
                var_dump('empty');
            } else {
                return response()->json(Cache::get($t),200);
            }

        } else {
            return '';
        }
    }

    // SPA下授权和获取jssdk签名

    public function oauth(Request $request)
    {
        $end_url = request('end_url');
        $app = app('wechat.official_account');
        $oauth = $app->oauth;
        $user = $oauth->user();
        $info = $user->getOriginal();
        $data = array(
            'openid' => $info['openid'],
            'nickname' => $info['nickname'],
            'gender' => $info['sex'],
            'avatar' => $info['headimgurl'],
            'province' => $info['province'] ?? '',
            'city' =>  $info['city'] ?? '',
            'country' => $info['country']??'',
            'follow' =>  $info['subscribe'] ?? 0,
            'follow_time' =>  $info['subscribe_time'] ?? 0
        );
        $token = $user['token']; //md5(uniqid());
        $expiresAt = Carbon::now()->addDays(7);
        Cache::put($token,$data['openid'], $expiresAt);
        $url="$end_url?token=".$token.'&openid='.$data['openid'];  //
        header('location: '.$url);
        exit;
    }

    public function start_oauth(Request $request)
    {
        $end_url = $request->input('end_url');
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $http_host = "$protocol$_SERVER[HTTP_HOST]";
        $config = [
            // ...
            'app_id' => config('wechat.official_account.default.app_id', 'your-app-id'),         // AppID
            'secret' => config('wechat.official_account.default.secret', 'your-app-secret'),    // AppSecret
            'token' => config('wechat.official_account.default.token', 'your-token'),           // Token
            'aes_key' => config('wechat.official_account.default.aes_key', ''),                 // EncodingAESKey
            'oauth' => [
                'scopes'   => ['snsapi_userinfo'],
                'callback' => $http_host.'/api/wx/oauth?end_url='.$end_url
            ],
        ];
        $app = Factory::officialAccount($config);
        $oauth=$app->oauth;
        return $oauth->redirect()->send();
    }

    public function config(Request $request)  // jssdk配置
    {
        $api = $request->input('api', []);
        $url = $request->input('url', env('FRONTEND_CALL'));
        $app = app('wechat.official_account');
        //  $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
//       $url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $app->jssdk->setUrl($url);
        $config = $app->jssdk->buildConfig($api, true, false,true);
        return $config;
    }

}
