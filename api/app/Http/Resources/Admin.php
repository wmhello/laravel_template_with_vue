<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class Admin extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'nickname' => $this->nickname,
            "email" => $this->email,
            "phone" => $this->phone,
            "avatar" => $this->avatar,
            "status" => $this->status,
            'roles' => AdminRole::collection($this->admin_roles),
            'permissions' => $this->getPermissions($this->id)
        ];
    }

    public function with($request)
    {
        return [
          'status' => 'success',
          'status_code' => 200
        ];
    }

    protected function getPermissions($id)
    {
//        $roleIds = DB::table('admin_roles')->where('admin_id', $id) ->pluck('role_id');
//        $permissionIds = DB::table('role_permissions')->whereIn('role_id', $roleIds)->pluck('permission_id');
//       dd($id);
        $permissionIds = DB::select("select rp.permission_id from role_permissions as rp where role_id in (select role_id from admin_roles where admin_id = ?)", [$id]);
        $result = [];
        foreach($permissionIds as $item) {
            $result [] = $item->permission_id;
        }
        $data = DB::table(DB::raw('permissions as p'))->join(DB::raw('modules as m'), 'm.id', "=", "p.module_id")
            ->whereIn('p.id', $result)->select(DB::raw("concat(m.name,'.', p.name) as permissions"))->pluck('permissions');
        return $data;
    }

}
