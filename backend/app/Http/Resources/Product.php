<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class Product extends Resource
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
            'product_number' => $this->product_number,
            'product_name' => $this->product_name,
            'product_img' => $this->product_img,
            'order_id' => $this->order_id,
            'order_number' => $this->order->order_number,
            'merchant_number' => $this->order->merchant_number,
            'product_amount' => $this->product_amount,
            'remain_amount' => $this->remain_amount,
            'product_remark' => $this->product_remark,
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
