<?php

namespace App;

use App\Http\Controllers\Tools;
use Illuminate\Database\Eloquent\Model;

class Teaching extends Model
{
    //
    use Tools;
    protected $fillable = ['session_id', 'teacher_id', 'grade', 'class_id', 'teach_id', 'hour', 'remark'];

    public function scopeGrade($query)
    {
        $val= request()->input('grade');
        if (isset($val)) {
            return $query = $query->where('grade', $val);
        }else {
            return $query;
        }
    }

    public function scopeTeachId($query)
    {
        $val= request()->input('teach_id');
        if (isset($val)) {
            return $query = $query->where('teach_id', $val);
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

    public function scopeClassId($query)
    {
        $val= request()->input('class_id');
        if (isset($val)) {
            return $query = $query->where('class_id', $val);
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

    public function getAllClass($id)
    {

        $item = Teaching::find($id);
        $arr = Teaching::where('teacher_id', $item->teacher_id)
                       ->where('session_id', $item->session_id)
                       ->where('grade', $item->grade)
                       ->pluck('class_id');
        return $arr;
    }

}
