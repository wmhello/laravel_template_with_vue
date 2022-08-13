<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Wechat
 *
 * @property int $id
 * @property string|null $app_id 微信应用APP_ID
 * @property string|null $app_secret 微信应用APP_Secret
 * @property string $type 微信应用类型
 * @property bool $status 微信应用是否启用
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Wechat newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Wechat newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Wechat query()
 * @method static \Illuminate\Database\Eloquent\Builder|Wechat whereAppId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wechat whereAppSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wechat whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wechat whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wechat whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wechat whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wechat whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Wechat extends Model
{
    //
    protected $guarded = [];
    protected $table = 'wechats';
    protected $casts = [
        'status' => 'boolean'
    ];
}
