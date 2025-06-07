<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentEvaluation extends Model
{
    protected $fillable = [
        'aveluation_id',
        'student_id',
        'progress_id',
        'exam_result_id',
        'evaluation_date',
        'final_status',
        'remarks',
    ];
    public function StudentProgres()
    {
        return $this->belongsTo(StudentProgres::class, 'progress_id');
    }
    public function Student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
    public function ExamResult()
    {
        return $this->belongsTo(ExamResult::class, 'exam_result_id');
    }
}
