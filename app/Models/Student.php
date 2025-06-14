<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use App\Enum\gender;
use App\Enum\personStatus;
class Student extends Authenticatable
{
    use Notifiable;
    protected $fillable = [
        'student_id',
        'avatar',
        'username',
        'name',
        'email',
        'password',
        'brirthday',
        'gender',
        'is_status',
    ];
    protected $primaryKey = 'student_id';

    protected $casts = [
        'gender' => gender::class,
        'is_status' => personStatus::class,
    ];


    //định nghĩa các quan hệ với các model khác
    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_enrollment', 'student_id', 'assigned_course_id');
    }
    public function enrollments()
    {
        return $this->hasMany(CourseEnrollment::class, 'student_id');
    }
    
    public function studentAnswers()
    {
        return $this->hasMany(StudentAnswer::class, 'student_id');
    }
    public function studentProgress()
    {
        return $this->hasMany(StudentProgres::class, 'student_id');
    }
    public function studentEvaluations()
    {
        return $this->hasMany(StudentEvaluation::class, 'student_id');
    }
    public function examResults()
    {
        return $this->hasMany(ExamResult::class, 'student_id');
    }
}
