<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Enum\courseStatus;
class Course extends Model
{
    protected $fillable = [
        'course_id',
        'level',
        'course_name',
        'year',
        'description',
        'status',
        'starts_date',
    ];
    protected $primaryKey = 'course_id';
     protected $casts = [
        'status' => courseStatus::class,
    ];
    //định nghĩa các quan hệ với các model khác
    public function students()
    {
        return $this->belongsToMany(Student::class, 'course_enrollments', 'assigned_course_id', 'student_id');
    }
    public function enrollments()
    {
        return $this->hasMany(CourseEnrollment::class, 'assigned_course_id');
    }
    public function teacherAssignments()
    {
        return $this->hasMany(TeacherCourseAssignment::class, 'course_id');
    }
    public function teachers()
    {
        return $this->belongsToMany(Teacher::class, 'teacher_course_assignments', 'course_id', 'teacher_id')
            ->withPivot('role', 'assigned_at');
    }
    public function lesson()
    {
        return $this->belongsTo(Lesson::class, 'level', 'level');
    }
    public function studentAnwers()
    {
        return $this->hasMany(StudentAnswer::class, 'course_id');
    }
    public function lessonPartScores()
    {
        return $this->hasMany(LessonPartScore::class, 'course_id');
    }
    public function examResult()
    {
        return $this->hasOne(ExamResult::class, 'course_id');
    }

    public function classPosts()
    {
        return $this->hasMany(ClassPost::class, 'course_id', 'course_id')
                    ->where('status', 1)
                    ->orderBy('created_at', 'desc');
    }
}
