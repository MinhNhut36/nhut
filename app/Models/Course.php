<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
    protected $primaryKey = 'course_id';

    //định nghĩa các quan hệ với các model khác
    public function Teachers()
    {
        return $this->belongsToMany(Teacher::class, 'teacher_course_assignments');
    }
    public function Students()
    {
        return $this->belongsToMany(Student::class, 'Course_Enrollments');
    }
    public function lesson()
    {
        return $this->belongsTo(Lesson::class, 'level','level');
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
