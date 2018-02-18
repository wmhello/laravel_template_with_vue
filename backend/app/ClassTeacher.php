<?php

namespace App;

use App\Http\Controllers\Tools;
use Illuminate\Database\Eloquent\Model;

class ClassTeacher extends Model
{
    //
    use Tools;
    protected $fillable = ['session_id', 'teacher_id', 'grade', 'class_id', 'remark'];

    public function scopeGrade($query)
    {
        $val= request()->input('grade');
        if (isset($val)) {
            return $query = $query->where('grade', $val);
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
