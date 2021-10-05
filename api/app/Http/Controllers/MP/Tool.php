<?php


namespace App\Http\Controllers\MP;


use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

trait Tool
{
    protected function success()
    {
        return response()->json([
            'status' => 'success',
            'status_code' => 200
        ], 200);
    }

    protected function successWithInfo($msg = '操作成功', $code = 200)
    {
        return response()->json([
            'info' => $msg,
            'status' => 'success',
            'status_code' => $code
        ], $code);
    }

    protected function successWithData($data = [], $code = 200)
    {
        return response()->json([
           'data' => $data,
           'status' => 'success',
           'status_code' => $code
        ], $code);
    }

    protected function error()
    {
        return response()->json([
            'status' => 'error',
            'status_code' => 404
        ], 404);
    }

    protected function errorWithInfo($msg = '操作失败', $code = 404)
    {
        return response()->json([
            'info' => $msg,
            'status' => 'error',
            'status_code' => $code
        ], $code);
    }

    protected function errorWithData($data = [], $code = 404)
    {
        return response()->json([
            'data' => $data,
            'status' => 'error',
            'status_code' => $code
        ], $code);
    }

    protected function receiveFile()
    {
        $file = request()->file('file');
         if ($file->isValid()) {
             $domain = getDomain();
             $fileName = $file->store('', 'public');
             $file = Storage::url($fileName);
             $path = $domain.$file;
             return $path;
         } else  {
             return '';
         }
    }





    public function getUserIdByOpenid()
    {
        $request = request();
        $auth = $request->header('Authorization');
        $arrTemp = explode(' ', $auth);
        if (count($arrTemp) === 2 && $arrTemp[0] === 'Bearer') {
            // 根据令牌获取openid，然后去查看是否有该用户
            $token = $arrTemp[1];
            $openId = $token;
            // 用户没有注册，也许是使用postman等直接调用的
            $member = User::where('open_id', $openId)->first();
            if (!$member) {
                return response()->json([
                    'msg' => '没有找到指定的内容，请用微信登录',
                    'status' => 'error',
                    'status_code' => 400
                ], 400);
            } else {
                return $member->id;
            }

        }
    }



    public function getTeacherIdByOpenid()
    {
        $user_id = $this->getUserIdByOpenid();
        $teacher_id = DB::table('user_teachers')->where('user_id',$user_id)->value('teacher_id');
        return $teacher_id;
    }


    public function getSchoolIdByOpenid()
    {
        $user_id = $this->getUserIdByOpenid();
        $teacher_id = DB::table('user_teachers')->where('user_id',$user_id)->value('teacher_id');
        $school_id = DB::table('teachers')->where('id', $teacher_id)->value('school_id');
        return $school_id;
    }

    public function getStudentIdByOpenid(){
        $user_id = $this->getUserIdByOpenid();
        $student_id = DB::table('user_students')->where('user_id',$user_id)->value('student_id');
        return $student_id;
    }

    public function getSchoolIdByStudentId(){
        $student_id = $this->getStudentIdByOpenid();
        $school_id = DB::table('students')->where('id', $student_id)->value('school_id');
        return school_id;
    }


}
