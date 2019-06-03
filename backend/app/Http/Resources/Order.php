<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Route;

class Order extends Resource
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
            'order_number' => $this->order_number,
            'merchant_number' => $this->merchant_number,
            'merchant_name' => $this->merchant_name,
            'order_time' => $this->order_time,
            'products' => $this->when($this->isShow(),$this->products),
            'order_remark' => $this->order_remark,
            'order_status' => $this->getStatus($this->order_status)
        ];
    }

    public function with($request)
    {
        return [
          'status' => 'success',
          'status_code' => 200
        ];

    }
    

    public function getStatus($status_code)
    {
        $content = '';
        switch ($status_code) {
            case 1:
                $content =  '未发';
                break;
            case 2:
                $content = '已发未完';
                break;
            default:
                $content = '已发完';
        }
        return $content;
    }

    public function isShow()
    {
        return strpos(Route::currentRouteName(),'show') ? true : false;
    }
}
