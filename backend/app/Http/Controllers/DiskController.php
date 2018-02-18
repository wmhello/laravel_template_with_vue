<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DiskController extends Controller
{
    //
    public function disk1()
    {
        $file ='abc.txt';
        $bools = Storage::disk('local')->exists($file);
        if ($bools) {
            echo $file . '在指定的目录下存在该文件';
            Storage::prepend($file, '追加内容到之前');

        } else {
            echo $file . '在指定的目录下不存在<br/>将新建文件';
            Storage::put($file, '文件内容');

        }
    }

    public function copy()
    {
        $file ='abc.txt';
        $bools = Storage::disk('local')->exists($file);
        if ($bools) {
            Storage::copy($file, 'new/'.$file);
            echo '文件拷贝成功';
        } else {
            echo $file . '在指定的目录下不存在';
        }
    }

    public function move()
    {
        $file ='abc.txt';
        $bools = Storage::disk('local')->exists($file);
        if ($bools) {
            if (Storage::exists('new/'.$file)){
              echo '文件已经存在,将删除后在覆盖';
              Storage::delete('new/'.$file);
            }
            Storage::move($file, 'new/'.$file);
            echo '文件成功';
        } else {
            echo $file . '</br>在指定的目录下不存在';
        }
    }
}
