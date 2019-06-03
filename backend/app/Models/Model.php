<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/13 0013
 * Time: 09:27
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model as EloquentModel;
class Model extends EloquentModel
{
    public function scopeRecent($query)
    {
        return $query->orderBy('create_at', 'desc');
    }


}