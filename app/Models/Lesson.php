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
    
    protected $primaryKey = 'level';
    protected $keyType = 'string';

    //định nghĩa các quan hệ với các model khác
    public function courses()
    {
        return $this->hasMany(Course::class, 'level','level');
    }
    public function lessonParts()
    {
        return $this->hasMany(LessonPart::class, 'level');
    }
}
