<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Permission
 *
 * @property int $id
 * @property string $name 权限名称
 * @property string|null $desc 中文说明
 * @property int $module_id 模块标识
 * @property int|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Module $module
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\RolePermission[] $role_permissions
 * @property-read int|null $role_permissions_count
 * @method static \Illuminate\Database\Eloquent\Builder|Permission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Permission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Permission query()
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereDesc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereModuleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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
