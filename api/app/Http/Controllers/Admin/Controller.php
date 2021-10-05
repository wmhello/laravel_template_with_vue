<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Validator;
use Rap2hpoutre\FastExcel\Facades\FastExcel;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    use Tool;
    use Validate;

    /**
     *
     *  1.接收文件，打开数据
     *   2. 处理打开的数据，循环转换
     *   3. 导入到数据库
     * @return \Illuminate\Http\JsonResponse
     */

     public function import()
     {

        $action = request()->input("action", "import");
        switch ($action) {
            case 'download':
                $result = [];
                $result [] = [
                    "昵称" => "xpyzwm",
                    "登录名" => "xpyzwm",
                    "电话号码" => "13577728948",
                    "角色" => 'user'
                ];
                $list = collect($result);
                // 直接下载
                return (FastExcel::data($list))->download('template.xlsx');
                break;
            default:
                $data = FastExcel::import(request()->file('file'));
                $arrData = $data->toArray();
                $arr = $this->importHandle($arrData);
                $this->model::insert($arr['successData']);
                $tips = '当前操作导入数据成功' . $arr['successCount'] . '条';
                if ($arr['isError']) {
                    // 有失败的数据，无法插入，要显示出来，让前端能下载
                    $file = time() . '.xlsx';
                    $fileName = public_path('xls') . '\\' . $file;
                    $file = 'xls\\' . $file;
                    $data = collect($arr['errorData']);
                    (new FastExcel($data))->export($fileName);
                    $tips .= ',失败' . $arr['errorCount'] . '条';
                    return response()->json([
                        'info' => $tips,
                        'fileName' => $file,
                        'status' => 'error',
                        'status_code' => 422
                    ], 422);
                } else {
                    return $this->successWithInfo($tips, 201);
                }
        }
    }

    /**
     * 1. 要对每一条记录进行校验
     * 2. 根据校验的结果，计算出可以导入的条数，以及错误的内容
     * @param $arrData
     * @return array
     */
    protected function importHandle($arrData){
        $error = []; // 错误的具体信息
        $isError = false;  // 是否存在信息错误
        $successCount = 0; // 统计数据导入成功的条数
        $errorCount = 0;  // 出错的条数
        $arr = [];  // 正确的内容存储之后，返回数据
        foreach ($arrData as $key => $item) {
            $data = $this->handleItem($item, 'import');
            $data['created_at'] = Carbon::now();
            // 可以根据需要，进一步处理数据
            $this->validatorData($item,$data,$error, $isError ,$successCount, $errorCount,$arr);
        }
        return [
            'successData' => $arr,
            'errorData' => $error,
            'isError' => $isError,
            'errorCount' => $errorCount,
            'successCount' => $successCount,
        ];
    }

    protected function validatorData($item, $data, &$error, &$isError ,&$successCount, &$errorCount,&$arr){
        if (method_exists($this, 'message')){
            $validator = Validator::make($data,$this->storeRule(),$this->message());
        } else {
            $validator = Validator::make($data,$this->storeRule());
        }
        if ($validator->fails()){
            // 获取相关的错误信息，并且把错误信息单独存放
            $errors = $validator->errors($validator);
            $tips = '';
            foreach ($errors->all() as $message){
                $tips .= $message.',';
            }
            $tips = substr($tips,0,strlen($tips)-1);
            // 状态信息
            $item['错误原因'] = $tips;
            $error[] = $item;
            $isError = true;
            $errorCount ++;
        } else {
            // 没有出错的，我们先存在正确的数组
            $arr[] = $data;
            $successCount ++;
        }
    }

    protected function storeRule(){
      return [];
    }

    protected  function UpdateRule($id){
        return [];
    }


//    protected function  message(){
//        return [];
//    }




}
