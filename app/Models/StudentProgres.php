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
    public function lessonPartScore()
    {
        return $this->belongsTo(LessonPartScore::class, 'score_id', 'score_id');
    }

    // Truy cập student thông qua lesson_part_score
    public function student()
    {
        return $this->hasOneThrough(
            Student::class,
            LessonPartScore::class,
            'score_id',      // Foreign key on lesson_part_scores table
            'student_id',    // Foreign key on students table
            'score_id',      // Local key on student_progress table
            'student_id'     // Local key on lesson_part_scores table
        );
    }

    public function evaluation()
    {
        return $this->hasOne(StudentEvaluation::class, 'progress_id');
    }
}
