<?php
/**
 * Created by ubuy.test.
 * User: wmhello
 * Email: 871228582@qq.com
 * Date: 2019/5/20 0020
 */

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Rap2hpoutre\FastExcel\FastExcel;

class PermissionController extends Controller
{
    protected  $model = 'App\Models\Permission'; // 模型类
    protected  $fillable =  ['name', 'pid', 'type', 'route_name',  'remark']; //  模型可以批量修改的数据，用于在新建和修改是读取从前端传入的内容
    protected  $resource = 'App\Http\Resources\Permission'; // 显示个体资源
    protected  $resourceCollection = 'App\Http\Resources\PermissionCollection'; // 显示资源集合
    protected $map = [];   // 导入导出数据的时候  数据表字段与说明的映射表

    public function index(Request $request)
    {
        //
        $pageSize = $request->input('pageSize', 10);
        $page = $request->input('page', 1);
        // $lists  = Permission::Name()->Pid()->Type()->paginate($pageSize);
        $lists  = Permission::Name()->Pid()->Type()->get();
        $count = Permission::Name()->Pid()->Type()->count();
        $countLen1 = Permission::where('type', 1)->count();
        $countLen2 = Permission::count();
        $items = $lists->toArray();
        $list_data = $items;
        $end = $list_data;
        if ($countLen1 !== $countLen2){ // 如果只有组类型，则无需进行无极限分类排序，直接输出结果就可以了
            if ( !$request->has('type')) {  // 如果仅显示某一类内容，也无需进行无极限分类，直接输出结果
                // 有组和节点类型   需要转换为无极限分类格式，分类下的所有API都在一起
                $data = $this->make_tree($list_data);
                $end = [];
                foreach($data as $item) {
                    if (isset($item['children'])) {
                        $values = $item['children'];
                        unset($item['children']);
                        array_push($end, $item);
                        $end = array_merge($end, $values);
                    } else {
                        array_push($end, $item);
                    }
                }
            }
        }
        return response()->json([
            'data' => $end,
            'meta' => [
                'total' => $count
            ],
            'status' => 'success',
            'status_code' => 200
        ], 200);
    }

    public function info(Request $request)
    {
        $pageSize = $request->input('pageSize', 10);
        $page = $request->input('page', 1);
        // $lists  = Permission::Name()->Pid()->Type()->paginate($pageSize);
        $lists  = Permission::Name()->Pid()->Type()->select(DB::raw('id,name, pid'))->get();
        $count = Permission::Name()->Pid()->Type()->count();
        $countLen1 = Permission::where('type', 1)->count();
        $countLen2 = Permission::count();
        $items = $lists->toArray();
        $list_data = $items;
        $end = $list_data;
        if ($countLen1 !== $countLen2){ // 如果只有组类型，则无需进行无极限分类排序，直接输出结果就可以了
            if ( !$request->has('type')) {  // 如果仅显示某一类内容，也无需进行无极限分类，直接输出结果
                // 有组和节点类型   需要转换为无极限分类格式，分类下的所有API都在一起
                $end = $this->make_tree($list_data);

            }
        }
        return $this->successWithData($end);
    }


    public function addGroup(Request $request)
    {
        $data = $request->only(['name', 'remark']);
        $rules = [
            'name' => 'required|unique:permissions'
        ];
        $messages = [
            'name.required' => '功能组名称必须填写',
            'name.unique' => '功能组名称已经存在，无法建立'
        ];
        $data['type'] = 1;
        $data['pid'] = 0;
       $validator = Validator::make($data, $rules, $messages);
       if ($validator->fails()) {
           $errors = $validator->errors();
           $errorTips = '';
           foreach($errors->all() as $message){
               $errorTips = $errorTips.$message.',';
           }
           $errorTips = substr($errorTips, 0, strlen($errorTips)-1);
           return $this->errorWithInfo($errorTips, 422);
       } else {
           $data['pid'] = 0;
           $data['type'] = 1;
           if ($this->model::create($data)) {
               return $this->successWithInfo('功能组添加成功', 201);
           } else {
               return $this->error();
           }
       }

    }

    public function getGroup()
    {
        $lists = $this->model::where('pid', 0)
            ->where('type', 1)
            ->get();
        return $this->successWithData($lists);
    }


//    public function getPermissionByTree()
//    {
//        // 获取权限数据 用于在树形控件中显示
//        $lists = $this->model::select(DB::raw('id, name, pid'))->get();
//        $arr = $lists->toArray();
//        $arr1 = $this->make_tree($arr);
//        dd($arr1);
//        return $this->successWithData($arr1);
//    }

    protected function make_tree($arr)
    {
        $refer = array();
        $tree = array();
        foreach($arr as $k => $v){
            $refer[$v['id']] = & $arr[$k]; //创建主键的数组引用
        }
        foreach($arr as $k => $v){
            $pid = $v['pid'];  //获取当前分类的父级id
            if($pid == 0){
                $tree[] = & $arr[$k];  //顶级栏目
            }else{
                if(isset($refer[$pid])){
                    $refer[$pid]['children'][] = & $arr[$k]; //如果存在父级栏目，则添加进父级栏目的子栏目数组中
                }
            }
        }
        return $tree;
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
            $this->model::where('pid', $id)->delete(); // 删除子节点
            $this->model::where('id', $id)->delete(); // 删除本身
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
        return [
            'name' => 'required|string',
            'pid' => 'required|integer',
            'type' => 'required|in:1,2',
            'route_name' => ['required_unless:type,1',
                Rule::unique('permissions')->ignore($id)]
        ];
    }

    /**
     * @return array
     * 增加业务是进行表单验证
     */
    protected function storeRule(){
        // 增加数据时的验证规则
        return [
            'name' => 'required|string',
            'pid' => 'required|integer',
            'type' => 'required|in:1,2',
        ];
    }

    /**
     * @return array
     * 表单验证时显示的错误信息 根据需要开启
     */
    protected function message() {
      // 验证出错是的提示信息
        return [
            'name.required' => '功能名称必须填写',
            'pid.required' =>  '所属组必须填写',
            'type.required' => '功能类型必须填写',
        ];
    }


}