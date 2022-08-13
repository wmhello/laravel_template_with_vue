<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Role
 *
 * @property int $id
 * @property string $name 英文名称
 * @property string $desc 中文说明
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\AdminRole[] $admin_roles
 * @property-read int|null $admin_roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Permission[] $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\RolePermission[] $role_permissions
 * @property-read int|null $role_permissions_count
 * @method static \Illuminate\Database\Eloquent\Builder|Role newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Role newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Role query()
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereDesc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Role extends Model
{
    //
    protected $fillable = ['name', 'desc'];
    protected $casts = [
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
    ];

    public function role_permissions()
    {
        return $this->hasMany(RolePermission::class);
    }

    public function permissions()
    {
        return $this->hasManyThrough(Permission::class, RolePermission::class);
    }

    public function admin_roles()
    {
        return $this->hasMany(AdminRole::class);
    }
}
