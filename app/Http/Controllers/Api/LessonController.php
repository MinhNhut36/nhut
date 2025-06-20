<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\LessonPart;
use App\Models\LessonPartContent;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    /**
     * Lấy bài học theo khóa học
     * GET /api/lessons/course/{courseId}
     */
    public function getLessonsByCourseId($courseId)
    {
        try {
            // Lấy lessons thông qua course level
            $lessons = Lesson::whereHas('courses', function($query) use ($courseId) {
                $query->where('course_id', $courseId);
            })->with('lessonParts')->get();
            
            return response()->json($lessons, 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Lỗi server',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Lấy bài học theo ID
     * GET /api/lessons/{lessonId}
     */
    public function getLessonById($lessonId)
    {
        try {
            $lesson = Lesson::with(['lessonParts', 'courses'])->find($lessonId);
            
            if (!$lesson) {
                return response()->json([
                    'error' => 'Không tìm thấy bài học'
                ], 404);
            }
            
            return response()->json($lesson, 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Lỗi server',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Lấy bài học theo level
     * GET /api/lessons/level/{level}
     */
    public function getLessonsByLevel($level)
    {
        try {
            $lessons = Lesson::where('level', $level)
                           ->with('lessonParts')
                           ->orderBy('order_index')
                           ->get();
            
            return response()->json($lessons, 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Lỗi server',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Lấy lesson parts theo lesson ID
     * GET /api/lesson-parts/lesson/{lessonId}
     */
    public function getLessonPartsByLessonId($lessonId)
    {
        try {
            // Lấy lesson parts theo level của lesson
            $lesson = Lesson::find($lessonId);
            
            if (!$lesson) {
                return response()->json([
                    'error' => 'Không tìm thấy bài học'
                ], 404);
            }
            
            $lessonParts = LessonPart::where('level', $lesson->level)
                                   ->with('contents')
                                   ->orderBy('order_index')
                                   ->get();
            
            return response()->json($lessonParts, 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Lỗi server',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Lấy lesson part theo ID
     * GET /api/lesson-parts/{lessonPartId}
     */
    public function getLessonPartById($lessonPartId)
    {
        try {
            $lessonPart = LessonPart::with(['contents', 'lesson'])->find($lessonPartId);
            
            if (!$lessonPart) {
                return response()->json([
                    'error' => 'Không tìm thấy phần bài học'
                ], 404);
            }
            
            return response()->json($lessonPart, 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Lỗi server',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Lấy nội dung lesson part
     * GET /api/lesson-part-contents/{lessonPartId}
     */
    public function getLessonPartContents($lessonPartId)
    {
        try {
            $contents = LessonPartContent::where('lesson_part_id', $lessonPartId)->get();
            
            return response()->json($contents, 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Lỗi server',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
