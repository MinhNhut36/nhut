<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\LessonPart;


class LessonController extends Controller
{
    /**
     * Lấy bài học theo khóa học
     * GET /api/lessons/course/{courseId}
     */
    public function getLessonsByCourseId($courseId)
    {
        try {
            // Lấy course để biết level
            $course = \App\Models\Course::find($courseId);
            if (!$course) {
                return response()->json([
                    'error' => 'Không tìm thấy khóa học'
                ], 404);
            }

            // Lấy lessons theo level của course
            $lessons = Lesson::whereHas('courses', function($query) use ($courseId) {
                $query->where('courses.course_id', $courseId);
            })->get();

            // Thêm lesson parts theo đúng level của course
            foreach ($lessons as $lesson) {
                $lesson->lessonParts = LessonPart::whereHas('lesson', function($query) use ($course) {
                    $query->where('level', $course->level);
                })->orderBy('order_index')->get();
            }

            return response()->json($lessons, 200);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Lỗi server',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Lấy bài học theo Level
     * GET /api/lessons/{level}
     */
    public function getLessonByLevel($level)
    {
        try {
            $lesson = Lesson::with('courses')->where('level', $level)->first();

            if ($lesson) {
                // Thêm lesson parts theo đúng level
                $lesson->lessonParts = LessonPart::whereHas('lesson', function($query) use ($level) {
                    $query->where('level', $level);
                })->orderBy('order_index')->get();
            }

            if (!$lesson) {
                return response()->json([
                    'error' => 'Không tìm thấy bài học với level này'
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
     * Lấy lesson parts theo level
     * GET /api/lesson-parts/lesson/{level}
     */
    public function getLessonPartsByLevel($level)
    {
        try {
            // Kiểm tra lesson có tồn tại với level này không
            $lesson = Lesson::where('level', $level)->first();

            if (!$lesson) {
                return response()->json([
                    'error' => 'Không tìm thấy bài học với level này'
                ], 404);
            }

            // Get all lesson parts since level column was removed
            // TODO: Need to redesign relationship between lessons and lesson_parts
            $lessonParts = LessonPart::with('questions')
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
            $lessonPart = LessonPart::with(['questions', 'lesson'])->find($lessonPartId);
            
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
     * Lấy câu hỏi của lesson part (thay thế cho lesson part contents)
     * GET /api/lesson-part-questions/{lessonPartId}
     */
    public function getLessonPartQuestions($lessonPartId)
    {
        try {
            $questions = \App\Models\Question::where('lesson_part_id', $lessonPartId)
                ->with('answers')
                ->orderBy('order_index')
                ->get();

            // Transform questions to ensure proper serialization
            $transformedQuestions = $questions->map(function($question) {
                return [
                    'questions_id' => $question->questions_id,
                    'lesson_part_id' => $question->lesson_part_id,
                    'question_type' => $question->question_type,
                    'question_text' => $question->question_text,
                    'media_url' => $question->media_url,
                    'order_index' => $question->order_index,
                    'created_at' => $question->created_at->toISOString(),
                    'updated_at' => $question->updated_at->toISOString(),
                    'answers' => $question->answers->map(function($answer) {
                        return [
                            'answers_id' => $answer->answers_id,
                            'questions_id' => $answer->questions_id,
                            'match_key' => $answer->match_key,
                            'answer_text' => $answer->answer_text,
                            'is_correct' => $answer->is_correct,
                            'feedback' => $answer->feedback,
                            'media_url' => $answer->media_url,
                            'order_index' => $answer->order_index,
                            'created_at' => $answer->created_at->toISOString(),
                            'updated_at' => $answer->updated_at->toISOString()
                        ];
                    })->toArray()
                ];
            });

            return response()->json($transformedQuestions, 200);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Lỗi server',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
