<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Permission extends JsonResource
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
            'name' => $this->name,
            'desc' => $this->desc,
            'module_id' => $this->module_id,
            'module_name' => $this->module->name,
            'module_desc' => $this->module->desc,
            'permission_name' => $this->module->name.'.'.$this->name,
            'created_at' => $this->created_at
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
