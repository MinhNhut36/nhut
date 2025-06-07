<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = [
        'question_id',
        'contents_id',
        'question_text',
        'question_type',
        'media_url',
        'order_index',
    ];
    public function LessonPartContents()
    {
        return $this->belongsToMany(LessonPartContent::class, 'contents_id');
    }
    public function Answers()
    {
        return $this->hasMany(Answer::class, 'question_id');
    }
    public function StudentAnswers()
    {
        return $this->hasMany(StudentAnswer::class, 'question_id');
    }
}
