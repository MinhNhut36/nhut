<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentProgres extends Model
{
    protected $table = 'student_progress';

    protected $fillable = [
        'progress_id',
        'score_id',
        'completion_status',
        'last_updated',
    ];
    protected $primaryKey = 'progress_id';
    //định nghĩa các quan hệ với các model khác
    public function score()
    {
        return $this->belongsTo(LessonPartScore::class, 'score_id');
    }

    // Truy cập student thông qua score
    public function student()
    {
        return $this->hasOneThrough(Student::class, LessonPartScore::class, 'score_id', 'student_id', 'score_id', 'student_id');
    }

    public function evaluation()
    {
        return $this->hasOne(StudentEvaluation::class, 'progress_id');
    }
}
