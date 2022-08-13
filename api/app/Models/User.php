<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;


/**
 * App\Models\User
 *
 * @property int $id
 * @property string|null $nickname 昵称
 * @property string|null $email 登陆名
 * @property string|null $password 密码
 * @property string|null $phone 手机号码
 * @property string|null $open_id 微信端用户ID
 * @property string|null $union_id 微信端用户联合ID
 * @property string|null $avatar 用户头像
 * @property bool $status 用户状态
 * @property int $gender 用户
 * @property string|null $country 用户国家
 * @property string|null $province 用户所在省
 * @property string|null $city 用户所在市
 * @property string|null $remember_token
 * @property string|null $deleted_at
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereNickname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereOpenId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereProvince($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUnionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class User extends Authenticatable
{
   // 该模型主要用于小程序用户和微信用户登陆

   use Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $guard = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
      'created_at' => 'timestamp',
      'updated_at' => 'timestamp',
      'status' => 'boolean'
    ];

}
