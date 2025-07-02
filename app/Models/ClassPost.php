<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
     * Quan hệ polymorphic với tác giả (Student hoặc Teacher)
     */
    public function author()
    {
        if ($this->author_type === 'student') {
            return $this->belongsTo(Student::class, 'author_id', 'student_id');
        } elseif ($this->author_type === 'teacher') {
            return $this->belongsTo(Teacher::class, 'author_id', 'teacher_id');
        }
        return null;
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
        $author = $this->author();
        if ($author) {
            return $author->first()->fullname ?? $author->first()->name ?? 'Unknown';
        }
        return 'Unknown';
    }
}
