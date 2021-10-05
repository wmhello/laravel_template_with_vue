<?php

namespace App\Http\Controllers\Admin;

use App\Http\Resources\AdminCollection;
use App\Models\Admin;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Rap2hpoutre\FastExcel\Facades\FastExcel;


/**
 * @group 管理员管理
 * @package App\Http\Controllers\Admin
 */
class AdminController extends Controller
{

    use Tool;
    use Validate;
    protected  $fillable = ['nickname', 'email', 'password', 'password_confirmation', 'phone', "avatar", "status", "roles"];
    protected $map = [
        "昵称"=> "nickname",
        "登陆名" => "email",
         "电话号码" => "phone",
         "角色" => "roles"
    ];
    protected $model='App\Models\Admin';
    protected  $resource = ''; // 显示个体资源
    protected  $resourceCollection = ''; // 显示资源集合

    /**
     * 管理员列表.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $page = $request->input('page', 1);
        $pageSize = $request->input('pageSize', 10);
        $data = Admin::Email()->Phone()->paginate($pageSize);
        return new AdminCollection($data);

    }

    /**
     * 新建管理员
     * @bodyParam email string required 登陆名
     * @bodyParam password string required 密码
     * @bodyParam password_confirmation string required 确认密码
     * @bodyParam avatar string optional 头像
     * @bodyParam phone string optional 手机号码
     * @bodyParam roles array  optional 角色（数组，内容为数字）
     */
    public function store(Request $request)
    {
        //
        $data = $request->only($this->fillable);
        if (!is_bool($result = $this->validateStore($data))){
            return $this->errorWithInfo($result, 422);
        }

        $roles = $data['roles'];
        unset($data['roles']);
        unset($data['password_confirmation']);
        $data['password'] = bcrypt($data['password']);
        DB::transaction(function() use($data, $roles){
           $data['created_at'] = Carbon::now();
           $id = DB::table('admins')->insertGetId($data);
           $result = [];
            foreach ($roles as $v) {
                $result [] = [
                  'admin_id' => $id,
                  'role_id' => $v,
                  'created_at'  => Carbon::now()
                ];
           }
           DB::table('admin_roles')->insert($result);
        });
        return $this->successWithInfo('新增用户信息成功', 201);
    }

    /**
     * 管理员详情
     * @param $id
     */
    public function show($id)
    {
        $admin = Admin::find($id);
        return new \App\Http\Resources\Admin($admin);
    }


    /**
     * 修改管理员信息
     * @bodyParam action string required 需要执行的动作(修改密码，修改个人信息，修改状态)
     * @bodyParam nickname string optional 用户昵称
     * @bodyParam avatar string optional 用户头像
     * @bodyParam roles array required 用户角色
     *
     */
    public function update(Request $request, $id)
    {
        //
        $action = $request->input('action', 'update');
        switch ($action) {
            case 'update':
                $data = $request->only(['nickname', 'phone', 'roles', 'avatar']);
                if (!is_bool($result = $this->validateUpdate($data, $id))){
                    return $this->errorWithInfo($result, 422);
                }
                $roles =$data['roles'];
                unset($data['roles']);
                $avatar = $data['avatar'];
                $oldAvatar = $this->model::find($id)->avatar;
                if ($avatar !== $oldAvatar && !empty($oldAvatar)) {
                    // 删除旧的头像文件 http://lv6.test//storage/axS9bUx4LkOFqwFmmfB5f2TRJBXWGmX4neGMR7RR.png
                    $this->deleteAvatar($oldAvatar);
                }
                DB::transaction(function() use($data, $roles, $id){
                    DB::table('admins')->where('id', $id)->update([
                          'nickname' => $data['nickname'],
                          'avatar' => $data['avatar'],
                          'phone' => $data['phone'],
                          'updated_at' => Carbon::now()
                    ]);
                    // 同步角色ID信息
                    // $roles 是现在读取的角色id
                    // $existRoles 指的是现在已经有的角色id
                    $existRoles = DB::table('admin_roles')->where('admin_id', $id)->pluck('role_id')->toArray();
                    // 添加
                    $tmp1 = array_diff($roles, $existRoles); // 需要增加的
                    $result = [];
                    foreach ($tmp1 as $v) {
                        $result [] = [
                          'role_id' => $v,
                          'admin_id' => $id,
                          'created_at' => Carbon::now()
                        ];
                    }
                    DB::table('admin_roles')->insert($result);
                    // 删除
                    $tmp2 = array_diff($existRoles, $roles); // 需要删除的
                    DB::table('admin_roles')->whereIn('role_id', $tmp2)->where('admin_id', $id)->delete();
                });
                return $this->successWithInfo('用户信息修改完成');
                break;
            case 'status':
                $status = $request->input('status');
                if (in_array($status, [0,1])) {
                   DB::table('admins')->where('id', $id)->update([
                      'status' => $status
                   ]);
                } else {
                    return $this->errorWithInfo('必须填写状态标识', 422);
                }
                return $this->successWithInfo('用户状态修改完成');
                break;
            case 'reset':
                $pwd = $request->input('password');
                if ($pwd){
                    DB::table('admins')->where('id', $id)->update([
                        'password' => bcrypt($pwd)
                    ]);
                } else {
                    return $this->errorWithInfo( '必须填写密码' , 422);
                }
                return $this->successWithInfo('用户密码修改完成');
                break;
        }
    }

