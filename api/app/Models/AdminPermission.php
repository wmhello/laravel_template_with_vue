<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\AdminPermission
 *
 * @property int $id
 * @property int $admin_id 用户标识
 * @property int $permission_id 权限标识
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|AdminPermission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AdminPermission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AdminPermission query()
 * @method static \Illuminate\Database\Eloquent\Builder|AdminPermission whereAdminId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminPermission whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminPermission whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminPermission wherePermissionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminPermission whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class AdminPermission extends Model
{
    //
    protected $fillable = ['admin_id', 'permission_id'];
}
