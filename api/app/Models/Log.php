<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Log
 *
 * @property int $id
 * @property int $admin_id 管理员标识
 * @property string $name 登陆者--使用者
 * @property string $address 地址--IP
 * @property string $event 操作类型--登陆操作、系统操作
 * @property string $desc 操作描述
 * @property string $result 操作结果
 * @property string|null $content 操作内容
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Log newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Log newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Log query()
 * @method static \Illuminate\Database\Eloquent\Builder|Log whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Log whereAdminId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Log whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Log whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Log whereDesc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Log whereEvent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Log whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Log whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Log whereResult($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Log whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Log extends Model
{
    //
    protected $fillable = ['admin_id', 'name', 'address', 'event', 'desc', 'result', 'content'];
}
