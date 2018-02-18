<?php

namespace App\Models;

class Permission extends Model
{
    //
  protected  $fillable = ['name', 'pid', 'type', 'method', 'route_name', 'route_match', 'remark'];

    public function scopeName($query)
    {
        $name = request()->input('name');
        if (isset($name)) {
            return $query->where('name', 'like', '%'.$name.'%');
        } else {
            return $query;
        }
    }

    public function scopePid($query)
    {
        $val = request()->input('pid');
        if (isset($val)) {
            return $query->where('id', (int)$val)->orWhere('pid', (int)$val);
        } else {
            return $query;
        }
    }

    public function scopeType($query)
    {
        $val = request()->input('type');
        if (isset($val)) {
            return $query->where('type', (int)$val);
        } else {
            return $query;
        }
    }
}
