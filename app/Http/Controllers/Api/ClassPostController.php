<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ClassPost;
use App\Models\ClassPostComment;
use Illuminate\Http\Request;

class ClassPostController extends Controller
{
    /**
     * Lấy bài viết theo khóa học
     * GET /api/class-posts/course/{courseId}
     */
    public function getClassPostsByCourseId($courseId)
    {
        try {
            $posts = ClassPost::where('course_id', $courseId)
                             ->where('status', 1)
                             ->with(['course', 'comments'])
                             ->orderBy('created_at', 'desc')
                             ->get();
            
            // Thêm thông tin tác giả
            foreach ($posts as $post) {
                if ($post->author_type === 'student') {
                    $post->author = $post->belongsTo(\App\Models\Student::class, 'author_id', 'student_id')->first();
                } else {
                    $post->author = $post->belongsTo(\App\Models\Teacher::class, 'author_id', 'teacher_id')->first();
                }
            }
            
            return response()->json($posts, 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Lỗi server',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Lấy bài viết theo ID
     * GET /api/class-posts/{postId}
     */
    public function getClassPostById($postId)
    {
        try {
            $post = ClassPost::with(['course', 'comments'])->find($postId);
            
            if (!$post) {
                return response()->json([
                    'error' => 'Không tìm thấy bài viết'
                ], 404);
            }
            
            // Thêm thông tin tác giả
            if ($post->author_type === 'student') {
                $post->author = $post->belongsTo(\App\Models\Student::class, 'author_id', 'student_id')->first();
            } else {
                $post->author = $post->belongsTo(\App\Models\Teacher::class, 'author_id', 'teacher_id')->first();
            }
            
            return response()->json($post, 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Lỗi server',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Tạo bài viết mới
     * POST /api/class-posts
     */
    public function createClassPost(Request $request)
    {
        try {
            $post = ClassPost::create($request->all());
            
            return response()->json($post, 201);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Lỗi server',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Lấy bình luận của bài viết
     * GET /api/class-post-replies/post/{postId}
     */
    public function getClassPostReplies($postId)
    {
        try {
            $comments = ClassPostComment::where('post_id', $postId)
                                      ->where('status', 1)
                                      ->orderBy('created_at', 'asc')
                                      ->get();
            
            // Thêm thông tin tác giả cho mỗi comment
            foreach ($comments as $comment) {
                if ($comment->author_type === 'student') {
                    $comment->author = $comment->belongsTo(\App\Models\Student::class, 'author_id', 'student_id')->first();
                } else {
                    $comment->author = $comment->belongsTo(\App\Models\Teacher::class, 'author_id', 'teacher_id')->first();
                }
            }
            
            return response()->json($comments, 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Lỗi server',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Tạo bình luận mới
     * POST /api/class-post-replies
     */
    public function createClassPostReply(Request $request)
    {
        try {
            $comment = ClassPostComment::create($request->all());
            
            return response()->json($comment, 201);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Lỗi server',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
