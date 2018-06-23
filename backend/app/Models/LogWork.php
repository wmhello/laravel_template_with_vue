<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogWork extends Model
{
    //
    protected $fillable = ['user_id', 'user_name', 'ip', 'type', 'route_name', 'desc'];
}
