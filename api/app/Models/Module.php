<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Module
 *
 * @property int $id
 * @property string $name 模块名称
 * @property string $desc 中文说明
 * @property int|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Permission[] $permissions
 * @property-read int|null $permissions_count
 * @method static \Illuminate\Database\Eloquent\Builder|Module newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Module newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Module query()
 * @method static \Illuminate\Database\Eloquent\Builder|Module whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Module whereDesc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Module whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Module whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Module whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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
