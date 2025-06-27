<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentAnswer extends Model
{
    protected $fillable = [
        'student_answer_id',
        'student_id',
        'questions_id',
        'answer_text',
        'course_id',
        'answered_at',
    ];
    protected $primaryKey = 'student_answer_id';
    //định nghĩa các quan hệ với các model khác
    public function Question()
    {
        return $this->belongsTo(Question::class, 'questions_id');
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
