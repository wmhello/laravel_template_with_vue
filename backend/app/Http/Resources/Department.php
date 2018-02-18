<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class Department extends Resource
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
            'session_id' => $this->session_id,
            'teacher_id' => $this->teacher_id,
            'teach_id' => $this->teach_id,
            'leader' => $this->leader,
            'grade' => $this->grade,
            'remark' => $this->remark,
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
