<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\RolePermission
 *
 * @property int $id
 * @property int $role_id 角色标识
 * @property int $permission_id 权限标识
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Module[] $module
 * @property-read int|null $module_count
 * @property-read \App\Models\Permission $permission
 * @property-read \App\Models\Role $role
 * @method static \Illuminate\Database\Eloquent\Builder|RolePermission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RolePermission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RolePermission query()
 * @method static \Illuminate\Database\Eloquent\Builder|RolePermission whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RolePermission whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RolePermission wherePermissionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RolePermission whereRoleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RolePermission whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class RolePermission extends Model
{
    //
    protected $fillable = ['role_id', 'permission_id'];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function permission()
    {
        return $this->belongsTo(Permission::class);
    }

    public function module(){
        return $this->hasManyThrough(Module::class,Permission::class, 'id', 'id',
            'permission_id', 'module_id');
    }
}