    public function export()
    {

    }

    public function import()
    {
        $action = request()->input("action", "import");
        switch ($action) {
            case 'download':
                $result = [];
                $result [] = [
                  "昵称" => "xpyzwm",
                  "登录名" => "xpyzwm",
                  "密码" => "123456",
                  "电话号码" => "13577728948",
                  "角色" => 'user'
                ];
                $list = collect($result);
                // 直接下载
                return (FastExcel::data($list))->download('template.xlsx');
                break;
            case 'import':
                $error = []; // 错误的具体信息
                $isError = false;  // 是否存在信息错误
                $successCount = 0; // 统计数据导入成功的条数
                $errorCount = 0;  // 出错的条数
                $arr = [];  // 正确的内容存储之后，返回数据
                $file = request()->file('file');
                if ($file->isValid()) {
                    $collection = FastExcel::import($file);
                    $arrData = $collection->toArray();
                    $roleData = DB::table('roles')->select("id", "name")->get();
                   foreach ($arrData as $k => $item){
                     $arrRoles = explode(',', $item["角色"]);
                     $roles = [];
                     foreach ($arrRoles as $v) {
                        $role = $roleData->first(function ($value) use ($v) {
                             return  $value->name === $v;
                         });
                         $roles[] = $role->id;
                     }
                     $data = [
                       'nickname' => $item['昵称'],
                       'email' => $item["登录名"],
                       "phone" => (string)$item["电话号码"],
                       "password" => $item["密码"],
                       "password_confirmation" => $item["密码"],
                       "roles" => $roles
                     ];
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
                           $arr [] = $data;
                           DB::transaction(function() use($data){
                            $data['created_at'] = Carbon::now();
                            $data['password'] = bcrypt($data['password']);
                            $roles = $data['roles'];
                            unset($data['roles']);  // 角色不用于管理员表
                            unset($data['password_confirmation']); // 密码确认不需要
                            if (empty($data['phone'])) {  // 电话号码为空，则不能添加进入数据库
                              unset($data['phone']);
                            }
                            $admin_id = DB::table('admins')->insertGetId($data);
                            $tmp = [];
                            foreach ($roles as $v) {
                               $tmp [] = [
                                   'admin_id' => $admin_id,
                                   'role_id' => $v,
                                   'created_at' => Carbon::now()
                               ];
                            }
                            DB::table('admin_roles')->insert($tmp);
                           });
                            $successCount ++;
                     }
                   }
                   $tips = '当前操作导入数据成功' . $successCount . '条';
                   if ($isError) {
                        // 有失败的数据，无法插入，要显示出来，让前端能下载
                        $file = time() . '.xlsx';
                        $domains = getDomain();
                        $fileName = public_path('xls') . '\\' . $file;
                        $file = $domains."xls\\" . $file;
                        $data = collect($error);
                        FastExcel::data($data)->export($fileName);
                        $tips .= ',失败' . $errorCount . '条';
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
                break;
        }
    }

    /**
     * 修改管理员的个人信息
     * @bodyParam action string required 动作(update=>指修改个人信息， update-avatr=>指修改头像和个人信息，reset=>修改密码)
     */
    public function modify(Request $request)
    {
     $action = $request->input('action', 'update');
     switch ($action) {
         // 修改个人密码，需要提供原来密码和现在准备修改的密码
         case "reset":
             $data = $request->only(['old_password', 'password', 'password_confirmation']);
             $old_password = $request->input('old_password');
             $password = $request->input('password');
             $password_confirmation = $request->input('password_confirmation');
             $validator = Validator::make($data, [
                 'old_password' => "required|string|min:6|max:20",
                 'password' => "required|string|min:6|max:20|confirmed",
             ], [
                 'old_password.required' => '原密码必须填写',
                 'password.required' => '新密码必须填写',
                 'password.min' => '新密码的长度不小于6字符',
                 'password.max' => '新密码的长度不大于20字符',
                 'password.confirmed' => '确认密码和新密码必须相同',
             ]);
             if ($validator->fails()) {
                 $info = $this->getErrorInfo($validator);
                 return $this->errorWithInfo($info, 422);
             }
             $id = Auth::guard('api')->id();
             $user = Admin::find($id);
             $pwd = $user->password;
             if (Hash::check($old_password, $pwd)){  // 老密码相等才会修改
                 DB::table('admins')->where('id', $id)->update([
                     'password' => bcrypt($password),
                     'updated_at' => Carbon::now()
                 ]);
                 return $this->successWithInfo("用户密码修改成功");
             } else {
                 return $this->errorWithInfo("你提供的旧密码与原来的不相等，无法修改", 422);
             }
             break;
         case "update-avatar":
             $file = $request->file('file');
             if ($file->isValid()) {
                 $avatar = $this->receiveFile();
                 $data = $request->only(["phone", "nickname"]);
                 $validator = Validator::make($data, [
                     'phone' => "nullable|string|size:11",
                 ], [
                     'phone.size' => '电话号码的长度必须为11位',
                 ]);
                 if ($validator->fails()) {
                     $info = $this->getErrorInfo($validator);
                     return $this->errorWithInfo($info, 422);
                 }
                 if (empty($data['phone'])) {
                     unset($data['phone']);
                 }
                 $data['avatar'] = $avatar;
                 $data['updated_at'] = Carbon::now();
                 $id = Auth::guard('api')->id();
                 $oldAvatar = $this->model::find($id)->avatar;
                 if ($avatar !== $oldAvatar && !empty($oldAvatar)) {
                    // 删除旧的头像文件 http://lv6.test//storage/axS9bUx4LkOFqwFmmfB5f2TRJBXWGmX4neGMR7RR.png
                    $this->deleteAvatar($oldAvatar);
                 }
                 DB::table('admins')->where('id', $id)->update($data);
                 return $this->successWithInfo("用户信息修改成功");
             } else {
                 return $this->errorWithInfo('上传文件失败，估计是文件太大，上传超时', 400);
             }
             break;
         // 默认不包括上传文件，只传数据
         default:
             $data = $request->only(["phone", "nickname"]);
             $validator = Validator::make($data, [
                 'phone' => "nullable|string|size:11",
             ], [
                 'phone.size' => '电话号码的长度必须为11位',
             ]);
             if ($validator->fails()) {
                 $info = $this->getErrorInfo($validator);
                 return $this->errorWithInfo($info, 422);
             }
             if (empty($data['phone'])) {
                 unset($data['phone']);
             }
             $id = Auth::guard('api')->id();;
             $data['updated_at'] = Carbon::now();
             DB::table('admins')->where('id', $id)->update($data);
             return $this->successWithInfo("用户信息修改成功");
         }
    }

    /**
     * 删除管理员
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        DB::transaction(function() use($id){
            // 删除对应的用户角色信息
            DB::table('admin_roles')->where('admin_id', $id)->delete();
            DB::table('admins')->where('id', $id)->delete();
        });
        return $this->successWithInfo('管理员信息删除成功');
    }

    protected function getErrorInfo($validator)
    {
      $errors = $validator->errors($validator);
       $tips = '';
       foreach ($errors->all() as $message){
           $tips .= $message.',';
       }
       $tips = substr($tips,0,strlen($tips)-1);
       return $tips;
    }

    protected function updateRule($id)
    {
        return [
             'roles' => 'required|array',
             'phone' => [
                 'nullable',
                 'size:11',
                 Rule::unique('admins')->ignore($id)
             ]
            ];
    }

    protected function storeRule()
    {
        return [
             'email' => 'required|unique:admins',
             'password' => 'required|confirmed',
             'phone' => 'sometimes|nullable|string|size:11|unique:admins',
             'roles' => 'required|array'
        ];
    }

    public function message()
    {
        return [
            'email.required' => '登陆名必须填写',
            'email.unique' => '登录名不能重复',
            'password.required' => '密码必须填写',
            'password.confirmed' => '两次输入的密码必须一致',

            'phone.size' => '电话号码长度必须为11位',
            'phone.unique' => '电话号码不能重复',
            'roles.required' => '必须选择用户角色',
        ];
    }

}
