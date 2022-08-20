<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin;
use Illuminate\Http\Request;
use App\GatewayClient\Gateway;
use Illuminate\Support\Facades\Auth;
use function React\Promise\all;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function check()
    {
        /**
         * 先检测客服是否已经上班，不上班或者上线无法使用客服功能
         * 客服如果已经登记了，做下面的事情：
         * 1.则把所有要咨询的用户都存入到一个服务组，便于客服统一给大家发信息
         * 2.把自己的信息进行注册，发送给客服，让客服能知道现哪些人已经在线等待咨询
         */


        $customer = ENV("CUSTOMER", "admin");
        $uid = Admin::where('email', $customer)->value('id');
        $address = env('REGISTER_ADDRESS', '127.0.0.1:1680');
        Gateway::$registerAddress = $address;
        $client_id = request('uuid'); // 获取现在接入的用户
        Gateway::joinGroup($client_id, "services");  // 把现在的用户接入到客服组，客户退出等可以同时进行通知
        if (Gateway::isUidOnline($uid)) {
            // 获得客服对应的client_id,用于发送信息
            $arr = Gateway::getClientIdByUid($uid);
            if (count($arr) === 1) {
                // uid与client_id 一对一，才能找到指定的session，获得是否注册为客服
                $customer_client_id = array_pop($arr);  // 客服的client_id
                $session = Gateway::getSession($customer_client_id);
                if (key_exists("is_customer", $session) && $session['is_customer']) {
                    // 登记用户信息进入组
                    $user = Auth::user();
                    $data = [
                        'name' => $user->email,
                        'avatar' => $user->avatar,
                        'client_id' => $client_id,
                        "type" => 'userLogin',
                        'content' => null,
                        'select' => 'all'
                    ];
                    Gateway::sendToClient($customer_client_id, json_encode($data));
                    return $this->successWithInfo("已经有客服存在");
                } else {
                    return $this->errorWithInfo('客服在线但没有登录客服页面，请稍后', 400);
                }
            } else {
                return $this->error();
            }
        } else {
            return $this->errorWithInfo("客服已经下班，无法咨询。", 400);
        }
    }

    public function leave()
    {
        /**
         * 客户离开，则我们进行相应的处理
         * 1.退出组
         * 2.提示客服，某个用户已经下线
         */
        // 退出组
        $address = env('REGISTER_ADDRESS', '127.0.0.1:1680');
        Gateway::$registerAddress = $address;
        $client_id = $this->getWebsocketClientId(); // 获取现在接入的用户
        Gateway::leaveGroup($client_id, "services");  // 把现在的用户接入到客服组，用户对退出等进行通知
        // 提醒客服，我已经退出
        $customer_client_id = $this->getCustomerClientId();
        $data = [
            'client_id' => $client_id,
            "type" => 'userLogout',
            'content' => null,
            'select' => 'all'
        ];
        Gateway::sendToClient($customer_client_id, json_encode($data));
        return $this->success();
    }

    protected function getCustomerClientId()
    {
        $customer = ENV("CUSTOMER", "admin");
        $uid = Admin::where('email', $customer)->value('id');
        $arr = Gateway::getClientIdByUid($uid);
        // uid与client_id 一对一，才能找到指定的session，获得是否注册为客服
        $customer_client_id = array_pop($arr);  // 客服的client_id
        return $customer_client_id;
    }

    protected function initGateWay()
    {
        $address = env('REGISTER_ADDRESS', '127.0.0.1:1680');
        Gateway::$registerAddress = $address;
    }

    public function customer()
    {
        $customer = ENV("CUSTOMER", "admin");
        return $this->successWithData([
            "customer" => $customer
        ]);
    }

    // 客服在线注册
    public function register()
    {
        // 在client_id 对应的session中登记为客服，因为用户即使在线，有时候也没有打开这个页面
        // 所以不一定是客户，这就要求用户进入到页面后，会自动的注册登记
        $uid = Auth::id();
        $address = env('REGISTER_ADDRESS', '127.0.0.1:1680');
        Gateway::$registerAddress = $address;
        if (Gateway::isUidOnline($uid)) {
            $arr = Gateway::getClientIdByUid($uid);
            $client_id = $arr[0];
            Gateway::updateSession($client_id, [
                'is_customer' => true
            ]);
            $data = [
              'type' => "customerLogin",
              'select' => "all",
              "name" => Auth::user()->email
            ];
             Gateway::sendToGroup("services", json_encode($data));
            return $this->success();
        } else {
            return $this->errorWithInfo("客服注册失败。");
        }

    }

    public function unRegister()
    {
        // 客服取消注册在线
        // 前端退出页面的时候，取消客服注册

        $client_id = request('uuid');
        $address = env('REGISTER_ADDRESS', '127.0.0.1:1680');
        Gateway::$registerAddress = $address;
        Gateway::updateSession($client_id, [
            'is_customer' => false
        ]);
        $data = [
          'type' => "customerLogout",
          'select' => "all"
        ];
        Gateway::sendToGroup("services", json_encode($data));
        return $this->success();
    }
    // 普通用户发送数据给客服
    public function sendDataToCustomer()
    {
        $data = request()->only(['name', 'content', 'time']);
        $this->initGateWay();
        $client_id = $this->getWebsocketClientId();
        $customer_client_id = $this->getCustomerClientId();
        $admin_id = (int)Gateway::getUidByClientId($client_id);
        $customer_admin_id = (int)Gateway::getUidByClientId($customer_client_id);
        $data["select"] = "all";
        $data["type"] = "userSay";
        Gateway::sendToClient($customer_client_id, json_encode($data));
        return $this->success();

    }
    // 客服发送数据给用户
    public function sendDataToUser()
    {
        $data = request()->only(['name', 'content', 'time', 'avatar']);
        $client_id = request('client_id');
        $this->initGateWay();
        $data["select"] = "all";
        $data["type"] = "customerSay";
        Gateway::sendToClient($client_id, json_encode($data));
        return $this->success();

    }

    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
