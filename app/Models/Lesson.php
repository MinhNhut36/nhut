<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    protected $fillable = [
        'level',
        'title',
        'description',
        'status',
        'order_index',
    ];
    protected $primaryKey = 'level';
    //định nghĩa các quan hệ với các model khác
    public function Courses()
    {
        return $this->hasMany(Course::class, 'level');
    }
    public function LessonParts()
    {
        return $this->hasMany(LessonPart::class, 'level');
    }
}
