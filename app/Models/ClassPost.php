<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Teacher;

class ClassPost extends Model
{
    protected $fillable = [
        'post_id',
        'course_id',
        'teacher_id',
        'title',
        'content',
        'status',
    ];

    protected $primaryKey = 'post_id';

    // Định nghĩa các quan hệ với các model khác

    /**
     * Quan hệ với Course
     */
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id', 'course_id');
    }

    /**
     * Quan hệ với Teacher (chỉ teacher mới tạo được post)
     */
    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id', 'teacher_id');
    }

    /**
     * Accessor để lấy tác giả (luôn là teacher)
     */
    public function getAuthorAttribute()
    {
        return $this->teacher;
    }

    /**
     * Quan hệ với ClassPostComment
     */
    public function comments()
    {
        return $this->hasMany(ClassPostComment::class, 'post_id', 'post_id')
            ->where('status', 1)
            ->orderBy('created_at', 'asc');
    }

    /**
     * Scope để lấy các bài viết đang hoạt động
     */
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    /**
     * Scope để lấy bài viết theo khóa học
     */
    public function scopeByCourse($query, $courseId)
    {
        return $query->where('course_id', $courseId);
    }

    /**
     * Accessor để lấy tên tác giả
     */
    public function getAuthorNameAttribute()
    {
        $teacher = $this->teacher;
        if ($teacher) {
            return $teacher->fullname ?? $teacher->name ?? 'Unknown';
        }
        return 'Unknown';
    }

    /**
     * Accessor để lấy loại tác giả (luôn là teacher)
     */
    public function getAuthorTypeAttribute()
    {
        return 'teacher';
    }
}
