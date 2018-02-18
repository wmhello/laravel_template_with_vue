<?php

namespace App\Http\Controllers\Import;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class SessionImport extends \Maatwebsite\Excel\Files\ExcelFile
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
