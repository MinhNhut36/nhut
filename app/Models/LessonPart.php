<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LessonPart extends Model
{
    protected $fillable = [
        'lesson_part_id',
        'level',
        'part_type',
        'content',
        'order_index',
    ];
    protected $primaryKey = 'lesson_part_id';
    //định nghĩa các quan hệ với các model khác
    public function lesson()
    {
        return $this->belongsTo(Lesson::class, 'level');
    }
    public function contents()
    {
        return $this->hasOne(LessonPartContent::class, 'lesson_part_id');
    }
    public function scores()
    {
        return $this->hasMany(LessonPartScore::class, 'lesson_part_id');
    }

}
