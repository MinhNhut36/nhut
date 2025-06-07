<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentAnswer extends Model
{
    protected $fillable = [
        'student_answer_id',
        'student_id',
        'question_id',
        'answer_text',
        'course_id',
        'answered_at',
    ];
    public function Question()
    {
        return $this->belongsTo(Question::class, 'question_id');
    }
    public function Course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
    public function Student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
}
