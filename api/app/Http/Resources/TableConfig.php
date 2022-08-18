<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TableConfig extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
//        $data = parent::toArray($request);
//        $data = [
//          'id' => $this->id?:0,
//          'table_name' => $this->table_name,
//          'column_name' => $this->column_name,
//          'data_type' => $this->data_type,
//          'column_comment' => $this->column_comment,
//          'is_required' => is_bool($this->is_required)?$this->is_required:false,
//
//        ];

        // 数据转换
         $data = parent::toArray($request);
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
