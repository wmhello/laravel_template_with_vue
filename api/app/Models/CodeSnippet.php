<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CodeSnippet extends Model
{
    //
    protected $casts =[
      'created_at' => 'timestamp',
      'updated_at' => 'timestamp'
    ];

    protected $guarded = [];


}
