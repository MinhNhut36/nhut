<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Enum\gender;
use App\Enum\personStatus;

class Teacher extends Authenticatable
{
    use Notifiable;
    protected $fillable = [
        'teacher_id',
        'avatar',
        'fullname',
        'username',
        'password',
        'date_of_birth',
        'gender',
        'email',
        'is_status',
    ];
    protected $casts = [
        'gender' => gender::class,
        'is_status' => personStatus::class,
    ];
    protected $primaryKey = 'teacher_id';
    //định nghĩa các quan hệ với các model khác
    public function assignments()
    {
        return $this->hasMany(TeacherCourseAssignment::class, 'teacher_id');
    }
    public function courses()
    {
        return $this->belongsToMany(Course::class, 'teacher_course_assignments', 'teacher_id', 'course_id')
            ->withPivot('role', 'assigned_at');
    }

    public function classPosts()
    {
        return $this->hasMany(ClassPost::class, 'teacher_id', 'teacher_id')
            ->where('status', 1)
            ->orderBy('created_at', 'desc');
    }

    public function classPostComments()
    {
        return $this->hasMany(ClassPostComment::class, 'teacher_id', 'teacher_id')
            ->where('status', 1)
            ->orderBy('created_at', 'desc');
    }
}
