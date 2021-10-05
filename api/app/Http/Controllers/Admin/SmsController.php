<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Overtrue\EasySms\EasySms;
/**
 * @group 验证码和短信发送管理
 * @package App\Http\Controllers\Admin
 */
class SmsController extends Controller
{
    use Tool;
    //
     protected $ttl = 5; // 过期时间 分钟为单位
    /**
     *   生成手机验证码
     * @bodyParam phone string required 手机号码
     */

    public function send_code()
    {
        $phone = request('phone');
        // 缓存 号码与数值
        if (($code = $this->isHasCode($phone))) {
        } else {
           $code = mt_rand(100000, 999999);
           $time = now()->addMinutes($this->ttl);
           Cache::add($phone, $code, $time);
        }
        // 发送手机短信
        if (config('app.debug')) {
           return $this->successWithInfo("验证码是:".$code."，请在".$this->ttl."分钟内输入");
        } else {
           $this->sendVerifyCode($phone, $code);
           return $this->successWithInfo("验证码已经发送到指定手机，请在".$this->ttl."分钟内输入");
        }
    }

    protected function isHasCode($key) {
     return Cache::has($key)? Cache::get($key):false;
    }

    protected function  sendVerifyCode($phone, $value) {
       $data =  [
        'code' => $value
       ];
       // 发送真实数据至手机
       $this->sendInfo($phone, $data, 'template_001');
    }

    protected function sendInfo($phone, $data, $template = '') {
        $config = [
            // HTTP 请求的超时时间（秒）
            'timeout' => 5.0,

            // 默认发送配置
            'default' => [
                // 网关调用策略，默认：顺序调用
                'strategy' => \Overtrue\EasySms\Strategies\OrderStrategy::class,

                // 默认可用的发送网关
                'gateways' => [
                    'juhe', 'qcloud',
                ],
            ],
            // 可用的网关配置
            'gateways' => [
                'errorlog' => [
                    'file' => '/www/wwwroot/easy-sms.log',
                ],
                'juhe' => [  // 聚合数据的配置
                   'app_key' => '163a137975527e3b6eaca21d701e71c0',
                ],
                'qcloud' => [        // 腾讯云配置
                    'sdk_app_id' => '', // SDK APP ID
                    'app_key' => '', // APP KEY
                    'sign_name' => '', // 短信签名，如果使用默认签名，该字段可缺省（对应官方文档中的sign）
                ]
            ],
        ];
        $easySms = new EasySms($config);

        $easySms->send($phone, [
            'content'  => '',
            'template' => $template,
            'data' => $data,
        ]);
    }

    /**
     * 校验手机验证码
     * @bodyParam phone string required 手机号码
     * @bodyParam code string required  验证码
     */
    public function verify_code()
    {
        $code = reqest('phone_code');
        $phone = request('phone');

        if ($value = $this->isHasCode($phone)) {
          if ((int)$value === (int)$code){
              return $this->successWithInfo('验证码正确，谢谢使用');
          } else {
              return $this->errorWithInfo('输入的验证码不正确，请仔细查看');
          }
        } else {
            return $this->errorWithInfo('该手机验证码已经过期，请重新发送',404);
        }

    }


}
