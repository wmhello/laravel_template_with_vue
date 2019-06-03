<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class Role extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $arrPermissions = explode(',', $this->permissions);
        array_walk($arrPermissions, function(&$value){
            $value = (int)$value;
        });
        return [
            'id' => $this->id,
            'name' => $this->name,
            'explain' => $this->explain,
            'permissions' => $arrPermissions,
            'remark' => $this->remark
        ];
    }

    public function with($request)
    {
        return [
            'status' => 'success',
            'status_code' => 200
        ];
    }

    protected function arrHandle($value){
        return (int)$value;
    }
}
