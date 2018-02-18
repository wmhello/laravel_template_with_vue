<?php

namespace App\Http\Requests;


class PermissionRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string',
            'pid' => 'required|integer',
            'type' => 'required|in:1,2',
            'method' => 'required_unless:type,1',
            'route_name' => 'required_unless:type,1',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '功能名称必须填写',
            'pid.required' =>  '所属组称必须填写',
            'type.required' => '功能类型必须填写',
            'route_name.required_unless' => '路由名称必须填写',
            'route_name.unique' => '路由名称已经存在，不能重复出现',
            'method.required_unless' => '访问方法必须填写',
        ];
    }
}
