<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    //  Order  orders
    protected $fillable = ['order_number', 'merchant_number', 'merchant_name', 'order_status', 'order_time', 'order_remark'];
    protected $table = 'orders';
    protected $primaryKey = 'id';

    public function scopeOrderNumber($query)
    {
        $params = request()->input('order_number');
        if ($params) {
            $query = $query->where('order_number', $params);
        } else {
            return $query;
        }
    }

    public function products()
    {
        return $this->hasMany('App\Models\Product');
    }

}
