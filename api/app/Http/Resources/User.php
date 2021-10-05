<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class User extends JsonResource
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
            'id' =>  $this->id,
            'nickname' => $this->nickname,
            "email" => $this->email,
            "phone" => $this->phone,
            "avatar" => $this->avatar,
            "status" => $this->status,
            'open_id' => $this->open_id,
            'union_id' => $this->union_id,
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
