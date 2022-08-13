<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

/**
 * App\Models\Admin
 *
 * @property int $id
 * @property string|null $nickname 昵称
 * @property string $email 登陆名
 * @property string|null $phone 手机号码
 * @property string|null $avatar 头像
 * @property string|null $password 密码
 * @property bool $status 用户状态
 * @property string|null $remember_token
 * @property string|null $deleted_at
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\AdminRole[] $admin_roles
 * @property-read int|null $admin_roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\Client[] $clients
 * @property-read int|null $clients_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\Token[] $tokens
 * @property-read int|null $tokens_count
 * @method static \Illuminate\Database\Eloquent\Builder|Admin email()
 * @method static \Illuminate\Database\Eloquent\Builder|Admin newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Admin newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Admin phone()
 * @method static \Illuminate\Database\Eloquent\Builder|Admin query()
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereNickname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Admin extends Authenticatable
{
    //
    use Notifiable, HasApiTokens;

    protected  $fillable = ['nickname', 'email', 'password', 'phone', "avatar", "status"];
    protected  $hidden = ['password'];
    protected  $guarded_name = 'admin';

    protected $casts = [
      'created_at' => 'timestamp',
      'updated_at' => 'timestamp',
      'status' => 'boolean'
    ];
    // 多个字段来验证
     public function findForPassport($username)
     {
         return $this->orWhere('email', $username)->orWhere('phone', $username)->first();
     }

     public function scopeEmail($query)
    {
        $params = request()->input('email');
        if ($params) {
            return $query = $query->where('email', like, "%".$params."%");
        } else {
            return $query;
        }
    }

        public function scopePhone($query)
    {
        $params = request()->input('phone');
        if ($params) {
            return $query = $query->where('phone', like, "%".$params."%");
        } else {
            return $query;
        }
    }

    public function admin_roles()
    {
        return $this->hasMany(AdminRole::class);
    }

}
