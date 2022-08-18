<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TableConfig extends Model
{
    //
    protected $dateFormat = "Y-m-d H:i:s";
    protected $casts = [
      'is_required' => 'boolean',
      'is_list' => 'boolean',
      'is_form' => 'boolean',
    ];

    protected $guarded = [];


}
