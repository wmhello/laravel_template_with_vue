<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    //
    protected $fillable = ['admin_id', 'name', 'address', 'event', 'desc', 'result', 'content'];
}
