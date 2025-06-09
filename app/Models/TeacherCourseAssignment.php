<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherCourseAssignment extends Model
{
    protected $fillable = [
        'assignment_id',
        'teacher_id',
        'course_id',
        'role',
        'assigned_at',
    ];
    protected $primaryKey = 'assignment_id';
    //định nghĩa các quan hệ với các model khác
        public function Teacher()
    {
        return $this->belongsTo(Teacher::class,'teacher_id');
    }
        public function Course()
    {
        return $this->belongsTo(Course::class,'course_id');
    }
}
