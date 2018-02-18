<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoleRequest;
use App\Http\Resources\RoleCollection;
use App\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
     use Result;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    /**
     * @api {get} /api/role 显示学期列表
     * @apiGroup role
     *
     *
     * @apiSuccessExample 返回所有的角色
     * HTTP/1.1 200 OK
     *  {
     *  "data": [
     *  {
     *  "id": 2,
     *  "name": "admin",
     *  "explain": "管理员",
     *  "remark": null
     *  }
     *  ],
     *  "status": "success",
     *  "status_code": 200,
     *  "links": {
     *  "first": "http://manger.test/api/role?page=1",
     *  "last": "http://manger.test/api/role?page=1",
     *  "prev": null,
     *  "next": null
     *  },
     *  "meta": {
     *  "current_page": 1,
     *  "from": 1,
     *   "last_page": 1,
     *  "path": "http://manger.test/api/role",
     *  "per_page": 15,
     *  "to": 30,
     *  "total": 5
     *  }
     *  }
     **/
    public function index()
    {
        //
        $data = Role::paginate(30);
        return new RoleCollection($data);
    }

    public function getRoles()
    {
        $data = Role::select('name', 'explain')->get();
        return $this->successWithData($data);

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
    /**
     * @api {post}/api/role 新建一条角色信息
     * @apiGroup role
     * @apiParam {string} name 角色名称
     * @apiParam {string} explain 角色说明
     * @apiParam {string} [remark] 角色备注 可选
     * @apiParamExample {object} 请求事例 建
     * {
     * name: 'app',
     * explain: '应用管理者'
     * }
     *@apiHeaderExample {json} 请求头:
     *{ "Content-Type": "application/x-www-form-urlencoded" }
     *
     * @apiSuccessExample {json} 操作成功:
     * HTTP/1.1 200 OK
     * {
     * "status": "success",
     * "status_code": 200
     * }
     * @apiErrorExample {json} 数据验证出错:
     * HTTP/1.1 404 Not Found
     * {
     * "status": "error",
     * "status_code": 404,
     * }
     */
    public function store(RoleRequest $request)
    {
        //
        $data = $request->only(['name', 'explain', 'remark']);
        if (Role::updateOrCreate($data)) {
            return $this->success();
        } else {
            return $this->error();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Role  $role
     * @return \Illuminate\Http\Response
     */
    /**
     * @api {get} /api/role/:id 获取一条角色
     * @apiGroup role
     * @apiParam {number} id 角色标识
     * @apiSuccessExample {json} 信息获取成功:
     * HTTP/1.1 200 OK
     * {
     * "data": [
     *  {
     *  "id": 2,
     *  "name": "admin",
     *  "explain": "管理员",
     *  "remark": null
     *  }
     * ],
     * "status": "success",
     * "status_code": 200
     * }
     * @apiErrorExample {json} 指定的角色不存在:
     * HTTP/1.1 404 Not Found
     * {
     * "status": "error",
     * "status_code": 404
     * }
     */
    public function show(Role $role)
    {
        //
        return new \App\Http\Resources\Role($role);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
        //


    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Role  $role
     * @return \Illuminate\Http\Response
     */

    /**
     * @api {patch}/api/role/:id 更新角色信息
     * @apiGroup role
     * @apiParam {number} id 角色标识 路由上使用
     * @apiParam {string} name 角色名称
     * @apiParam {string} explain 角色描述
     * @apiParam {string} [remark] 备注 可选
     * @apiParamExample {object} 请求事例 建立学期 2017-2018上学期:
     * {
     * name: 'admin',
     * explain: '管理员',
     * remark: '管理员'
     * }
     *@apiHeaderExample {json} 请求头:
     *{ "Content-Type": "application/x-www-form-urlencoded" }
     * @apiSuccessExample {json} 操作成功:
     * HTTP/1.1 200 OK
     * {
     * "status": "success",
     * "status_code": 200
     * }
     * @apiErrorExample {json} 数据验证出错:
     * HTTP/1.1 404 Not Found
     * {
     * "status": "error",
     * "status_code": 404
     * }
     */
    public function update(RoleRequest $request, Role $role)
    {
        //
        $data = $request->only(['name', 'explain', 'remark', 'permission']);
        $role->name = $data['name'];
        $role->explain = $data['explain'];
        $role->permission = implode(',', $data['permission']);
        $role->remark = $data['remark']??null;
        if ($role->save()) {
            return $this->success();
        } else {
            return $this->error();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Role  $role
     * @return \Illuminate\Http\Response
     */
    /**
     * @api {delete} /api/role/:id 删除指定的角色信息
     * @apiGroup role
     * @apiParam {number} id 角色标识
     * @apiSuccessExample {json} 信息获取成功:
     * HTTP/1.1 200 OK
     * {
     * "status": "success",
     * "status_code": 200
     * }
     * @apiErrorExample {json} 删除失败，没有指定的角色:
     * HTTP/1.1 404 Not Found
     * {
     * "status": "error",
     * "status_code": 404，
     * "message": "删除失败"
     * }
     */
    public function destroy(Role $role)
    {
        //
        if ($role->delete()) {
            return $this->success();
        } else {
            return $this->error();
        }
    }

    public function addPermission(Request $request)
    {
        $data = $request->only(['id', 'name', 'permission', 'explain', 'remark']);

    }
}
