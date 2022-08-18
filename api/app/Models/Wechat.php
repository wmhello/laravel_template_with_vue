<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wechat extends Model
{
    //
    protected $dateFormat = "Y-m-d H:i:s";

    protected $guarded = [];
    
        public function scopeApp_id($query)
    {
        $params = request()->input('app_id');
        if ($params) {
            return $query = $query->where('app_id', 'like', "%".$params."%");
        } else {
            return $query;
        }
    }
        public function scopeApp_secret($query)
    {
        $params = request()->input('app_secret');
        if ($params) {
            return $query = $query->where('app_secret', 'like', "%".$params."%");
        } else {
            return $query;
        }
    }
    


}