<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $fillable = [
        'answers_id',
        'question_id',
        'match_key',
        'answer_text',
        'is_correct',
        'feedback',
        'media_url',
        'order_index',
    ];
    public function Question()
    {
        return $this->belongsTo(Question::class, 'question_id');
    }
}
