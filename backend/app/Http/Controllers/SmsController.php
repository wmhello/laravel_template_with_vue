<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Overtrue\EasySms\EasySms;

class SmsController extends Controller
{
    //
    use Result;

    protected function sendVerifyCode($phone, $template = '54224')
    {
        // 3、 生成验证码并写入到数据表中
        $verify = rand(100000,999999);
        // 缓存信息，120秒  2分钟
        $expiresAt = Carbon::now()->addMinutes(2);
        Cache::put($phone, $verify, $expiresAt);

        // 4、 发送验证码到指定的手机
        $easySms = new EasySms($this->getParams());
        $easySms->send($phone, [
            'template' => $template,
            'data' => [
                'code' => $verify
            ],
        ]);
        return $this->successWithInfo('验证码已经发送');
    }

    // 手机验证码功能，判断发送的验证码是否成功
    protected function checkVerify($phone, $verify)
    {
        if (Cache::has($phone)) {
            $check = Cache::get($phone);
        } else {
            $info = '手机验证码不存在';
            return $this->errorWithInfo($info);
        }
        if ($check != $verify) {
            $info = '输入的验证码不正确，请重新输入';
            return $this->errorWithInfo($info);
        } else {
            $info = '输入的验证码正确';
            return $this->successWithInfo($info);
        }
    }

    // 发送验证码
    public function send(Request $request)
    {
        $phone = $request->input('phone');
        return $this->sendVerifyCode($phone);
    }

    // 验证码校验
    public function verify(Request $request)
    {
      $phone = $request->input('phone');
      $code = $request->input('code', 111111);
      return $this->checkVerify($phone, $code);
    }

    // 配置参数
    protected function getParams()
    {


        $key = config("app.sms_api_key");
        $sms_config = [
            // HTTP 请求的超时时间（秒）
            'timeout' => 5.0,

            // 默认发送配置
            'default' => [
                // 网关调用策略，默认：顺序调用
                'strategy' => \Overtrue\EasySms\Strategies\OrderStrategy::class,

                // 默认可用的发送网关
                'gateways' => [],
            ],
            // 可用的网关配置
            'gateways' => [
                'errorlog' => [
                    'file' => '/tmp/easy-sms.log',
                ],
                'juhe' => [
                    'app_key' => $key,
                ],
            ],
        ];
        // josn转数组，env文件里面的配置参数必须用双引号引起来
        $gateway =  json_decode(config("app.sms_gateway_type"),true);
        $sms_config['default']['gateways'] = $gateway;
     return $sms_config;
    }
}
