<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class Student extends Resource
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
        'student_id' => $this->student_id,
        'student_name' => $this->student_name,
        'student_sex'  => $this->student_sex,
        'student_phone' => $this->student_phone,
        'student_status' => $this->student_status,
        'student_remark' =>$this->student_remark,
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
