<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wechat extends Model
{
    //
    protected $guarded = [];
    protected $table = 'wechats';
    protected $casts = [
        'status' => 'boolean'
    ];
}
