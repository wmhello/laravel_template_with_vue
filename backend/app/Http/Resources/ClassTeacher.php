<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class ClassTeacher extends Resource
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
            'grade' => $this->grade,
            'class_id' => $this->class_id,
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
}
