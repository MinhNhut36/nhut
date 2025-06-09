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
    protected $hidden = ['password'];
   public function Courses()
   {
            return $this->belongsToMany(Course::class, 'teacher_course_assignments');

   }
}
