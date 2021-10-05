<?php


namespace App\Http\Controllers\Admin;


use Illuminate\Support\Facades\Storage;

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

    protected function deleteAvatar($url) {
        $domain = getDomain();
        $path = $domain.'/storage/';
        $file = str_replace($path, '', $url);
        $address = storage_path('app/public');
        $fileName = $address.'/'.$file;
        if (file_exists($fileName)) {
            unlink($fileName);
        }
    }
}
