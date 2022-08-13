<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Code extends Model
{
    //
    protected $casts =[
      'created_at' => 'timestamp',
      'updated_at' => 'timestamp'
    ];

    protected $guarded = [];


}
