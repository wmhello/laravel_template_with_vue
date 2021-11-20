<?php

namespace App\Http\Controllers\MP;

use App\Models\Admin;
use App\Models\AdminRole;
use App\Models\User;
use Carbon\Carbon;
use EasyWeChat\Factory;
use Illuminate\Http\Request;
use App\Http\Controllers\MP\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    //
    public  $config = [
            'app_id' => 'wx7f8da3ac0cdfe244',
            'secret' => '1ac50578f8d1d3663d202b4a89b923fa',

            // 下面为可选项
            // 指定 API 调用返回结果的类型：array(default)/collection/object/raw/自定义类名
            'response_type' => 'array',

            'log' => [
                'level' => 'debug',
                'file' => __DIR__.'/wechat.log',
            ],
        ];

    public function getCode(Request $request)
    {
        $code = $request->input('code', '');
        if (empty($code)) {
            return [
              'status' => 'error',
                'status_code' => 400,
              'msg' => '没有参数',
            ];
        }
        $mp = Factory::miniProgram($this->config);
        $data = $mp->auth->session($code);
//        unset($data['openid']);
        return $data;
    }

    public function getInfo(Request $request)
    {

        $openid = $request->input('openid', '');
        $sessionKey = $request->input('session_key');
        $iv = $request->input('iv', '');
        $encryptedData = $request->input('encryptedData');
        $mp = Factory::miniProgram($this->config);
        $data = $mp->encryptor->decryptData($sessionKey, $iv, $encryptedData);
        $member = User::where('open_id', $data['openId'])->first();
        if (! $member) {
            $member = User::create([
                'open_id' => $data['openId'],
                'nickname'=> $data['nickName'],
                'avatar' => $data['avatarUrl'],
                'gender' => $data['gender'],
                'country' => $data['country'],
                'province' => $data['province'],
                'city' => $data['city']
            ]);
        } else {
            $member->avatar = $data['avatarUrl'];
            $member->gender = $data['gender'];
            $member->country = $data['country'];
            $member->province = $data['province'];
            $member->city = $data['city'];
            $member->updated_at = Carbon::now();
            $member->update();
        }
        $member = $member->toArray();
        $openid = $member['open_id'];
        unset($member['openid']);
        $result = [
            'token' => $openid,
            'data' => $member
        ];
        return $result;
    }

    public function getRun(Request $request)
    {

        $sessionKey = $request->input('session_key');
        $iv = $request->input('iv', '');
        $encryptedData = $request->input('encryptedData');
        $mp = Factory::miniProgram($this->config);
        $data = $mp->encryptor->decryptData($sessionKey, $iv, $encryptedData);
        $phone = $data['phoneNumber'];
        $user_id = $this->getUserIdByOpenid();
        User::where('id', $user_id)->update([
            'phone' => $phone
        ]);
        return $data;
    }


    public function saveUserInfo()
    {
        $data = request()->only([ 'avatar', 'gender', 'nickname', 'country', 'province', 'city']);
        $open_id = request('open_id');
        $bool = false;
        $item = User::where('open_id', $open_id)->first();
        if ($item) {
            $bool = User::where('open_id', $open_id)->update($data);
        } else {
            $data['open_id'] = $open_id;
            $bool = User::create($data);
        }
//        $bool = User::updateOrCreate([
//           'open_id' => $open_id
//        ], $data);
        if ($bool) {
            return $this->success();
        } else {
            return $this->error();
        }

    }


    public function scan_login()
    {
        $data = request()->only([
           'open_id', 'guid'
        ]);
        $action = request('action');
        switch ($action) {
            case 'getRoles':
                // code...
                  $teacher_id = $this->getTeacherIdByOpenid();
                  $phone = Teacher::where('id', $teacher_id)->value('phone');
                  $result = DB::table('v_admin_roles')->where('phone', $phone)->select(['role_id','desc'])->get();
                  if ($result) {
                       return $this->successWithData($result);
                  } else {
                     return $this->errorWithInfo('获取用户角色失败', 400);
                  }
                break;
            case 'setRoles':
                $role_id = request('role_id', null);
                DB::table('scan_login')->where('guid', $data['guid'])->update([
                    'open_id' => $data['open_id'],
                    'role_id' => $role_id
                ]);
                return $this->successWithInfo('扫码已经完成，稍后即将登陆');
                break;
            default:
                // code...
               DB::table('scan_login')->where('guid', $data['guid'])->update([
                    'open_id' => $data['open_id'],
                ]);
                return $this->successWithInfo('扫码已经完成，稍后即将登陆');
                break;
        }


    }

}
