<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LessonPartScore extends Model
{
    protected $fillable = [
        'score_id',
        'student_id',
        'lesson_part_id',
        'course_id',
        'attempt_no',
        'score',
        'total_questions',
        'correct_answers',
        'submit_time',
    ];
    public function LessonPart()
    {
        return $this->belongsTo(LessonPart::class, 'lesson_part_id');
    }
    public function Course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
    public function StudentProces()
    {
        return $this->belongsTo(StudentProgres::class, 'score_id');
    }

}
