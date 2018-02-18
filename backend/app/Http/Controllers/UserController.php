<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Import\UserImport;
use App\Http\Resources\UserCollection;
use App\Models\Permission;
use App\Role;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
   use Result;

    /**
     * @api {get} /api/admin 显示管理员列表
     * @apiGroup admin
     *
     *
     * @apiSuccessExample 返回管理员信息列表
     * HTTP/1.1 200 OK
     * {
     *  "data": [
     *     {
     *       "id": 2 // 整数型  用户标识
     *       "name": "test"  //字符型 用户昵称
     *       "email": "test@qq.com"  // 字符型 用户email，管理员登录时的email
     *       "role": "admin" // 字符型 角色  可以取得值为admin或editor
     *       "avatar": "" // 字符型 用户的头像图片
     *     }
     *   ],
     * "status": "success",
     * "status_code": 200,
     * "links": {
     * "first": "http://manger.test/api/admin?page=1",
     * "last": "http://manger.test/api/admin?page=19",
     * "prev": null,
     * "next": "http://manger.test/api/admin?page=2"
     * },
     * "meta": {
     * "current_page": 1, // 当前页
     * "from": 1, //当前页开始的记录
     * "last_page": 19, //总页数
     * "path": "http://manger.test/api/admin",
     * "per_page": 15,
     * "to": 15, //当前页结束的记录
     * "total": 271  // 总条数
     * }
     * }
     *
     */
    public function index(Request $request)
    {
        //
        $pageSize = (int)$request->input('pageSize');
        $pageSize = isset($pageSize) && $pageSize?$pageSize:10;
        $users = User::name()->email()->paginate($pageSize);
        return new UserCollection($users);
    }


    public function create(Request $request)
    {

    }

    /**
     * @api {post} /api/admin  建立新的管理员
     * @apiGroup admin
     * @apiParam {string} name 用户昵称
     * @apiParam {string} email 用户登陆名　email格式 必须唯一
     * @apiParam {string} password 用户登陆密码
     * @apiParam {string="admin","editor"} [role="editor"] 角色 内容为空或者其他的都设置为editor
     * @apiParam {string} [avatar] 用户头像地址
     * @apiParamExample {json} 请求的参数例子:
     *     {
     *       name: 'test',
     *       email: '1111@qq.com',
     *       password: '123456',
     *       role: 'editor',
     *       avatar: 'uploads/20178989.png'
     *     }
     *
     * @apiSuccessExample 新建用户成功
     * HTTP/1.1 201 OK
     * {
     * "status": "success",
     * "status_code": 201
     * }
     * @apiErrorExample 数据验证出错
     * HTTP/1.1 404 Not Found
     * {
     * "status": "error",
     * "status_code": 404,
     * "message": "信息提交不完全或者不规范，校验不通过，请重新提交"
     * }
     */
    public function store(Request $request)
    {
        //  新建管理员信息
        $data = $request->only(['name', 'role', 'password','password_confirmation', 'email', 'avatar']);
        $rules = [
            'name'=>'required',
            'role' =>'nullable|array',
            'password' => 'required|confirmed',
            'email' => 'required|unique:users',
            'avatar' => 'nullable|string'
        ];
       $message = [
           'name.required' => '用户名是必填项',
           'password.required' => '用户密码是必填项',
           'password.confirmed' => '两次输入的密码不匹配',
           'email.required' => '登录名是必填项',
           'email.unique' => '登录名已经存在，请重新填写',
       ];
       $validator = Validator::make($data, $rules, $message);
       if ($validator->fails()) {
           $errors = $validator->errors($validator);
           return $this->errorWithCodeAndInfo(422, $errors);
       }
       $data['password'] = bcrypt($data['password']);
       $data['role'] = implode(',', $data['role']);
       if (User::create($data)) {
            return $this->success();
       }
    }


    /**
     * @api {get} /api/admin/:id 显示指定的管理员
     * @apiGroup admin
     *
     *
     * @apiSuccessExample 返回管理员信息
     * HTTP/1.1 200 OK
     * {
     * "data": {
     *   "id": 1,
     *   "name": "wmhello",
     *   "email": "871228582@qq.com",
     *   "role": "admin",
     *   "avatar": ""
     * },
     * "status": "success",
     * "status_code": 200
     * }
     *
     */
    public function show($id)
    {
        //
       $user =  User::find($id);
       return new \App\Http\Resources\User($user);
    }

    public function edit($id)
    {
        //
    }


    /**
     * @api {put} /api/admin/:id  更新指定的管理员
     * @apiGroup admin
     * @apiHeaderExample {json} http头部请求:
     *     {
     *       "content-type": "application/x-www-form-urlencoded"
     *     }
     * @apiParam {string} name 用户昵称
     * @apiParam {string="admin","editor"} [role=editor] 角色 内容为空或者其他的都设置为editor
     * @apiParam {string} [avatar] 用户头像地址
     * @apiParamExample {json} 请求参数例子
     *{
     *      name: 'test',
     *      role: 'editor',
     *      avatar: 'uploads/20174356.png'
     * }
     * @apiSuccessExample 返回密码设置成功的结果
     * HTTP/1.1 200 OK
     * {
     * "status": "success",
     * "status_code": 200
     * }
     * @apiErrorExample 数据验证出错
     * HTTP/1.1 404 Not Found
     * {
     * "status": "error",
     * "status_code": 404,
     * "message": "信息提交不完全或者不规范，校验不通过，请重新提交"
     * }
     */

    public function update(Request $request, $id)
    {

        $all = $request->validate([
            'name' => 'required|string',
            'role' => 'required|array',
            'avatar' =>'nullable|string'
        ]);
        $roles = $all['role'];
        $all['role'] = implode(',', $roles); // 把前台传回的数组型角色字段转化为字符型存入数据表
        if (! (isset($all['role']) && $all['role'])) { // 没有填写内容 则删除，默认为user角色
            //array_except($all, 'role');
            $all['role'] = 'user';
        }
        $bool = User::where('id', $id)->update($all);
        if ($bool) {
            return $this->success();
        }
    }

    /**
     * @api {delete} /api/admin/:id  删除指定的管理员
     * @apiGroup admin
     *
     * @apiSuccessExample 用户删除成功
     * HTTP/1.1 200 OK
     * {
     * "status": "success",
     * "status_code": 200
     * }
     *
     * @apiErrorExample 用户删除失败
     * HTTP/1.1 404 ERROR
     * {
     * "status": "error",
     * "status_code": 404
     * }
     */

    public function destroy($id)
    {
        //
        $user = User::find($id);
        if ($user->delete()) {
            return $this->success();
        } else {
            return $this->error();
        }

    }

    /**
     * @api {post} /api/admin/:id/reset  重置指定管理员的密码
     * @apiGroup admin
     *
     * @apiParam {string} password 用户密码
     *
     * @apiSuccessExample 返回密码设置成功的结果
     * HTTP/1.1 200 OK
     * {
     * "status": "success",
     * "status_code": 200
     * }
     *
     */
    public function reset(Request $request, $id)
    {
        $password = $request->input('password');
        $user = User::find($id);
        $user->password = bcrypt($password);
        $user->save();
        return $this->success();
    }

    /**
     * @api {post} /api/admin/upload  头像图片上传
     * @apiGroup admin
     * @apiHeaderExample {json} http头部请求:
     *     {
     *       "content-type": "application/form-data"
     *     }
     *
     * @apiSuccessExample 上传成功
     * HTTP/1.1 200 OK
     * {
     * "status": "success",
     * "status_code": 200，
     * "data": {
     *   "url" : 'uploads/3201278123689.png'
     *  }
     * }
     *
     * @apiErrorExample 上传失败
     * HTTP/1.1 400 ERROR
     * {
     * "status": "error",
     * "status_code": 400
     * }
     */

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

    /**
     * 修改个人密码
     * 获取三个字段，oldPassword => 原来密码  password=>新密码 password_confirmation
     * 原密码相同才能修改密码为新密码
     */
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
                $validator->errors()->add('oldPassword', '原密码错误');
            }
        });
        if ($validator->fails()) {
            $errors = $validator->errors($validator); //返回一次性错误
            return $this->errorWithCodeAndInfo(422,$errors);
        }
        $user->password = bcrypt($password);
        if ($user->save()) {
            return $this->success();
        } else {
            return $this->error();
        }
        
    }

    /**
     * @api {get} /api/user 获取当前登陆的用户信息
     * @apiGroup login
     *
     *
     * @apiSuccessExample 信息获取成功
     * HTTP/1.1 200 OK
     *{
     * "data": {
     *    "id": 1,
     *    "name": "xxx",
     *    "email": "xxx@qq.com",
     *    "roles": "xxx", //角色: admin或者editor
     *    "avatar": ""
     *  },
     *  "status": "success",
     *  "status_code": 200
     *}
     */
    public function getUserInfo(Request $request)
    {
        // 获取用户信息和用户组对应的用户权限
        // 用户权限
        $user = $request->user();
        $roles = explode(',',$user['role']);
        $data = [
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'role' => $roles,
            'avatar' => $user['avatar']
        ];
        // 用户权限
        $feature = \App\Role::whereIn('name',$roles)->pluck('permission');
        $feature = $feature->toArray();
        $strPermission = implode(',', $feature);
        $permissions = explode(',', $strPermission);
        $feature = Permission::select(['route_name', 'method', 'route_match', 'id'])->whereIn('id',$permissions)->get();
        $feature = $feature->toArray();
        $data['permission'] = $feature;
        return response()->json([
            'data' => $data,
            'status' => 'success',
            'status_code' => 200,
        ],200);
    }

    public function upload(UserImport $import)
    {
        $bool = $import->handleImport($import);
        if ($bool) {
            return $this->success();
        } else {
            return $this->error();
        }
    }

    public function exportAll(Request $request) {

        // $rec = User::count(); // 获得总记录数,因为是所有的数据
        $this->generator(null, 1);
    }

    public function export(Request $request)
    {
        $pageSize = (int)$request->input('pageSize');
        $pageSize = isset($pageSize) && $pageSize? $pageSize: 10;
        $page = (int)$request->input('page');
        $page = isset($page) && $page ? $page: 1;
        $this->generator($pageSize, $page);
    }

    public function generator($pageSize, $page)

    {

        $name = (int)request()->input('name');
        $email= (int)request()->input('email');

        $name = (isset($name)&&$name)?$name: null;
        $email = (isset($email)&&$email)?$email: null;

        $lists = $this->queryData($pageSize, $page,$name, $email);

        $data = $lists->toArray();  // 分页内容

        $items = $this->generatorData($data);
        $this->generatorXls($items);
    }

    protected  function queryData($pageSize = null, $page = 1, $name, $email){
        // 查询条件  根据姓名或者电话号码进行查询
        $offset = $pageSize * ($page - 1) == 0? 0: $pageSize * ($page - 1);
        $lists = User::select('name', 'email', 'role')
                       ->name()
                       ->email()
                       ->when($pageSize,function($query) use($offset, $pageSize) {
                              return $query->offset($offset)->limit($pageSize);
                       })
                       ->get();

        return $lists;
    }

    /**
     * 根据传入的数据生成内容
     * @param $data
     * @return array
     */
    protected function generatorData($data): array
    {
        $items = [];
        // $data = $data['data'];  // 数据库中的数据
        $arrRoles = Role::pluck('explain', 'name')->all();
        foreach ($data as $item) {
            $arr = [];
            $arr['name'] = $item['name'];
            $arr['email'] = $item['email'];
            $tmpRoles = explode(',', $item['role']);
            $strRoles = '';
            foreach ($tmpRoles as $tmp) {
              $strRoles .= $arrRoles[$tmp].',';
            }
            $arr['role'] = substr($strRoles,0, -1);
            array_push($items, $arr);
        }
        array_unshift($items, ['姓名', '登录名', '角色']);
        return $items;
    }

    /**
     * 生成xls文件  名称叫做员工信息
     */
    protected function generatorXls($items): void
    {
        $file = time();
        Excel::create('用户管理', function ($excel) use ($items) {
            $excel->sheet('score', function ($sheet) use ($items) {
                $sheet->rows($items);
            });
        })->store('xls', public_path('xls'));
    }

    public function test()
    {
        $str = 'abacde,';
        dump(substr($str,0,-1));
    }

    public function deleteAll(Request $request)
    {
      $data = $request->only('ids');
      $rules = [
          'ids' => 'required | Array'
      ];
      $messages = [
          'ids.required' => '必须选择相应的记录',
          'ids.Array' => 'ids字段必须是数组'
      ];

      $validator = Validator::make($data, $rules, $messages);
      if ($validator->fails()) {
          $errors = $validator->error($validator);
          return $this->errorWithCodeAndInfo(422, $errors);
      }

      if (User::destroy($data['ids'])) {
          return $this->success();
      } else {
          return $this->error();
      }

    }
}
