<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserPost extends FormRequest
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
            'name' => 'required| unique:users',
            'email' => 'required| unique:users',
            'password' => 'required'
        ];
    }

    public function message()
    {
        return [
            'name.required' => '用户昵称必须填写',
            'name.unique' => '用户昵称不能重复',
            'email.required' => '登录名必须填写',
            'email.unique' => '登录名不能重复',
            'password.required' => '密码必填填写'
        ];
    }
}
