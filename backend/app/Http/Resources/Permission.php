<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class Permission extends Resource
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
           'id'=> $this->id,
           'name'=> $this->name,
           'pid'=> $this->pid,
           'type'=> $this->type,
           'method'=> $this->method,
           'route_name'=> $this->route_name,
           'route_match'=> $this->route_match,
           'remark'=> $this->remark
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
