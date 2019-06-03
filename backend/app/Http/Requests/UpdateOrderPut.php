<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateOrderPut extends FormRequest
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
        //url()->current();
        $id = $this->route('order')->id;
        return [
            'order_number' => ['required',
                Rule::unique('orders')->ignore($id)],
            // 订单号必填且必须唯一
            'merchant_number' => 'required',  // 商户编号必填
            'order_status' => 'required|in:1,2,3',  // 订单状态必填，并且只能是1,2,3
            'order_time' => 'required'
        ];
    }

    public function message()
    {
        return [
            'order_number.required' => '订单号必填',
            'order_number.unique' => '订单号必须唯一，不能重复',
        ];
    }

}
