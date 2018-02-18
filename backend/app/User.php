<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens,Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'role', 'avatar'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function scopeName($query)
    {
        $name = request()->input('name');
        if (isset($name)) {
            return $query = $query->where('name', 'like', '%'.$name.'%');
        } else {
            return $query;
        }
    }

    public function scopeEmail($query)
    {
        $email = request()->input('email');
        if (isset($email)) {
            return $query = $query->where('email', 'like', '%'.$email.'%');
        } else {
            return $query;
        }
    }
}
