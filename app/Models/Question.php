<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enum\QuestionType;

class Question extends Model
{
    protected $fillable = [
        'questions_id',
        'contents_id',
        'question_text',
        'question_type',
        'media_url',
        'order_index',
    ];
    protected $primaryKey = 'questions_id';

    protected $casts = [
        'question_type' => QuestionType::class,
    ];
    //định nghĩa các quan hệ với các model khác


    public function lessonPart()
    {
        return $this->belongsTo(LessonPart::class, 'contents_id');
    }
    public function answers()
    {
        return $this->hasMany(Answer::class, 'questions_id','questions_id');
    }

    public function correctAnswer()
    {
        return $this->hasOne(Answer::class, 'questions_id', 'questions_id')->where('is_correct', true);
    }

    public function studentAnswers()
    {
        return $this->hasMany(StudentAnswer::class, 'questions_id');
    }
}
