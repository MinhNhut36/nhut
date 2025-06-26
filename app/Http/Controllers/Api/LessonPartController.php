<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LessonPart;
use App\Models\LessonPartScore;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LessonPartController extends Controller
{
    /**
     * Get lesson parts by course with progress
     * GET /api/lesson-parts/course/{courseId}?student_id={studentId}
     */
    public function getLessonPartsByCourse($courseId, Request $request)
    {
        try {
            // Get course level first
            $course = Course::find($courseId);
            if (!$course) {
                return response()->json(['error' => 'Course not found'], 404);
            }

            $studentId = $request->query('student_id');

            $lessonParts = LessonPart::where('level', $course->level)
                ->orderBy('order_index')
                ->get();

            // Transform to include real progress data from database
            $lessonPartsWithProgress = $lessonParts->map(function($part) use ($studentId, $courseId) {
                $lessonPartId = $part->lesson_part_id;

                // Get total questions for this lesson part
                $totalQuestions = DB::table('questions')
                    ->where('lesson_part_id', $lessonPartId)
                    ->count();

                // Initialize default values
                $isCompleted = false;
                $progressPercentage = 0.0;
                $bestScore = null;
                $attemptsCount = 0;

                // If student_id is provided, calculate real progress
                if ($studentId) {
                    // Get answered questions
                    $answeredQuestions = DB::table('student_answers')
                        ->whereIn('questions_id', function($query) use ($lessonPartId) {
                            $query->select('questions.questions_id')
                                  ->from('questions')
                                  ->where('lesson_part_id', $lessonPartId);
                        })
                        ->where('student_id', $studentId)
                        ->where('course_id', $courseId)
                        ->distinct('questions_id')
                        ->count();

                    // Get correct answers
                    $correctAnswers = DB::table('student_answers as sa')
                        ->join('questions as q', 'sa.questions_id', '=', 'q.questions_id')
                        ->join('answers as a', function($join) {
                            $join->on('q.questions_id', '=', 'a.questions_id')
                                 ->where('a.is_correct', '=', 1);
                        })
                        ->where('q.lesson_part_id', $lessonPartId)
                        ->where('sa.student_id', $studentId)
                        ->where('sa.course_id', $courseId)
                        ->where('sa.answer_text', DB::raw('a.answer_text'))
                        ->count();

                    // Get scores and attempts
                    $scores = LessonPartScore::where('lesson_part_id', $lessonPartId)
                        ->where('student_id', $studentId)
                        ->get();

                    $bestScore = $scores->max('score');
                    $attemptsCount = $scores->count();

                    // Calculate progress
                    if ($totalQuestions > 0) {
                        $progressPercentage = ($answeredQuestions / $totalQuestions) * 100;
                        $isCompleted = ($answeredQuestions == $totalQuestions) &&
                                      ($correctAnswers >= ($totalQuestions * 0.7));
                    }
                }

                return [
                    'lesson_part_id' => $part->lesson_part_id,
                    'level' => $part->level,
                    'part_type' => $part->part_type,
                    'content' => $part->content,
                    'order_index' => $part->order_index,
                    'total_questions' => $totalQuestions,
                    'is_completed' => $isCompleted,
                    'progress_percentage' => round($progressPercentage, 2),
                    'best_score' => $bestScore,
                    'attempts_count' => $attemptsCount
                ];
            });

            return response()->json($lessonPartsWithProgress, 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Server error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get lesson part details with contents
     * GET /api/lesson-parts/{lessonPartId}/details
     */
    public function getLessonPartDetails($lessonPartId)
    {
        try {
            $lessonPart = LessonPart::with(['questions', 'questions.answers'])
                ->find($lessonPartId);

            if (!$lessonPart) {
                return response()->json(['error' => 'Lesson part not found'], 404);
            }

            // Count total questions
            $totalQuestions = $lessonPart->questions->count();

            // Get questions for this lesson part
            $questions = $lessonPart->questions->map(function($question) {
                return [
                    'questions_id' => $question->questions_id,
                    'lesson_part_id' => $question->lesson_part_id,
                    'question_type' => $question->question_type,
                    'question_text' => $question->question_text,
                    'media_url' => $question->media_url,
                    'order_index' => $question->order_index
                ];
            });

            $response = [
                'lesson_part_id' => $lessonPart->lesson_part_id,
                'level' => $lessonPart->level,
                'part_type' => $lessonPart->part_type,
                'content' => $lessonPart->content,
                'questions' => $questions,
                'total_questions' => $totalQuestions,
                'estimated_time_minutes' => $totalQuestions * 2 // 2 minutes per question
            ];

            return response()->json($response, 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Server error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get lesson part progress for student
     * GET /api/lesson-parts/{lessonPartId}/student/{studentId}/progress
     */
    public function getLessonPartProgress($lessonPartId, $studentId)
    {
        try {
            $lessonPart = LessonPart::find($lessonPartId);
            if (!$lessonPart) {
                return response()->json(['error' => 'Lesson part not found'], 404);
            }

            // Get total questions
            $totalQuestions = DB::table('questions')
                ->where('lesson_part_id', $lessonPartId)
                ->count();

            // Get answered questions
            $answeredQuestions = DB::table('student_answers')
                ->whereIn('questions_id', function($query) use ($lessonPartId) {
                    $query->select('questions.questions_id')
                          ->from('questions')
                          ->where('lesson_part_id', $lessonPartId);
                })
                ->where('student_id', $studentId)
                ->distinct('questions_id')
                ->count();

            // Get scores and attempts
            $scores = LessonPartScore::where('lesson_part_id', $lessonPartId)
                ->where('student_id', $studentId)
                ->orderBy('attempt_no')
                ->get();

            $bestScore = $scores->max('score');
            $correctAnswers = $scores->max('correct_answers') ?? 0;

            // Calculate progress
            $progressPercentage = $totalQuestions > 0 ? 
                min(($answeredQuestions / $totalQuestions) * 100, 100) : 0;

            $isCompleted = ($answeredQuestions == $totalQuestions) && 
                          ($correctAnswers >= ($totalQuestions * 0.7));

            $attempts = $scores->map(function($score) {
                return [
                    'attempt_no' => $score->attempt_no,
                    'score' => $score->score,
                    'correct_answers' => $score->correct_answers,
                    'total_questions' => $score->total_questions,
                    'submit_time' => $score->submit_time
                ];
            });

            $response = [
                'success' => true,
                'data' => [
                    'student_id' => (int)$studentId,
                    'lesson_part_id' => (int)$lessonPartId,
                    'lesson_part_title' => $lessonPart->part_type,
                    'total_questions' => $totalQuestions,
                    'answered_questions' => $answeredQuestions,
                    'correct_answers' => $correctAnswers,
                    'progress_percentage' => round($progressPercentage, 2),
                    'is_completed' => $isCompleted,
                    'best_score' => $bestScore,
                    'attempts' => $attempts
                ]
            ];

            return response()->json($response, 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ], 500);
        }
    }
}
