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
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $pageSize = $request->input('pageSize', 10);
        $page = $request->input('page', 1);
        // $lists  = Permission::Name()->Pid()->Type()->paginate($pageSize);
        $lists  = Permission::Name()->Pid()->Type()->get();
        $count = Permission::Name()->Pid()->Type()->count();
        $items = $lists->toArray();
        $list_data = $items;
        $end = $list_data;
        if ( !$request->has('type')) {
            // 转换为无极限分类格式，分类下的所有API都在一起
            $data = $this->make_tree($list_data);
            $end = [];
            foreach($data as $item) {
                if (is_array($item['children'])) {
                    $values = $item['children'];
                    unset($item['children']);
                    array_push($end, $item);
                    $end = array_merge($end, $values);
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function show(Permission $permission)
    {
        //
        return new \App\Http\Resources\Permission($permission);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function edit(Permission $permission)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Permission  $permission
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Permission  $permission
     * @return \Illuminate\Http\Response
     */
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

    public function deleteAll(Request $request)
    {
        $data = $this->deleteByIds($request);
        if ($data) {
            if (Permission::destroy($data['ids'])) {
                return $this->success();
            } else {
                return $this->error();
            }
        }
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

}
