<?php
/**
 * Created by ubuy.test.
 * User: wmhello
 * Email: 871228582@qq.com
 * Date: 2019/5/20 0020
 */

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Rap2hpoutre\FastExcel\FastExcel;

class TempController extends Controller
{
    protected  $model = 'App\Models'; // 模型类
    protected  $fillable = []; //  模型可以批量修改的数据，用于在新建和修改是读取从前端传入的内容
    protected  $resource = 'App\Http\Resources'; // 显示个体资源
    protected  $resourceCollection = 'App\Http\Resources'; // 显示资源集合
    protected $map = [];   // 导入导出数据的时候  数据表字段与说明的映射表


    protected function getListData($pageSize)
    {
        // index方法上调用，显示不同的内容
       //   $data = $this->model::paginate($pageSize);  // 查找内容的修改
       //   return new $this->resourceCollection($data);

        return parent::getListData($pageSize);   // 分页显示每个表的记录
    }


    protected function storeHandle($data)
    {
//        $data['password'] = bcrypt($data['password']);  // 需要在保存内容之前做相应的处理，则添加
//        if (!isset($data['roles'])) {
//            $data['roles'] = 'user';   // 默认值的情况，可以通过数据表直接设置，也可以通过程序来设置
//        }
        return parent::storeHandle($data);   // TODO: 调用父类
    }


    protected  function  updateHandle($data){
        // 保存到数据表之前进一步处理数据，用于数据的转换等操作
//        $data['password'] = bcrypt($data['password']);  // 需要在保存内容之前做相应的处理，则添加
//        if (!isset($data['roles'])) {
//            $data['roles'] = 'user';   // 默认值的情况，可以通过数据表直接设置，也可以通过程序来设置
//        }
        return parent::updateHandle($data);
    }

    /**
     * @param $id
     * @return bool
     * 删除内容时，如果涉及到多个表，请在事务内部进行处理
     */
    protected function destoryHandle($id)
    {
        DB::transaction(function () use ($id) {
            // 删除逻辑  注意多表关联的情况
            $this->model::where('id', $id)->delete();
        });
        return true;
    }

    /**
     * 导出数据表的内容到指定的电子表格文件
     */


    protected function exportHandle($arrData)
    {
        $arr = [];
        foreach($arrData as $key=>$item){
           $arr1 = $this->handleItem($item);
           // 可以进一步增加或者修改具体的数据
            $arr[] = $arr1;
        }
        return $arr;
    }


    protected function importHandle($arrData){
//        1. 要对每一条记录进行校验

//        2. 根据校验的结果，计算出可以导入的条数，以及错误的内容

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

    /**
     * @param $id  主要用于重复内容，忽略某些字段，可以根据需求修改
     * @return array  验证规则
     * 数据的更新的验证规则
     */
    protected function updateRule($id) {
        //
        return [];
    }

    /**
     * @return array
     * 增加业务是进行表单验证
     */
    protected function storeRule(){
        // 增加数据时的验证规则
        return [];
    }

    /**
     * @return array
     * 表单验证时显示的错误信息 根据需要开启
     */
//    protected function message() {
//      // 验证出错是的提示信息
//        return [];
//    }


}