<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ClassPost;
use App\Models\ClassPostComment;
use App\Models\Course;
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
                             ->with(['course', 'teacher', 'comments.student', 'comments.teacher'])
                             ->orderBy('created_at', 'desc')
                             ->get();

            // Thêm thông tin tác giả cho posts và comments
            foreach ($posts as $post) {
                $post->author = $post->teacher;
                $post->author_type = 'teacher';
                $post->author_name = $post->teacher ? $post->teacher->fullname : 'Unknown';

                // Thêm thông tin tác giả cho comments
                foreach ($post->comments as $comment) {
                    if ($comment->student_id) {
                        $comment->author = $comment->student;
                        $comment->author_type = 'student';
                        $comment->author_name = $comment->student ? $comment->student->fullname : 'Unknown';
                    } else {
                        $comment->author = $comment->teacher;
                        $comment->author_type = 'teacher';
                        $comment->author_name = $comment->teacher ? $comment->teacher->fullname : 'Unknown';
                    }
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
            $post = ClassPost::with(['course', 'teacher', 'comments.student', 'comments.teacher'])->find($postId);

            if (!$post) {
                return response()->json([
                    'error' => 'Không tìm thấy bài viết'
                ], 404);
            }

            // Thêm thông tin tác giả
            $post->author = $post->teacher;
            $post->author_type = 'teacher';
            $post->author_name = $post->teacher ? $post->teacher->fullname : 'Unknown';

            // Thêm thông tin tác giả cho comments
            foreach ($post->comments as $comment) {
                if ($comment->student_id) {
                    $comment->author = $comment->student;
                    $comment->author_type = 'student';
                    $comment->author_name = $comment->student ? $comment->student->fullname : 'Unknown';
                } else {
                    $comment->author = $comment->teacher;
                    $comment->author_type = 'teacher';
                    $comment->author_name = $comment->teacher ? $comment->teacher->fullname : 'Unknown';
                }
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
     * Tạo bài viết mới (chỉ teacher)
     * POST /api/class-posts
     */
    public function createClassPost(Request $request)
    {
        try {
            // Validate input
            $request->validate([
                'course_id' => 'required|exists:courses,course_id',
                'teacher_id' => 'required|exists:teachers,teacher_id',
                'title' => 'required|string|max:255',
                'content' => 'required|string',
            ]);

            $post = ClassPost::create([
                'course_id' => $request->course_id,
                'teacher_id' => $request->teacher_id,
                'title' => $request->title,
                'content' => $request->content,
                'status' => 1,
            ]);

            // Load relationships
            $post->load(['course', 'teacher']);
            $post->author = $post->teacher;
            $post->author_type = 'teacher';
            $post->author_name = $post->teacher ? $post->teacher->fullname : 'Unknown';

            return response()->json([
                'success' => true,
                'message' => 'Tạo bài viết thành công',
                'data' => $post
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
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
                                      ->with(['student', 'teacher', 'post'])
                                      ->orderBy('created_at', 'asc')
                                      ->get();

            // Thêm thông tin tác giả cho mỗi comment
            foreach ($comments as $comment) {
                if ($comment->student_id) {
                    $comment->author = $comment->student;
                    $comment->author_type = 'student';
                    $comment->author_name = $comment->student ? $comment->student->fullname : 'Unknown';
                } else {
                    $comment->author = $comment->teacher;
                    $comment->author_type = 'teacher';
                    $comment->author_name = $comment->teacher ? $comment->teacher->fullname : 'Unknown';
                }
            }

            return response()->json([
                'success' => true,
                'data' => $comments
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
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
            // Validate input
            $request->validate([
                'post_id' => 'required|exists:class_posts,post_id',
                'content' => 'required|string',
            ]);

            // Validate that either student_id or teacher_id is provided (but not both)
            if ((!$request->student_id && !$request->teacher_id) ||
                ($request->student_id && $request->teacher_id)) {
                return response()->json([
                    'success' => false,
                    'error' => 'Phải cung cấp student_id hoặc teacher_id (không được cả hai)'
                ], 400);
            }

            // Validate student_id or teacher_id exists
            if ($request->student_id) {
                $request->validate(['student_id' => 'exists:students,student_id']);
            }
            if ($request->teacher_id) {
                $request->validate(['teacher_id' => 'exists:teachers,teacher_id']);
            }

            $comment = ClassPostComment::create([
                'post_id' => $request->post_id,
                'student_id' => $request->student_id,
                'teacher_id' => $request->teacher_id,
                'content' => $request->content,
                'status' => 1,
            ]);

            // Load relationships
            $comment->load(['student', 'teacher', 'post']);
            if ($comment->student_id) {
                $comment->author = $comment->student;
                $comment->author_type = 'student';
                $comment->author_name = $comment->student ? $comment->student->fullname : 'Unknown';
            } else {
                $comment->author = $comment->teacher;
                $comment->author_type = 'teacher';
                $comment->author_name = $comment->teacher ? $comment->teacher->fullname : 'Unknown';
            }

            return response()->json([
                'success' => true,
                'message' => 'Tạo bình luận thành công',
                'data' => $comment
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Lỗi server',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lấy tất cả comments trong một khóa học
     * GET /api/class-posts/course/{courseId}/comments
     */
    public function getClassPostCommentsByCourse($courseId)
    {
        try {
            // Validate course exists
            $course = Course::findOrFail($courseId);

            $comments = ClassPostComment::whereHas('post', function($query) use ($courseId) {
                                $query->where('course_id', $courseId);
                            })
                            ->where('status', 1)
                            ->with(['student', 'teacher', 'post.course'])
                            ->orderBy('created_at', 'desc')
                            ->get();

            // Transform data for better structure
            $transformedComments = $comments->map(function($comment) {
                return [
                    'comment_id' => $comment->comment_id,
                    'content' => $comment->content,
                    'created_at' => $comment->created_at,
                    'updated_at' => $comment->updated_at,
                    'status' => $comment->status,

                    // Author information
                    'author' => [
                        'id' => $comment->student_id ?: $comment->teacher_id,
                        'type' => $comment->student_id ? 'student' : 'teacher',
                        'name' => $comment->student_id
                            ? ($comment->student?->fullname ?? 'Unknown Student')
                            : ($comment->teacher?->fullname ?? 'Unknown Teacher'),
                        'email' => $comment->student_id
                            ? ($comment->student?->email ?? null)
                            : ($comment->teacher?->email ?? null)
                    ],

                    // Post information
                    'post' => [
                        'post_id' => $comment->post->post_id,
                        'title' => $comment->post->title,
                        'course_id' => $comment->post->course_id,
                        'course_name' => $comment->post->course->course_name ?? 'Unknown Course'
                    ]
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $transformedComments,
                'meta' => [
                    'course_id' => $courseId,
                    'course_name' => $course->course_name,
                    'total_comments' => $transformedComments->count(),
                    'student_comments' => $transformedComments->where('author.type', 'student')->count(),
                    'teacher_comments' => $transformedComments->where('author.type', 'teacher')->count()
                ]
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'error' => 'Course not found',
                'message' => 'The specified course does not exist'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Server error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cập nhật bài viết (chỉ teacher)
     * PUT /api/class-posts/{postId}
     */
    public function updateClassPost(Request $request, $postId)
    {
        try {
            $post = ClassPost::find($postId);

            if (!$post) {
                return response()->json([
                    'success' => false,
                    'error' => 'Không tìm thấy bài viết'
                ], 404);
            }

            // Validate input
            $request->validate([
                'title' => 'sometimes|string|max:255',
                'content' => 'sometimes|string',
                'status' => 'sometimes|integer|in:0,1',
            ]);

            $post->update($request->only(['title', 'content', 'status']));

            // Load relationships
            $post->load(['course', 'teacher']);
            $post->author = $post->teacher;
            $post->author_type = 'teacher';
            $post->author_name = $post->teacher ? $post->teacher->fullname : 'Unknown';

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật bài viết thành công',
                'data' => $post
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Lỗi server',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Xóa bài viết (chỉ teacher)
     * DELETE /api/class-posts/{postId}
     */
    public function deleteClassPost($postId)
    {
        try {
            $post = ClassPost::find($postId);

            if (!$post) {
                return response()->json([
                    'success' => false,
                    'error' => 'Không tìm thấy bài viết'
                ], 404);
            }

            $post->delete();

            return response()->json([
                'success' => true,
                'message' => 'Xóa bài viết thành công'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Lỗi server',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cập nhật comment
     * PUT /api/class-post-replies/{commentId}
     */
    public function updateClassPostReply(Request $request, $commentId)
    {
        try {
            $comment = ClassPostComment::find($commentId);

            if (!$comment) {
                return response()->json([
                    'success' => false,
                    'error' => 'Không tìm thấy bình luận'
                ], 404);
            }

            // Validate input
            $request->validate([
                'content' => 'sometimes|string',
                'status' => 'sometimes|integer|in:0,1',
            ]);

            $comment->update($request->only(['content', 'status']));

            // Load relationships
            $comment->load(['student', 'teacher', 'post']);
            if ($comment->student_id) {
                $comment->author = $comment->student;
                $comment->author_type = 'student';
                $comment->author_name = $comment->student ? $comment->student->fullname : 'Unknown';
            } else {
                $comment->author = $comment->teacher;
                $comment->author_type = 'teacher';
                $comment->author_name = $comment->teacher ? $comment->teacher->fullname : 'Unknown';
            }

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật bình luận thành công',
                'data' => $comment
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Lỗi server',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Xóa comment
     * DELETE /api/class-post-replies/{commentId}
     */
    public function deleteClassPostReply($commentId)
    {
        try {
            $comment = ClassPostComment::find($commentId);

            if (!$comment) {
                return response()->json([
                    'success' => false,
                    'error' => 'Không tìm thấy bình luận'
                ], 404);
            }

            $comment->delete();

            return response()->json([
                'success' => true,
                'message' => 'Xóa bình luận thành công'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Lỗi server',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
