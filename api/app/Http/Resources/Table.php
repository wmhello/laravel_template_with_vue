<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Table extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'table_name' => $this->table_name,
            'engine' => $this->engine,
            'table_collation' => $this->table_collation,
            'table_comment' => $this->table_comment,
            'create_time' => $this->create_time,
            'table_config' => $this->table_config
        ];
        return $data;
    }

    public function with($request)
    {
        return [
            'status' => 'success',
            'status_code' => 200
        ];
    }
}
