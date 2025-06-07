<?php

namespace App\Models;

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
    public function Student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
    public function Course()
    {
        return $this->belongsTo(Course::class, 'assigned_course_id');
    }
}
