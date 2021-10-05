<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

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
