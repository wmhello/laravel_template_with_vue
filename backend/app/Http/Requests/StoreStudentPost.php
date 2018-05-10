<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentPost extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
            'student_name' => 'required',
            'student_sex'  => 'required|in:男,女',
            'student_phone' => 'required|unique:students',
            'student_status' => 'required|in:0,1'
        ];
    }

    public function message()
    {
        return [
            'student_name.required' => '学生信息必须填写',
            'student_sex.required' => '学生性别必须填写',
            'student_phone.required' => '电话号码必须填写',
            'student_status.required' => '学生状态必须填写',
            'student_sex.in' => '学生性别填写有误',
            'student_status.in' => '学生状态填写有误',
            'student_phone.unique' => '学生电话号码不能重复'
        ];
    }
}
