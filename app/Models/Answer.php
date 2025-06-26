<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $fillable = [
        'answers_id',
        'questions_id',
        'match_key',
        'answer_text',
        'is_correct',
        'feedback',
        'media_url',
        'order_index',
    ];
    protected $primaryKey = 'answers_id';
    //định nghĩa các quan hệ với các model khác
    public function question()
    {
        return $this->belongsTo(Question::class, 'questions_id');
    }
}
