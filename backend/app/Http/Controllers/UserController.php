<?php
/**
 * Created by ubuy.test.
 * User: wmhello
 * Email: 871228582@qq.com
 * Date: 2019/5/20 0020
 */

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Rap2hpoutre\FastExcel\FastExcel;

class UserController extends Controller
{
    protected  $model = 'App\Models\User'; // 模型类
    protected  $fillable = ['name', 'email', 'password', 'roles', 'avatar', 'remark']; //  模型可以批量修改的数据，用于在新建和修改是读取从前端传入的内容
    protected  $resource = 'App\Http\Resources\User'; // 显示个体资源
    protected  $resourceCollection = 'App\Http\Resources\UserCollection'; // 显示资源集合
    protected $map = [
        '用户昵称' => 'name',
        '登录名' => 'email',
        '角色'  => 'roles',
        '头像' => 'avatar',
        '备注' => 'remark'
    ];   // 导入导出数据的时候  数据表字段与说明的映射表
    protected  $downloadData = [
            '用户昵称' => '随意，不可重复',
            '登录名' => '随意，不可重复，建议使用电子邮箱',
            '备注' => '该用户的信息备注，可以选填'
    ];

    protected function getListData($pageSize)
    {
        // index方法调用该类，显示不同的内容
          $data = $this->model::name()->email()->paginate($pageSize);  // 查找内容的修改
          return new $this->resourceCollection($data);
       // return parent::getListData($pageSize);   // 分页显示每个表的记录
    }


    protected function storeHandle($data)
    {
        $data['password'] = bcrypt($data['password']);  // 需要在保存内容之前做相应的处理，则添加
        if (!isset($data['roles'])) {
          $data['roles'] = 'user';   // 默认值的情况，可以通过数据表直接设置，也可以通过程序来设置
        }
        $data['roles'] = implode(',' , $data['roles']);
        return parent::storeHandle($data);   // TODO: 调用父类
    }


    protected  function  updateHandle($data){
        // 保存到数据表之前进一步处理数据，用于数据的转换等操作
        if (!isset($data['roles'])) {
            $data['roles'] = 'user';   // 默认值的情况，可以通过数据表直接设置，也可以通过程序来设置
        }
        $data['roles'] = implode(',', $data['roles']);
        isset($data['remark']);
        isset($data['password']);
        isset($data['email']);
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
            // 可以根据需要，进一步处理数据
            $data['password'] = bcrypt('123456');
            $data['created_at'] = Carbon::now();
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
            'name' => ['required', Rule::unique('users')->ignore($id)]
        ];
    }

    /**
     * @return array
     * 增加业务是进行表单验证
     */
    protected function storeRule(){
        // 增加数据时的验证规则
        return [
            'name' => 'required|unique:users',
            'email' => 'required|unique:users',
            'password' => 'required'
        ];
    }

    /**
     * @return array
     * 表单验证时显示的错误信息
     */
    protected function message() {
      // 验证出错是的提示信息
        return [
            'name.required' => '用户昵称必须填写',
            'name.unique' => '用户昵称不能重复',
            'email.required' => '登录名必须填写',
            'email.unique' => '登录名不能重复',
            'password.required' => '密码必填填写'
        ];
    }


    public function info()
    {
        // 获取用户信息和用户组对应的用户权限
        // 用户权限
        $user = request()->user();
        $roles = explode(',',$user['roles']);
        $data = [
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'roles' => $roles,
            'avatar' => $user['avatar']
        ];
        // 用户权限
        $feature = Role::whereIn('name',$roles)->pluck('permissions');
        $feature = $feature->toArray();
        $strPermission = implode(',', $feature);
        $permissions = explode(',', $strPermission);

        $feature = Permission::whereIn('id',$permissions)->pluck('route_name');
//        $feature = $feature->toArray();
//
        $data['permissions'] = $feature;
        return response()->json($data,200);
    }

    public function reset(Request $request, $id)
    {
        $password = $request->input('password');
        $user = $this->model::find($id);
        $user->password = bcrypt($password);
        $user->save();
        return $this->success();
    }

    public function modify(Request $request)
    {

        $oldPassword = $request->input('oldPassword');
        $password = $request->input('password');
        $data = $request->all();
        $rules = [
            'oldPassword'=>'required|between:6,20',
            'password'=>'required|between:6,20|confirmed',
        ];
        $messages = [
            'required' => '密码不能为空',
            'between' => '密码必须是6~20位之间',
            'confirmed' => '新密码和确认密码不匹配'
        ];
        $validator = Validator::make($data, $rules, $messages);
        $user = Auth::user();
        $validator->after(function($validator) use ($oldPassword, $user) {
            if (!\Hash::check($oldPassword, $user->password)) {
                $validator->errors()->add('oldPassword', '原密码错误,请重新输入正确的原密码后再修改  ');
            }
        });
        if ($validator->fails()) {
            $errors = $validator->errors($validator); //返回一次性错误
            $errorTips = '';
            foreach($errors->all() as $message){
                $errorTips = $errorTips.$message.',';
            }
            $errorTips = substr($errorTips, 0, strlen($errorTips)-1);
            return $this->errorWithInfo($errorTips, 422);
        }
        $user->password = bcrypt($password);
        if ($user->save()) {
            return $this->success();
        } else {
            return $this->error();
        }

    }

    public function uploadAvatar(Request $request)
    {
        if ($request->isMethod('POST')) {
//            var_dump($_FILES);
            $file = $request->file('photo');
            //判断文件是否上传成功
            if ($file->isValid()) {
                //获取原文件名
                $originalName = $file->getClientOriginalName();
                //扩展名
                $ext = $file->getClientOriginalExtension();
                //文件类型
                $type = $file->getClientMimeType();
                //临时绝对路径
                $realPath = $file->getRealPath();

                $filename = date('YmdHiS') . uniqid() . '.' . $ext;

                $bool = Storage::disk('uploads')->put($filename, file_get_contents($realPath));
                //dd( public_path('uploads').'\\'.$filename); 显示路径
                if ($bool) {
                    $filename = 'uploads/' . $filename;
                    return response()->json([
                        'status' => 'success',
                        'status_code' => 200,
                        'data' => [
                            'url' => $filename,
                        ]
                    ], 200);
                } else {
                    return $this->error();
                }
            }
        }
    }

    public function download()
    {
       $file = 'xls/'.time().'.xlsx';
      $data = collect([
          $this->downloadData
      ]);
        return (new FastExcel($data))->download($file);

    }
}