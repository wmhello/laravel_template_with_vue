<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    //
    public function update()
    {
        return view('file.update');
    }

    public function updateXls()
    {
        return view('file.update_xls');
    }

    public function store(Request $request)
    {
        $file = $request->file('file1')->store('images');
        $fullFileName = storage_path('app').'\\'.$file;
        echo $fullFileName;
        if (Storage::exists($file)) {
            echo '文件存在';
            echo '<br>文件拷贝';
            if (Storage::copy($file,'images/test.jpg'))  {
                echo '<br>拷贝成功';
            }
        } else {
            echo '文件不存在';
        }
    }

    public function storeXls(Request $request)
    {
        $file = $request->file('file1');
        $type=['application/vnd.ms-excel'];
        $fileType = $file->getClientMimeType();
         if (in_array($fileType, $type)) {
             echo '格式正确，保存文件<br>';
             $clientExt = $file->getClientOriginalExtension();
             $fileName = date('ymdhis').'.'.$clientExt;
             return $file->storeAs('xls',$fileName);
         } else {
             return '上传的文件格式不正确，无法保存';
         }

    }

    public function files()
    {
         $files = Storage::disk('test')->files('abc');
         var_dump($files);
         echo '<br>';
         foreach ($files as $file) {
             $fileName = preg_replace('/abc\//','',$file);
             echo $fileName.'<br>';
         }
    }
}
