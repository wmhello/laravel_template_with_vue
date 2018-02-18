<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/8 0008
 * Time: 15:58
 */

namespace App\Http\Controllers\Import;


class TeachingImport
{
    use \App\Http\Controllers\Result;

    public function getFile()
    {

        return storage_path('app') .'/'.$this->fileUpdate();
    }

    public function getFilters()
    {
        return [
            'chunk'
        ];
    }
}