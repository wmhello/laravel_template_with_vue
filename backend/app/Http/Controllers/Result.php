<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/30 0030
 * Time: 10:48
 */

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

trait Result
{
    public function success()
    {
         return response()->json([
           'status' => 'success',
           'status_code' => 200
        ], 200);
    }

    public function successWithData($data)
    {
        return response()->json([
            'data' => $data,
            'status' => 'success',
            'status_code' => 200
        ], 200);
    }

    public function error()
    {
        return response()->json([
            'status' => 'error',
            'status_code' => 404
        ], 404);
    }

    public function validateError($errors)
    {
        return response()->json(
            [
                'status' => 'validate error',
                'status_code' => 422,
                'errors' => $errors
            ], 422);
    }

    public function errorWithInfo( $info)
    {
        return response()->json([
            'status' => 'error',
            'status_code' => 404,
            'message' => $info
        ], 404);

    }

    public function errorWithCodeAndInfo($code, $info)
    {
        return response()->json([
            'status' => 'error',
            'status_code' => $code,
            'message' => $info
        ], $code);
    }

    public function fileUpdate()
    {
       $file = Input::file('file');
        $type=['application/vnd.ms-excel'];
        $fileType = $file->getClientMimeType();
        if (in_array($fileType, $type)) {
            $clientExt = $file->getClientOriginalExtension();
            $fileName = date('ymdhis').'.'.$clientExt;
            return $file->storeAs('xls',$fileName);
        } else {
            return $this->errorWithCodeAndInfo(406,'上传的文件格式不正确');
        }
    }

    public function deleteByIds($request)
    {
        $data = $request->only('ids');
        $rules = [
            'ids' => 'required | Array'
        ];
        $messages = [
            'ids.required' => '必须选择相应的记录',
            'ids.Array' => 'ids字段必须是数组'
        ];

        $validator = Validator::make($data, $rules, $messages);
        if ($validator->fails()) {
            $errors = $validator->error($validator);
            return $this->errorWithCodeAndInfo(422, $errors);
        } else {
            return $data;
        }
    }
}