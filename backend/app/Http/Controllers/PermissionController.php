<?php

namespace App\Http\Controllers;

use App\Http\Requests\PermissionRequest;
use App\Http\Resources\PermissionCollection;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PermissionController extends Controller
{
    use Result;

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
                // 转换为无极限分类格式，分类下的所有API都在一起
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
        //return new PermissionCollection($lists);
    }


    public function create()
    {
        //
    }


    public function store(PermissionRequest $request)
    {
        //
        $data = $request->only(['name', 'pid', 'type', 'method', 'route_name', 'route_match', 'remark']);

        if (Permission::create($data)){
            return $this->success();
        } else {
            return $this->error();
        }
    }


    public function show(Permission $permission)
    {
        //
        return new \App\Http\Resources\Permission($permission);
    }


    public function edit(Permission $permission)
    {
        //
    }

    public function update(PermissionRequest  $request, Permission $permission)
    {
        //
        $data = $request->only(['name', 'pid', 'type', 'method', 'route_name', 'route_match', 'remark']);
        $permission->name = $data['name'];
        $permission->pid= $data['pid'];
        $permission->type = $data['type'];
        $permission->method = $data['method'];
        $permission->route_name = $data['route_name'];
        $permission->route_match = isset($data['route_match'])?$data['route_match']:$permission->route_match;
        $permission->remark = isset($data['remark'])?$data['remark']:$permission->remark;
        if ($permission->save()) {
            return $this->success();
        } else {
            return $this->error();
        }
    }

    public function destroy(Permission $permission)
    {
        //
        if ($permission->delete()) {
             return $this->success();
        } else {
             return $this->error();
        }
    }

    public function addGroup(Request $request)
    {
        $data = $request->only(['name', 'remark']);
        $rules = [
            'name' => 'required|unique|string'
        ];
        $messages = [
            'name.requried' => '功能组名称必须填写',
            'name.unique' => '功能组名称已经存在，无法建立'
        ];
        Validator::make($data, $rules, $messages);

        $data['pid'] = 0;
        $data['type'] = 1;
        if (Permission::create($data)) {
            return $this->success();
        } else {
            return $this->error();
        }
    }

    public function getGroup()
    {
        $lists = Permission::where('pid', 0)
                            ->where('type', 1)
                            ->get();
        return $this->successWithData($lists);
    }


    public function getPermissionByTree()
    {
        // 获取权限数据 用于在树形控件中显示
        $lists = Permission::select(DB::raw('id, name as label, pid'))->get();
        $arr = $lists->toArray();
        $arr1 = $this->make_tree($arr);
        return $this->successWithData($arr1);
    }

    public function make_tree($arr){
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

    public function getModel()
    {
        return 'App\Models\Permission';
    }

}
