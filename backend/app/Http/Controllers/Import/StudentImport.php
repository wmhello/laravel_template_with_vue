<?php

namespace App\Http\Controllers\Import;


class StudentImport extends \Maatwebsite\Excel\Files\ExcelFile
{
    use \App\Http\Controllers\Result;

    public function getFile()
    {

        $fileName = storage_path('app') . '/' . $this->fileUpdate();
        return $fileName;
    }

    public function getFilters()
    {
        return [
            'chunk'
        ];
    }
}
