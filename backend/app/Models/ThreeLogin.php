<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ThreeLogin extends Model
{
    //
    protected $fillable = ['user_id', 'platform_id', 'remark', 'provider'];
}
