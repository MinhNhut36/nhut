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
    protected $primaryKey = 'exam_result_id';
    //định nghĩa các quan hệ với các model khác
    public function studentEvaluation()
    {
        return $this->hasOne(StudentEvaluation::class, 'exam_result_id');
    }
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
}
