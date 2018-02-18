<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class Teaching extends Resource
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
            'class_id' => $this->class_id,
            'grade' => $this->grade,
            'hour'  => $this->hour,
            'remark' => $this->remark,
            'classAll' => $this->getAllClass($this->id),
            'oldClassAll' => $this->getAllClass($this->id)
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
