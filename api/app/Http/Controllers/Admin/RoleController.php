<?php

namespace App\Http\Controllers\Admin;

use App\Http\Resources\RoleCollection;
use App\Models\Role;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

/**
 * @group 角色管理
 * @package App\Http\Controllers\Admin
 */
class RoleController extends Controller
{
    protected $table = 'roles';
    /**
     * 显示角色列表
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $data = Role::all();
        return new RoleCollection($data);

    }


    /**
     * 新增角色
     * @bodyParam name string required 角色名称 Example: manger
     * @bodyParam desc string optional 角色说明 Example: 运营者
     * @bodyParam permissions Object required 权限列表 Example: [{"module": "goods", "permissions":["index", "show"]}]
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       $data = $request->only(['name', 'desc', 'permissions']);
       if (!is_bool($result = $this->validateStore($data))){
            return $this->errorWithInfo($result, 422);
       }
//       $data = $data->toArray();
       // 构建数据到角色和角色权限表里面
        DB::transaction(function() use($data){
            $data['created_at'] = Carbon::now();
            $permissions = $data['permissions'];
            unset($data['permissions']);
            $roleId = DB::table($this->table)->insertGetId($data);
            $obj = $permissions;
            $result = [];
            foreach ($obj as $item) {
               $moduleId = $this->getModuleIdByName($item['module']);
                foreach($item['permissions'] as $v) {
                    $permissionId = DB::table('permissions')->where('name', $v)->where('module_id', $moduleId)->value('id');
                    $result [] = [
                      'role_id' => $roleId,
                      'permission_id' => $permissionId,
                      'created_at' => Carbon::now()
                    ];
                }
            }
            DB::table('role_permissions')->insert($result);
        });
        return $this->successWithInfo('角色建立成功', 201);
    }

    /**
     * 显示详情
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function show(Role $role)
    {
        return new \App\Http\Resources\Role($role);
    }


    /**
     * 更新角色
     * @bodyParam name string required 角色名称 Example: manger
     * @bodyParam desc string optional 角色说明 Example: 运营者
     * @bodyParam permissions array required 权限列表 Example: [{"module": "goods", "permissions":["index", "show"]}]
     */
    public function update(Request $request, Role $role)
    {
        //
        $data = $request->only(['name', 'desc', 'permissions']);
        if (!is_bool($result = $this->validateUpdate($data, $role->id))){
            return $this->errorWithInfo($result, 422);
        }

        DB::transaction(function() use($data, $role){
             $permissions = $data['permissions'];
             // 已经存在的功能组权限
             $existPermissions = DB::table('role_permissions')->where('role_id', $role->id)->pluck('permission_id')->toArray();
             // 获取读取的需要设置的权限
             $arrPermissions = [];

             foreach ($permissions as $item) {
                $moduleId = DB::table('modules')->where('name', $item['module'])->value('id');
                $readPermissions = DB::table('permissions')->where('module_id', $moduleId)->whereIn('name', $item['permissions'])->pluck('id')->toArray();
                $arrPermissions = array_merge($arrPermissions, $readPermissions);
             }
             $result = [];
             // 需要增加的
             $arrTemp1 = array_diff($arrPermissions, $existPermissions);
             // 必须删除的
             $arrTemp2 = array_diff($existPermissions, $arrPermissions);
             foreach ($arrTemp1 as $v){
                        $result [] = [
                           'role_id' => $role->id,
                           'permission_id' => $v,
                           'created_at' => Carbon::now()
                        ];
             }
             if (count($result)>=0) {
               DB::table('role_permissions')->insert($result);
             }
             DB::table('role_permissions')->whereIn('permission_id', $arrTemp2)->where('role_id', $role->id)->delete();
        });
        return $this->successWithInfo('模块修改成功', 200);

    }

    /**
     * 删除角色信息     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        //
        if ($role->name !== "admin") {
               DB::transaction(function() use($role){
               DB::table('admin_roles')->where('role_id', $role->id)->delete();
               DB::table('role_permissions')->where('role_id', $role->id)->delete();
               $role->delete();
            });
            return $this->successWithInfo('角色删除成功');
        } else {
            return $this->errorWithInfo('无法删除超级管理员角色', 400);
        }

    }

    protected function getModuleIdByName($name) {
       $id = DB::table('modules')->where('name', $name)->value('id');
       return $id;
    }

     protected function message()
    {
        return [
           'name.required' => '模块名称(name字段)不能为空',
            'name.unique' => '模块名称(name字段)不能重复',
           'permissions.required' => '权限列表(permissions字段)不能为空',
           'permissions.array' => '权限列表(permissions字段)类型必须为数组'
        ];

    }

    protected function storeRule()
    {
        return [
          'name' => 'required|unique:modules',
          'permissions' => 'required|array'
        ];
    }

    protected function updateRule($id)
    {

        return [
          'name' => [
              'required',
              Rule::unique($this->table)->ignore($id)
          ],
          'permissions' => 'required|array'
        ];
    }

}
