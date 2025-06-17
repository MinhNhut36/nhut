<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassPostComment extends Model
{
    protected $fillable = [
        'comment_id',
        'post_id',
        'author_id',
        'author_type',
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
        $author = $this->author();
        if ($author) {
            return $author->first()->fullname ?? $author->first()->name ?? 'Unknown';
        }
        return 'Unknown';
    }
}
