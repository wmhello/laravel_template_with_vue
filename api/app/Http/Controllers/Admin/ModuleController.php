<?php

namespace App\Http\Controllers\Admin;

use App\Http\Resources\ModuleCollection;
use App\Models\Module;
use App\Models\Permission;
use App\Models\RolePermission;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;


/**
 * @group 模块和权限管理
 * @package App\Http\Controllers\Admin
 */
class ModuleController extends Controller
{


    protected $arrPermissions = [
      'menu' => '菜单',
      'index' => '列表',
      'show' => '详情',
      'store' => '新增',
      'update'  => '修改',
      'destroy' => '删除',
      'import' => '导入',
      'export' => '导出'
    ];

    protected $table = 'modules';
    /**
     * 显示模块列表
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $data = Module::All();
        return new ModuleCollection($data);
    }



    /**
     * 新增模块信息
     * @bodyParam name string required 模块名称（英文） Example: goods
     * @bodyParam desc string   模块说明（中文）  Example: 商品模块
     * @bodyParam permissions array required 权限列表（数组） Example: ['index', 'show']
     *
     */
    public function store(Request $request)
    {
        //
        $data = $request->only(['name', 'desc', 'permissions']);
        if (!is_bool($result = $this->validateStore($data))){
            return $this->errorWithInfo($result, 422);
        }

        // 构建数据到模型和权限表里面
        DB::transaction(function() use($data){
            $data['created_at'] = Carbon::now();
            $permissions = $data['permissions'];
            unset($data['permissions']);
            $id = DB::table('modules')->insertGetId($data);
            $result = [];
            foreach($permissions as $v) {
                $result [] = [
                  'name' => $v,
                  'desc' => $this->arrPermissions[$v],
                  'module_id' => $id,
                  'created_at' => Carbon::now()
                ];
            }
            DB::table('permissions')->insert($result);
        });
        return $this->successWithInfo('模块建立成功', 201);
    }

    /**
     * 显示模块详情
     *
     */
    public function show(Module $module)
    {
        //
        return new \App\Http\Resources\Module($module);
    }



    /**
     * 修改模块信息
     * @bodyParam name string required 模块名称（英文） Example: goods
     * @bodyParam desc string   模块说明（中文）  Example: 商品模块
     * @bodyParam permissions array required 权限列表（数组） Example: ['index', 'show']
     *
     */
    public function update(Request $request, Module $module)
    {
        $data = $request->only(['name', 'desc', 'permissions']);
        if (!is_bool($result = $this->validateUpdate($data, $module->id))){
            return $this->errorWithInfo($result, 422);
        }

        // 构建数据到模型和权限表里面
        DB::transaction(function() use($data, $module){
            $data['updated_at'] = Carbon::now();
            $permissions = $data['permissions'];
            unset($data['permissions']);
            $id = $module->id;
            $module->update($data);

            // 获取现在所有的数据
             $existPermissions= DB::table('permissions')->where('module_id', $id)->pluck('name')->toArray();
            // 同步数据，先把多余的删除掉
            $delArray = array_diff($existPermissions, $permissions);
            $ids =DB::table('permissions')->where('module_id', $id)->whereIn('name', $delArray)->pluck('id')->toArray();
            RolePermission::whereIn('permission_id', $ids)->delete();
            Permission::destroy($ids); // 批量删除
            // 增加内容
            $addArray = array_diff($permissions, $existPermissions);
            $result = [];
            foreach($addArray as $v) {
                $result [] = [
                  'name' => $v,
                  'desc' => $this->arrPermissions[$v],
                  'module_id' => $id,
                  'created_at' => Carbon::now()
                ];
//                Permission::updateOrCreate($result, $result);
            }
            DB::table('permissions')->insert($result);
        });
        return $this->successWithInfo('模块修改成功', 201);
    }

    /**
     * 删除模块信息
     *
     */
    public function destroy(Module $module)
    {
        //
        DB::transaction(function() use ($module){
             $module_id = $module->id;
             $ids = DB::table('permissions')->where('module_id', $module_id)->pluck('id')->toArray();
             DB::table('role_permissions')->whereIn('permission_id', $ids)->delete();
             DB::table('permissions')->where('module_id', $module_id)->delete();
             DB::table('modules')->where('id', $module->id)->delete();
        });
    }

    protected function message()
    {
        return [
           'name.required' => '模块名称(name字段)不能为空',
            'name.unique' => '模块名称(name字段)不能重复',
           'permissions.required' => '权限列表(permissions字段)不能为空'
        ];
        
    }

    protected function storeRule()
    {
        return [
          'name' => 'required|unique:modules',
          'permissions' => 'required|Array'
        ];
    }

    protected function updateRule($id)
    {

        return [
          'name' => [
              'required',
              Rule::unique($this->table)->ignore($id)
          ],
          'permissions' => 'required|Array'
        ];
    }
}
