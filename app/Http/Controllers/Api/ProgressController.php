<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\LessonPart;
use App\Models\Lesson;
use App\Models\Question;
use App\Models\StudentAnswer;
use App\Models\LessonPartScore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProgressController extends Controller
{
    /**
     * Tính tiến độ của một lesson_part với học sinh
     * 
     * @param int $studentId
     * @param int $lessonPartId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLessonPartProgress($studentId, $lessonPartId)
    {
        try {
            // Kiểm tra student và lesson_part có tồn tại không
            $student = Student::find($studentId);
            $lessonPart = LessonPart::find($lessonPartId);
            
            if (!$student || !$lessonPart) {
                return response()->json([
                    'success' => false,
                    'message' => 'Student hoặc Lesson Part không tồn tại'
                ], 404);
            }

            // 1. Tổng số câu hỏi trong lesson_part
            $totalQuestions = DB::table('questions')
                ->join('lesson_part_contents', 'questions.contents_id', '=', 'lesson_part_contents.contents_id')
                ->where('lesson_part_contents.lesson_part_id', $lessonPartId)
                ->count();

            // 2. Số câu đã trả lời của học sinh
            $answeredQuestions = DB::table('student_answers')
                ->whereIn('questions_id', function($query) use ($lessonPartId) {
                    $query->select('questions.questions_id')
                          ->from('questions')
                          ->join('lesson_part_contents', 'questions.contents_id', '=', 'lesson_part_contents.contents_id')
                          ->where('lesson_part_contents.lesson_part_id', $lessonPartId);
                })
                ->where('student_id', $studentId)
                ->distinct('questions_id')
                ->count();

            // 3. Số câu trả lời đúng từ lesson_part_scores
            $correctAnswers = LessonPartScore::where('student_id', $studentId)
                ->where('lesson_part_id', $lessonPartId)
                ->sum('correct_answers');

            // 4. Tính tiến độ theo công thức
            $progress = 0;
            if ($totalQuestions > 0) {
                // Kiểm tra điều kiện hoàn thành
                $isCompleted = ($answeredQuestions == $totalQuestions) && ($correctAnswers >= ($totalQuestions * 0.7));
                
                if ($isCompleted) {
                    $progress = 100; // Hoàn thành 100%
                } else {
                    // Tính tiến độ dựa trên số câu đã trả lời
                    $progress = min(($answeredQuestions / $totalQuestions) * 100, 99);
                }
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'student_id' => $studentId,
                    'lesson_part_id' => $lessonPartId,
                    'lesson_part_title' => $lessonPart->part_type,
                    'total_questions' => $totalQuestions,
                    'answered_questions' => $answeredQuestions,
                    'correct_answers' => $correctAnswers,
                    'progress_percentage' => round($progress, 2),
                    'is_completed' => $progress == 100,
                    'required_correct_answers' => ceil($totalQuestions * 0.7)
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi tính tiến độ lesson part: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Tính tiến độ của một lesson với học sinh
     * 
     * @param int $studentId
     * @param string $lessonLevel
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLessonProgress($studentId, $lessonLevel)
    {
        try {
            // Kiểm tra student và lesson có tồn tại không
            $student = Student::find($studentId);
            $lesson = Lesson::where('level', $lessonLevel)->first();
            
            if (!$student || !$lesson) {
                return response()->json([
                    'success' => false,
                    'message' => 'Student hoặc Lesson không tồn tại'
                ], 404);
            }

            // 1. Tổng số lesson_parts trong lesson
            $totalParts = LessonPart::where('level', $lessonLevel)->count();

            // 2. Tính số lesson_parts đã hoàn thành
            $completedParts = 0;
            $lessonParts = LessonPart::where('level', $lessonLevel)->get();
            
            foreach ($lessonParts as $lessonPart) {
                // Lấy tiến độ của từng lesson_part
                $partProgressResponse = $this->getLessonPartProgress($studentId, $lessonPart->lesson_part_id);
                $partProgressData = json_decode($partProgressResponse->getContent(), true);
                
                if ($partProgressData['success'] && $partProgressData['data']['is_completed']) {
                    $completedParts++;
                }
            }

            // 3. Tính tiến độ lesson
            $lessonProgress = 0;
            if ($totalParts > 0) {
                $lessonProgress = ($completedParts / $totalParts) * 100;
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'student_id' => $studentId,
                    'lesson_level' => $lessonLevel,
                    'lesson_title' => $lesson->title,
                    'total_parts' => $totalParts,
                    'completed_parts' => $completedParts,
                    'progress_percentage' => round($lessonProgress, 2),
                    'is_completed' => $lessonProgress == 100
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi tính tiến độ lesson: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lấy tiến độ tổng quan của học sinh
     * 
     * @param int $studentId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStudentOverallProgress($studentId)
    {
        try {
            $student = Student::find($studentId);
            
            if (!$student) {
                return response()->json([
                    'success' => false,
                    'message' => 'Student không tồn tại'
                ], 404);
            }

            // Lấy tất cả lessons
            $lessons = Lesson::all();
            $progressData = [];
            $totalLessons = $lessons->count();
            $completedLessons = 0;

            foreach ($lessons as $lesson) {
                $lessonProgressResponse = $this->getLessonProgress($studentId, $lesson->level);
                $lessonProgressData = json_decode($lessonProgressResponse->getContent(), true);
                
                if ($lessonProgressData['success']) {
                    $progressData[] = $lessonProgressData['data'];
                    
                    if ($lessonProgressData['data']['is_completed']) {
                        $completedLessons++;
                    }
                }
            }

            $overallProgress = 0;
            if ($totalLessons > 0) {
                $overallProgress = ($completedLessons / $totalLessons) * 100;
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'student_id' => $studentId,
                    'student_name' => $student->fullname,
                    'total_lessons' => $totalLessons,
                    'completed_lessons' => $completedLessons,
                    'overall_progress_percentage' => round($overallProgress, 2),
                    'lessons_progress' => $progressData
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi tính tiến độ tổng quan: ' . $e->getMessage()
            ], 500);
        }
    }
}
