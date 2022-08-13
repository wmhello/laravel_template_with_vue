<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\AdminRole
 *
 * @property int $id
 * @property int $admin_id 用户标识
 * @property int $role_id 角色标识
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Admin $admin
 * @property-read \App\Models\Role $role
 * @method static \Illuminate\Database\Eloquent\Builder|AdminRole newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AdminRole newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AdminRole query()
 * @method static \Illuminate\Database\Eloquent\Builder|AdminRole whereAdminId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminRole whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminRole whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminRole whereRoleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminRole whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class AdminRole extends Model
{
    //
    protected $fillable = ['admin_id', 'role_id'];

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
