<?php

namespace App;

use App\Http\Controllers\Tools;
use Illuminate\Database\Eloquent\Model;

class Leader extends Model
{
    //
    use Tools;
    protected $fillable = ['session_id', 'teacher_id', 'leader_type', 'job', 'remark'];

    public function scopeLeaderType($query)
    {
        $val= request()->input('leader_type');
        if (isset($val)) {
          return $query = $query->where('leader_type', $val);
        }else {
            return $query;
        }
    }

    public function scopeTeacherId($query)
    {
        $val= request()->input('teacher_id');
        if (isset($val)) {
            return $query = $query->where('teacher_id', $val);
        }else {
            return $query;
        }
    }

    public function scopeSessionId($query)
    {
        $val= request()->input('session_id');
        if (isset($val)) {
            return $query = $query->where('session_id', $val);
        }else {
            $sessionId = $this->getCurrentSessionId();
            return $query = $query->where('session_id', $sessionId);
        }
    }
}
