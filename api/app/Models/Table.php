<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Table
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Table newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Table newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Table query()
 * @mixin \Eloquent
 */
class Table extends Model
{
    //
    protected $dateFormat = "Y-m-d H:i:s";
    protected $casts = [
        'table_config' => 'array'
    ];
    protected $guarded = [];


}
