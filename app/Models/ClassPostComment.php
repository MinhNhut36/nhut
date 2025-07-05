<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassPostComment extends Model
{
    protected $fillable = [
        'comment_id',
        'post_id',
        'student_id',
        'teacher_id',
        'content',
        'status',
    ];

    protected $primaryKey = 'comment_id';

    // Định nghĩa các quan hệ với các model khác

    /**
     * Quan hệ với ClassPost
     */
    public function post()
    {
        return $this->belongsTo(ClassPost::class, 'post_id', 'post_id');
    }

    /**
     * Quan hệ với Student
     */
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'student_id');
    }

    /**
     * Quan hệ với Teacher
     */
    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id', 'teacher_id');
    }

    /**
     * Accessor để lấy tác giả (student hoặc teacher)
     */
    public function getAuthorAttribute()
    {
        if ($this->student_id) {
            return $this->student;
        } else {
            return $this->teacher;
        }
    }

    /**
     * Scope để lấy các comment đang hoạt động
     */
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    /**
     * Scope để lấy comment theo bài viết
     */
    public function scopeByPost($query, $postId)
    {
        return $query->where('post_id', $postId);
    }

    /**
     * Accessor để lấy tên tác giả
     */
    public function getAuthorNameAttribute()
    {
        if ($this->student_id && $this->student) {
            return $this->student->fullname ?? 'Unknown';
        } elseif ($this->teacher_id && $this->teacher) {
            return $this->teacher->fullname ?? 'Unknown';
        }
        return 'Unknown';
    }

    /**
     * Accessor để lấy loại tác giả
     */
    public function getAuthorTypeAttribute()
    {
        if ($this->student_id) {
            return 'student';
        } else {
            return 'teacher';
        }
    }
}
