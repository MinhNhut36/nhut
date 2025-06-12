<?php

namespace App\Models;
use App\Enum\enrollment;
use Illuminate\Database\Eloquent\Model;

class CourseEnrollment extends Model
{
    protected $fillable = [
        'enrollment_id',
        'student_id',
        'assigned_course_id',
        'level',
        'registration_date',
        'status',
    ];
    protected $primaryKey = 'enrollment_id';
    protected $casts = [
        'status' => enrollment::class,
    ];
    //định nghĩa các quan hệ với các model khác
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
    public function course()
    {
        return $this->belongsTo(Course::class, 'assigned_course_id','course_id');
    }
}
