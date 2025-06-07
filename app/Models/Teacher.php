<?php

namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
class Teacher extends Authenticatable
{
    use Notifiable;
    protected $fillable = [
        'teacher_id',
        'avatar',
        'username',
        'name',
        'email',
        'password',
        'is_status',
    ];
   public function Courses()
   {
            return $this->belongsToMany(Course::class, 'teacher_course_assignments');

   }
}
