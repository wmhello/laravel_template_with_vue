<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RolePermission extends JsonResource
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
           'role_id' => $this->role_id,
           'role_name' => $this->role->name,
           'role_desc'  => $this->role->desc,
           'permission_id' => $this->permission_id,
           'permission_name' => $this->permission->name,
           'permission_desc' => $this->permission->desc,
           'module_id' =>  $this->permission->module->id,
           'module_name' => $this->permission->module->name,
           'module_desc' => $this->permission->module->desc,
           'module_permission' => $this->permission->module->name.'.'.$this->permission->name
        ];
    }

    public function with($request)
    {
        return [
          'status' => 'success',
          'status_code' => 200
        ];
    }
}
