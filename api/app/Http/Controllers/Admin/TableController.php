<?php


namespace App\Http\Controllers\Admin;

use App\Models\CodeSnippet;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class TableController extends Controller
{
    use Tool;
    protected  $model = 'App\Models\Table';  // 当前模型
    protected  $fillable = ['table_name', 'table_comment', 'engine', 'table_collation', 'create_time', 'table_config'];  // 当前模型可以修改和新增的字段
    protected  $resource = 'App\Http\Resources\Table'; // 显示个体资源
    protected  $resourceCollection = 'App\Http\Resources\TableCollection'; // 显示资源集合
    protected  $map = [];   // 导入导出时候  数据表字段与说明的映射表
    protected  $systemTable = [
     "admin_permissions",
     "admin_roles",
     "admins",
     "article_categories",
     "articles",
     "carousels",
     "failed_jobs",
     "logs",
     "migrations",
     "modules",
     "oauth_access_tokens",
     "oauth_auth_codes",
     "oauth_clients",
     "oauth_personal_access_clients",
     "oauth_refresh_tokens",
     "password_resets",
     "permissions",
     "role_permissions",
     "roles",
     "three_logins",
     "users",
     "code_configs",
     "code_snippets",
     "codes",
     "tables"
    ];

    public function index(Request $request)
    {
        // 显示订单列表
//        $tables = DB::connection()->getDoctrineSchemaManager()->listTableNames();
//        $existsTable = array_diff($tables, $this->systemTable);
//        dd($existsTable);
        $dbName = env('DB_DATABASE');
        $sql = <<<SQL
        SELECT 0 as id, table_name,engine, table_collation, table_comment, create_time, '' as table_config  
        FROM INFORMATION_SCHEMA.TABLES  
        WHERE TABLE_SCHEMA = '$dbName' and table_comment <> 'VIEW'
    SQL;
        $sql = str_replace('$dbName', $dbName, $sql);
        $tables = DB::connection("super")->select($sql);
        $myTable = array_filter($tables, function($table) {
            return   in_array($table->table_name, $this->systemTable)?false:true;
        });
        $data = array_values($myTable);
        $page = $request->page ?: 1;
          //每页的条数
        $pageSize = request('pageSize', 10);
          //计算每页分页的初始位置
        $offset = ($page * $pageSize ) - $pageSize;
        $result = new LengthAwarePaginator(array_slice($data, $offset, $pageSize, true), count($data), $pageSize, $page);
        return new $this->resourceCollection($result);
//        $pageSize = $request->input('pageSize', 10);
//        return  $this->getListData($pageSize);
    }

    protected  function  getListData($pageSize){
        // 当前列表数据  对应于原来的index
        $data = $this->model::paginate($pageSize);
        return new $this->resourceCollection($data);
    }


    public function show($id){
        if ($id >= 1) {
           $result = $this->model::find($id);
        } else {
           $result = $this->getResultByTable();
        }
        return new $this->resource($result);
    }

    protected function getResultByTable(){
         $table_name = request('table_name');
         $result = $this->model::where('table_name', $table_name)->first();
            if (!$result){
               $data = request()->only($this->fillable);
               $len = strlen($table_name);
               if ($table_name[$len-1] === "s"){
                   $front_model = substr($table_name, 0,$len-1);  // 去掉复数形式
                   $back_model = ucfirst($front_model);  // 首字母大写
                   $component = $back_model.'Index';
                   $config = [
                     'back_model' => $back_model,
                     'back_routes' => $table_name,
                     'front_model'  =>  $front_model,
                     'front_component_name' => $component
                   ];
               } else {
                   $back_model = ucfirst($table_name);  // 首字母大写
                   $component = $back_model.'Index';
                   $config = [
                     'back_model' => $back_model,
                     'back_routes' => $table_name.'s',
                     'front_model'  =>  $table_name,
                     'front_component_name' => $component
                   ];

               }
                  $data['create_time'] = Carbon::createFromFormat('Y-m-d H:i:s', $data['create_time']);
                  $data['table_config'] = json_encode($config);
                  $id = $this->model::insertGetId($data);
                  $result = $this->model::find($id);
            }

         return $result;
    }

    public function store(Request $request)
    {
//        1. 获取前端数据

        $data = $request->only($this->fillable);
//        2. 验证数据
        if (method_exists($this, 'message')){
            $validator = Validator::make($data, $this->storeRule(), $this->message());
        } else {
            $validator = Validator::make($data, $this->storeRule());
        }

        if ($validator->fails()){
            // 有错误，处理错误信息并且返回
            $errorTips = $this->getErrorInfo($validator);
            return $this->errorWithInfo($errorTips, 422);
        }
//        3.数据无误，进一步处理后保存到数据表里面，有的表需要处理，有的不需要
        $data = $this->storeHandle($data);
        if ($this->model::create($data)) {
            return $this->successWithInfo('新增数据成功', 201);
        } else {
            return $this->error();
        }
    }


        protected function storeHandle($data)
    {
          return $data;   // TODO: Change the autogenerated stub
    }

    protected function getErrorInfo($validator)
    {
            $errors = $validator->errors();
            $errorTips = '';
            foreach($errors->all() as $message){
                $errorTips = $errorTips.$message.',';
            }
            $errorTips = substr($errorTips, 0, strlen($errorTips)-1);
            return $errorTips;
    }


    public function update(Request $request, $id)
    {
        $action = request('action', 'default');
        if ($action === 'default') {
            // 普通的保存信息
            $data = $request->only($this->fillable);
            if (method_exists($this, 'message')){
                $validator = Validator::make($data, $this->updateRule($id), $this->message());
            } else {
                $validator = Validator::make($data, $this->updateRule($id));
            }
            if ($validator->fails()){
                // 有错误，处理错误信息并且返回
                $errorTips = $this->getErrorInfo($validator);
                return $this->errorWithInfo($errorTips, 422);
            }
            // 进一步处理数据
            $data = $this->updateHandle($data);
            // 更新到数据表
            if ($this->model::where('id', $id)->update($data)){
                return $this->successWithInfo('数据更新成功');
            } else {
                return $this->errorWithInfo('数据更新失败');
            }
        }
        if ($action === 'download'){
           $result = $this->getResultByTable();
           $config = $result->table_config;
           $tableName = $result->table_name;
           $this->createDir($tableName);
           $snippet = CodeSnippet::whereNotNull('name')->first();
           // 处理后端控制器数据
           $code = $this->createCodeBySnippet($snippet->back_api,$config);

           // 保存内容
           $fileName = $config['back_model'].'Controller.php';
           $controller = 'api/app/Http/Controllers/Admin';
           file_put_contents(public_path('code/'.$tableName.'/'.$controller)."/$fileName",$code);
           // 后端模型
           $code = $this->createCodeBySnippet($snippet->back_model,$config);

           dd($code);

           dd($config['back_model']);
           $zip = public_path("code/$tableName.zip");//压缩文件名，自己命名
           HZip::zipDir(public_path("code/$tableName"),$zip);
           return response()->download($zip, basename($zip))->deleteFileAfterSend(true);
        }

    }

    public function createDir($tableName)
    {
        // 建立保存文件的目录
         $controller = 'api/app/Http/Controllers/Admin';
         $model = 'api/app/Models';
         $routes = "api/routes";
         $resource = 'api/app/Http/Resources';
         $api = 'element/src/api';
         $front_model = 'element/src/model';
         $page = 'element/src/view';
         if (Storage::disk('code')->exists($tableName)){
             Storage::disk('code')->deleteDirectory($tableName);
         }
         Storage::disk('code')->makeDirectory($tableName);
         Storage::disk('code')->makeDirectory($tableName.'/'.$controller);
         Storage::disk('code')->makeDirectory($tableName.'/'.$model);
         Storage::disk('code')->makeDirectory($tableName.'/'.$routes);
         Storage::disk('code')->makeDirectory($tableName.'/'.$resource);
         Storage::disk('code')->makeDirectory($tableName.'/'.$api);
         Storage::disk('code')->makeDirectory($tableName.'/'.$front_model);
         Storage::disk('code')->makeDirectory($tableName.'/'.$page);
    }

    protected function createCodeBySnippet($code, $config)
    {
        $keys = array_keys($config);
        foreach ($keys as $key){
           $format = "##".$key."##";
           $value = $config[$key];
           $code = str_replace($format, $value, $code);;
         }
        return $code;
    }

    protected  function  updateHandle($data){
        return $data;
    }

    public function destroy($id)
    {
        if ($this->destroyHandle($id)){
            return  $this->successWithInfo('数据删除成功', 204);
        } else {
            return $this->errorWithInfo('数据删除失败，请查看指定的数据是否存在');
        }
    }

    protected function destroyHandle($id) {
        DB::transaction(function () use($id) {
            // 删除逻辑  注意多表关联的情况
            $this->model::where('id', $id)->delete();
        });
        return true;
    }
    public function deleteAll()
    {
        // 前端利用json格式传递数据
        $ids = json_decode(request()->input('ids'),true);
        foreach ($ids as $id) {
            $this->destoryHandle($id);
        }
        return $this->successWithInfo('批量删除数据成功', 204);
    }



    public function export()
    {
        $data = $this->model::all();
        $data = $data->toArray();
        $arr = $this->exportHandle($data);
        $data = collect($arr);
        $fileName = time().'.xlsx';
        $file = 'xls\\'.$fileName;
        (new FastExcel($data))->export($file);
        return $this->successWithInfo($file);
    }

    protected function exportHandle($arrData){
        // 默认会根据$map进行处理，
        $arr = [];
        foreach ($arrData as $item) {
            $tempArr = $this->handleItem($item, 'export');
            // 根据需要$tempArr可以进一步处理，特殊的内容，默认$tempArr是根据$this->map来处理
            $arr[] = $tempArr;
        }
        return $arr;
    }


    /**
     * 根据map表，处理数据
     * @param $data
     */
    protected function handleItem($data, $type = 'export'){
        $arr = [];
        if ($type === 'export'){
            foreach ($this->map as $key => $item){
                if (!isset($data[$item])){
                    continue;
                }
                $arr[$key] = $data[$item];
            }
        }
        if ($type === 'import'){
            foreach ($this->map as $key => $item){
                if (!isset($data[$key])){
                    continue;
                }
                $arr[$item] = $data[$key];
            }
        }
        return $arr;
    }


    public function import()
    {
//        1.接收文件，打开数据
//        2. 处理打开的数据，循环转换
//        3. 导入到数据库
        $data = (new FastExcel())->import(request()->file('file'));
        $arrData = $data->toArray();
        $arr = $this->importHandle($arrData);
        $this->model::insert($arr['successData']);
        $tips = '当前操作导入数据成功'.$arr['successCount'].'条';
        if ($arr['isError']) {
            // 有失败的数据，无法插入，要显示出来，让前端能下载
            $file = time().'.xlsx';
            $fileName = public_path('xls').'\\'.$file;
            $file = 'xls\\'.$file;
            $data = collect($arr['errorData']);
            (new FastExcel($data))->export($fileName);
            $tips .= ',失败'.$arr['errorCount'].'条';
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


   protected function  message(){
       return [];
   }

}
