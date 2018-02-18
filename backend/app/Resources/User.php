<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class User extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'role' => explode(',',$this->role),
            'avatar' => $this->avatar
        ];
    }

    public function with($request)
    {
        return [
           'status' => 'success',
           'status_code' => 200,
        ];
    }
}
