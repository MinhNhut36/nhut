<?php

namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
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
    public function Courses()
    {
        return $this->belongsToMany(Course::class, 'Course_Enrollments');
    }
    public function StudentAnswers()
    {
        return $this->hasMany(StudentAnswer::class, 'student_id');
    }
    public function StudentProgress()
    {
        return $this->hasMany(StudentProgres::class, 'student_id');
    }
    public function StudentEvaluations()
    {
        return $this->hasMany(StudentEvaluation::class, 'student_id');
    }
    public function ExamResults()
    {
        return $this->hasMany(ExamResult::class, 'student_id');
    }
}
