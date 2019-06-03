<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    //
    protected $fillable = ['product_number', 'product_name', 'product_img', 'order_id', 'product_amount', 'remain_amount', 'product_remark'];
    protected $primaryKey = 'id';
    protected $table = 'products';

    public function order()
    {
      return $this->belongsTo('App\Models\Order');
    }
}
