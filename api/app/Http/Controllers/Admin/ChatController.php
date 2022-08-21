<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin;
use Illuminate\Http\Request;
use App\GatewayClient\Gateway;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // 进入聊天室
    /**
     *  1.广播给其他用户我已经我已经进入聊天室
     *  2.加入聊天室组
     *  3.返回连自己在内的所用用户
     */
    public function register()
    {

        $this->initGateWay();
        $client_id = $this->getWebsocketClientId();
        $user = Auth::user();
        $arr = Gateway::getClientSessionsByGroup("chat");
        $chatArr = array_keys($arr);
        if (count($arr)>=1){
          // 广播给其他用户
           $data = [
              'name' => $user->email,
              'avatar' => $user->avatar,
              'client_id' => $client_id,
              "type" => "chatUserLogin",
              "select" => "all"
           ];
           Gateway::sendToGroup("chat",json_encode($data));
        }
        Gateway::joinGroup($client_id, "chat");
        // 返回已经在线的信息
        $otherUser = [];

        foreach ($chatArr as $v) {
           $uid = Gateway::getUidByClientId($v);
           $otherUser[$uid] = $v;
        }

        $existsData = [];
        foreach ($otherUser as $uid => $v) {
           $user = Admin::find($uid);
           $existsData [] = [
               'name' => $user->email,
               'avatar' => $user->avatar,
               'client_id' => $v
           ];
        }
        return $this->successWithData($existsData);
    }


    // 取消注册

    /**
     * 1.退出聊天的组
     * 2.获取剩下的聊天室成员
     * 3.发送数据去通知
     */
    public function unRegister()
    {
       $this->initGateWay();
       $client_id = $this->getWebsocketClientId();
       Gateway::leaveGroup($client_id, "chat");
       $chatArray = Gateway::getClientSessionsByGroup("chat");
       $chatArr = array_keys($chatArray);
       $uid = Gateway::getUidByClientId($client_id);
       $user = Admin::find($uid);
       $data = [
         "client_id" => $client_id,
         "name" => $user->email,
         "type" => "chatUserLogout" ,
         "select" => "all"
       ];
       Gateway::sendToAll(json_encode($data), $chatArr);
       return $this->success();
    }

    /**
     * 发送信息给聊天室的其他用户
     * 从chat组中找出所有的额数据，去掉本人的，然后发给剩下所有的
     */

    public function sendDataToUser()
    {
        $data = request()->only(['name', 'content', 'avatar', 'time']);
        $client_id = $this->getWebsocketClientId();
        $this->initGateWay();
        $chatArray = Gateway::getClientSessionsByGroup("chat");
        $chatArr = array_keys($chatArray);
        // 删除自身
        unset($chatArr[array_search($client_id, $chatArr)]);
        $chatIds = array_values($chatArr);
        $data['type'] = "chatUserSay";
        $data['select'] = "all";
        Gateway::sendToAll(json_encode($data), $chatIds);
        return $this->successWithInfo("信息已经发送");
    }

    protected function initGateWay()
    {
        $address = env('REGISTER_ADDRESS', '127.0.0.1:1680');
        Gateway::$registerAddress = $address;
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
