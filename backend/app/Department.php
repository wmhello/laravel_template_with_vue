<?php

namespace App;

use App\Http\Controllers\Tools;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    //
    use Tools;
    protected $fillable = ['session_id', 'teacher_id', 'teach_id', 'leader', 'grade', 'remark'];

    public function scopeGrade($query)
    {
        $val= request()->input('grade');
        if (isset($val)) {
            return $query = $query->where('grade', $val);
        }else {
            return $query;
        }
    }

    public function scopeLeader($query)
    {
        $val= request()->input('leader');
        if (isset($val)) {
            return $query = $query->where('leader', $val);
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
