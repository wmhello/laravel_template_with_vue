<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    //
    protected $fillable=['name', 'desc', 'module_id'];
    protected $casts = [
        'created_at' => 'timestamp'
    ];

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function role_permissions()
    {
        return $this->hasMany(RolePermission::class);
    }
}
