<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClassTeacherRequest extends FormRequest
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
            'teacher_id' => 'required|exists:yz_teacher,id',
            'class_id' => 'required|numeric',
            'grade' => 'required|in:1,2,3',
            'remark' => 'nullable|string|max:50',
        ];
    }
}
