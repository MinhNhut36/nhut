<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentProgres extends Model
{
    protected $fillable = [
        'progress_id',
        'student_id',
        'score_id',
        'completion_status',
        'last_updated',
    ];
    protected $primaryKey = 'progress_id';
    //định nghĩa các quan hệ với các model khác
    public function score()
    {
        return $this->hasMany(LessonPartScore::class, 'score_id');
    }
    public function Student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
    public function evaluation()
    {
        return $this->hasOne(StudentEvaluation::class, 'progress_id');
    }
}
