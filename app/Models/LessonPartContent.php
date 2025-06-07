<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LessonPartContent extends Model
{
    protected $fillable = [
        'contents_id',
        'lesson_part_id',
        'content_type',
        'content_data',
        'mini_game_type',
    ];
    public function lessonPart()
    {
        return $this->belongsTo(LessonPart::class, 'lesson_part_id');
    }
    public function questions()
    {
        return $this->belongsToMany(Question::class, 'contents_id');
    }
}
