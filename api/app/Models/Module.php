<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    //
    protected $fillable = ['name', 'desc'];

    protected $casts = [
        'created_at' => 'timestamp'
    ];

    public function permissions()
    {
        return $this->hasMany(Permission::class);
    }
    
}
