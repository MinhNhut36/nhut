<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentEvaluation extends Model
{
    protected $fillable = [
        'evaluation_id',
        'student_id',
        'progress_id',
        'exam_result_id',
        'evaluation_date',
        'final_status',
        'remarks',
    ];
    protected $primaryKey = 'evaluation_id';
    //định nghĩa các quan hệ với các model khác
    public function progress()
    {
        return $this->belongsTo(StudentProgress::class, 'progress_id');
    }
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
    public function examResult()
    {
        return $this->belongsTo(ExamResult::class, 'exam_result_id');
    }
}
