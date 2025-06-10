<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use App\Enum\gender;

class Teacher extends Authenticatable
{
    use Notifiable;
    protected $fillable = [
        'teacher_id',
        'avatar',
        'username',
        'name',
        'email',
        'gender',
        'password',
        'is_status',
    ];
    protected $casts = [
        'gender' => gender::class,
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
}
