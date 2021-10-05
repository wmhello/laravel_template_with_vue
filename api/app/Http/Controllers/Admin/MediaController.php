<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * @group 媒体管理
 * @package App\Http\Controllers\Admin
 */
class MediaController extends Controller
{
    use Tool;
    //
    /**
     * 文件上传
     * @bodyParam  file required file 上传的文件
     */
    public function store(Request $request)
    {
        $domain = getDomain();
        $file = $request->file('file');
         if ($request->file('file')->isValid()) {
             $domain = getDomain();
             $fileName = $file->store('', 'public');
             $file = Storage::url($fileName);
             $path = $domain.$file;
             return $this->successWithData([
                 "url" => $path
             ], 201);
         } else  {
             return $this->errorWithInfo('上传文件失败，估计是文件太大，上传超时');
         }
    }
}
