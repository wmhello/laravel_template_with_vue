<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class LogLogin extends Resource
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
            'user_name' => $this->user_name,
            'type' => $this->type,
            'ip' => $this->ip,
            'desc' => $this->desc,
            'created_at' => $this->created_at
        ];
    }
}
