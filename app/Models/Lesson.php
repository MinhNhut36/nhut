<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    protected $fillable = [
        'level',
        'title',
        'description',
        'order_index',
    ];
    public function Courses()
    {
        return $this->hasMany(Course::class, 'level');
    }
    public function LessonParts()
    {
        return $this->hasMany(LessonPart::class, 'level');
    }
}
