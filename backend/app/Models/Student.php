<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    //
    protected $primaryKey = 'student_id';
    protected $guarded = [];
    protected $table = 'students';
}
