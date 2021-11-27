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

        $data = DB::table('v_admin_permissions')->where('admin_id', $id)->pluck('full_permissions');
        return $data;
    }

}
