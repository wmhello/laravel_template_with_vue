<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Maatwebsite\Excel\Facades\Excel;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    use Result;
    // 批量删除记录
    public function deleteAll()
    {
        $request = request();
        $data = $this->deleteByIds($request);
        $model = $this->getModel();
        if ($model::destroy($data['ids'])) {
            return $this->success();
        } else {
            return $this->error();
        }
    }

    // 导出所有的内容
    public function exportAll() {

        $this->exportHandle(null, 1);
    }

    // 导出当前指定的页
    public function export()
    {
        $request = request();
        $pageSize = (int)$request->input('pageSize');
        $pageSize = isset($pageSize) && $pageSize? $pageSize: 10;
        $page = (int)$request->input('page');
        $page = isset($page) && $page ? $page: 1;
        $this->exportHandle($pageSize, $page);
    }

    public function exportHandle($pageSize, $page)
    {
        // 处理流程，模板方法
        // 1、找出指定的数据
        $lists = $this->queryData($pageSize, $page);
        $data = $lists->toArray();  // 分页内容
        // 内部逻辑处理， 生成表头或者对应的去找关联数据
        $items = $this->generatorData($data);
        // 最后生成电子表格
        $this->generatorXls($items);
    }

    /**
     * 生成xls文件
     */
    public function generatorXls($items)
    {
        $file = $this->getExportFile();
        Excel::create($file, function ($excel) use ($items) {
            $excel->sheet('score', function ($sheet) use ($items) {
                $sheet->rows($items);
            });
        })->store('xls', public_path('xls'));
    }
}
