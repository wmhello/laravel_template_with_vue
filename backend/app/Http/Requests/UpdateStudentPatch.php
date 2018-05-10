<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateStudentPatch extends FormRequest
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
        $id = $this->route('student')->student_id;
        return [
            'student_name' => 'required',
            'student_sex'  => 'required|in:男,女',
            'student_phone' => [
                'required',
                Rule::unique('students')->ignore($id, 'student_id'),
            ],
            'student_status' => 'required|in:0,1'
        ];
    }

}
