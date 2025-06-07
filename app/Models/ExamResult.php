<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamResult extends Model
{
    protected $fillable = [
        'exam_result_id',
        'student_id',
        'exam_date',
        'listening_score',
        'reading_score',
        'writing_score',
        'speaking_score',
        'overall_status',
    ];
    public function StudentEvaluation()
    {
        return $this->hasOne(StudentEvaluation::class, 'exam_result_id');
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
