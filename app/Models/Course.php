<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
        'course_id',
        'level',
        'course_name',
        'year',
        'description',
        'status',
    ];
    public function Teachers()
    {
        return $this->belongsToMany(Teacher::class, 'teacher_course_assignments');
    }
    public function Students()
    {
        return $this->belongsToMany(Student::class, 'Course_Enrollments');
    }
    public function Lesson()
    {
        return $this->belongsTo(Lesson::class, 'level');
    }
    public function StudentAnwers()
    {
        return $this->hasMany(StudentAnswer::class, 'course_id');
    }
    public function LessonPartScores()
    {
        return $this->hasMany(LessonPartScore::class, 'course_id');
    }
    public function ExamResult()
    {
        return $this->hasOne(ExamResult::class, 'course_id');
    }
}
