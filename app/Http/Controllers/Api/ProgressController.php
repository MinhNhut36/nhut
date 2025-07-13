<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\StudentProgress;
use App\Models\LessonPart;
use App\Models\Lesson;
use App\Models\Course;
use App\Models\Question;
use App\Models\StudentAnswer;
use App\Models\LessonPartScore;
use App\Models\CourseEnrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProgressController extends Controller
{
    /**
     * Create or update student progress
     * POST /api/student-progress
     */
    public function createOrUpdateStudentProgress(Request $request)
    {
        try {
            $validated = $request->validate([
                'score_id' => 'required|integer|exists:lesson_part_scores,score_id',
                'completion_status' => 'required|boolean'
            ]);

            // Get score with related data
            $score = LessonPartScore::with(['student', 'lessonPart', 'course'])
                                   ->findOrFail($validated['score_id']);

            // Check if progress already exists
            $existingProgress = StudentProgress::where('score_id', $validated['score_id'])->first();
            $isUpdate = $existingProgress !== null;

            $progress = StudentProgress::updateOrCreate(
                ['score_id' => $validated['score_id']],
                [
                    'completion_status' => $validated['completion_status'],
                    'last_updated' => now()
                ]
            );

            return response()->json([
                'success' => true,
                'message' => $isUpdate ? 'Progress updated successfully' : 'Progress created successfully',
                'data' => [
                    'progress_id' => $progress->progress_id,
                    'score_id' => $progress->score_id,
                    'completion_status' => $progress->completion_status,
                    'last_updated' => $progress->last_updated,
                    'is_new_record' => !$isUpdate
                ],
                'related_info' => [
                    'student_id' => $score->student_id,
                    'student_name' => $score->student->fullname ?? 'Unknown',
                    'lesson_part_id' => $score->lesson_part_id,
                    'course_id' => $score->course_id,
                    'score' => $score->score
                ]
            ], $isUpdate ? 200 : 201);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Score not found'
            ], 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Server error',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get course progress for a student using the CORRECT formula
     * GET /api/progress/course/{courseId}/student/{studentId}
     */
    public function getCourseProgress($courseId, $studentId)
    {
        try {
            $course = Course::find($courseId);
            if (!$course) {
                return response()->json(['error' => 'Course not found'], 404);
            }

            // Get enrollment status
            $enrollment = CourseEnrollment::where('assigned_course_id', $courseId)
                ->where('student_id', $studentId)
                ->first();

            if (!$enrollment) {
                return response()->json(['error' => 'Student not enrolled in this course'], 404);
            }

            // Áp dụng công thức ĐÚNG theo course_id và level của course
            $progressData = DB::select("
                SELECT
                    SUM(question_count) as total_questions,
                    SUM(answered_count) as total_answered,
                    SUM(correct_count) as total_correct
                FROM (
                    SELECT
                        lp.lesson_part_id,
                        COUNT(DISTINCT q.questions_id) AS question_count,
                        COUNT(DISTINCT CASE WHEN sa.questions_id IS NOT NULL THEN sa.questions_id END) AS answered_count,
                        COUNT(DISTINCT CASE WHEN a.is_correct = 1 AND LOWER(TRIM(sa.answer_text)) = LOWER(TRIM(a.answer_text)) THEN sa.questions_id END) AS correct_count
                    FROM lesson_parts lp
                    JOIN questions q ON lp.lesson_part_id = q.lesson_part_id
                    LEFT JOIN student_answers sa ON q.questions_id = sa.questions_id AND sa.student_id = ? AND sa.course_id = ?
                    LEFT JOIN answers a ON q.questions_id = a.questions_id AND a.is_correct = 1
                    WHERE lp.level = ?
                    GROUP BY lp.lesson_part_id
                ) AS progress_table
            ", [$studentId, $courseId, $course->level]);

            $totalQuestions = $progressData[0]->total_questions ?? 0;
            $totalAnswered = $progressData[0]->total_answered ?? 0;
            $totalCorrect = $progressData[0]->total_correct ?? 0;

            // Calculate progress percentage: (answered_questions / total_questions) * 100
            $overallProgress = $totalQuestions > 0 ? ($totalAnswered / $totalQuestions) * 100 : 0;

            // Kiểm tra điều kiện hoàn thành: answered_questions >= total_questions AND correct_answers >= 0.7 * total_questions
            $isCompleted = false;
            if ($totalQuestions > 0) {
                $isCompleted = ($totalAnswered >= $totalQuestions) && ($totalCorrect >= (0.7 * $totalQuestions));
            }

            // Get lesson parts for this course's level only
            $lessonParts = LessonPart::where('level', $course->level)->get();
            $lessonsProgress = [];

            foreach ($lessonParts as $lessonPart) {
                // Get progress for each lesson part individually (updated for new structure)
                $partData = DB::select("
                    SELECT
                        COUNT(DISTINCT q.questions_id) AS question_count,
                        COUNT(DISTINCT CASE WHEN sa.questions_id IS NOT NULL THEN sa.questions_id END) AS answered_count,
                        COUNT(DISTINCT CASE WHEN a.is_correct = 1 AND LOWER(TRIM(sa.answer_text)) = LOWER(TRIM(a.answer_text)) THEN sa.questions_id END) AS correct_count
                    FROM questions q
                    LEFT JOIN student_answers sa ON q.questions_id = sa.questions_id AND sa.student_id = ? AND sa.course_id = ?
                    LEFT JOIN answers a ON q.questions_id = a.questions_id AND a.is_correct = 1
                    WHERE q.lesson_part_id = ?
                ", [$studentId, $courseId, $lessonPart->lesson_part_id]);

                $partQuestions = $partData[0]->question_count ?? 0;
                $partAnswered = $partData[0]->answered_count ?? 0;
                $partCorrect = $partData[0]->correct_count ?? 0;

                $partProgress = $partQuestions > 0 ? ($partAnswered / $partQuestions) * 100 : 0;
                $partIsCompleted = $partQuestions > 0 && ($partAnswered >= $partQuestions) && ($partCorrect >= ($partQuestions * 0.7));

                $lessonsProgress[] = [
                    'lesson_part_id' => $lessonPart->lesson_part_id,
                    'level' => $course->level, // Use course level instead
                    'lesson_title' => $lessonPart->part_type,
                    'total_questions' => (int)$partQuestions,
                    'answered_questions' => (int)$partAnswered,
                    'correct_answers' => (int)$partCorrect,
                    'progress_percentage' => round($partProgress, 2),
                    'is_completed' => $partIsCompleted
                ];
            }

            // Estimate completion date (simple calculation)
            $estimatedDate = null;
            if ($overallProgress > 0 && $overallProgress < 100) {
                $daysRemaining = (100 - $overallProgress) * 2; // 2 days per 1% progress
                $estimatedDate = now()->addDays($daysRemaining)->format('Y-m-d');
            }

$data = [
            'course_id' => (int)$courseId,
            'course_name' => $course->course_name,
            'student_id' => (int)$studentId,
            'enrollment_status' => $enrollment->status->value,
            'total_questions' => (int)$totalQuestions,
            'answered_questions' => (int)$totalAnswered,
            'correct_answers' => (int)$totalCorrect,
            'overall_progress_percentage' => round($overallProgress, 2),
            'correct_percentage' => $totalQuestions > 0 ? round(($totalCorrect / $totalQuestions) * 100, 2) : 0,
            'is_completed' => $isCompleted,
            'required_correct_percentage' => 70,
            'lessons_progress' => $lessonsProgress,
            'total_time_spent_minutes' => 0,
            'estimated_completion_date' => $estimatedDate
        ];

        // Return direct object for Kotlin CourseProgress compatibility
        return response()->json($data, 200);

    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Server error: ' . $e->getMessage()
        ], 500);
        }
    }

    /**
     * Tính tiến độ của một lesson_part với học sinh (updated to work with course context)
     *
     * @param int $studentId
     * @param int $lessonPartId
     * @param int $courseId (optional)
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLessonPartProgress($studentId, $lessonPartId, $courseId = null)
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

            // 1. Tổng số câu hỏi trong lesson_part (updated for new structure)
            $totalQuestions = DB::table('questions')
                ->where('lesson_part_id', $lessonPartId)
                ->count();

            // 2. Số câu đã trả lời của học sinh (có thể filter theo course_id) - updated for new structure
            $answeredQuery = DB::table('student_answers')
                ->whereIn('questions_id', function($query) use ($lessonPartId) {
                    $query->select('questions_id')
                          ->from('questions')
                          ->where('lesson_part_id', $lessonPartId);
                })
                ->where('student_id', $studentId);

            if ($courseId) {
                $answeredQuery->where('course_id', $courseId);
            }

            $answeredQuestions = $answeredQuery->distinct('questions_id')->count();

            // 3. Số câu trả lời đúng (tính từ student_answers và answers) - updated for new structure
            $correctAnswersQuery = DB::table('student_answers as sa')
                ->join('questions as q', 'sa.questions_id', '=', 'q.questions_id')
                ->join('answers as a', function($join) {
                    $join->on('q.questions_id', '=', 'a.questions_id')
                         ->where('a.is_correct', '=', 1);
                })
                ->where('q.lesson_part_id', $lessonPartId)
                ->where('sa.student_id', $studentId)
                ->whereRaw('LOWER(TRIM(sa.answer_text)) = LOWER(TRIM(a.answer_text))');

            if ($courseId) {
                $correctAnswersQuery->where('sa.course_id', $courseId);
            }

            $correctAnswers = $correctAnswersQuery->count();

            // 4. Tính tiến độ theo công thức
            $progress = 0;
            $isCompleted = false;
            if ($totalQuestions > 0) {
                // Tính progress dựa trên số câu đã trả lời
                $progress = ($answeredQuestions / $totalQuestions) * 100;

                // Kiểm tra điều kiện hoàn thành: answered_questions >= total_questions AND correct_answers >= 0.7 * total_questions
                $isCompleted = ($answeredQuestions >= $totalQuestions) && ($correctAnswers >= ($totalQuestions * 0.7));

                // Nếu hoàn thành thì progress = 100%
                if ($isCompleted) {
                    $progress = 100;
                }
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'student_id' => (int)$studentId,
                    'lesson_part_id' => (int)$lessonPartId,
                    'course_id' => $courseId ? (int)$courseId : null,
                    'lesson_part_title' => $lessonPart->part_type,
                    'total_questions' => $totalQuestions,
                    'answered_questions' => $answeredQuestions,
                    'correct_answers' => $correctAnswers,
                    'progress_percentage' => round($progress, 2),
                    'is_completed' => $isCompleted,
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
     * Get lesson progress for student by lesson level
     * GET /api/progress/lesson/{lessonLevel}/student/{studentId}
     */
    public function getLessonProgress($lessonLevel, $studentId)
    {
        try {
            // Kiểm tra student và lesson có tồn tại không
            $student = Student::find($studentId);
            $lesson = Lesson::find($lessonLevel); // Primary key của Lesson là 'level'

            if (!$student || !$lesson) {
                return response()->json([
                    'success' => false,
                    'message' => 'Student hoặc Lesson không tồn tại'
                ], 404);
            }

            // 1. Tổng số lesson_parts (all since level column removed)
            // TODO: Need to redesign relationship between lessons and lesson_parts
            $totalParts = LessonPart::count();

            // 2. Tính số lesson_parts đã hoàn thành
            $completedParts = 0;
            $lessonParts = LessonPart::all();

            foreach ($lessonParts as $lessonPart) {
                // Lấy tiến độ của từng lesson_part (không cần course context cho lesson progress)
                $partProgressResponse = $this->getLessonPartProgress($studentId, $lessonPart->lesson_part_id, null);
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
                    'student_id' => (int)$studentId,
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
     * Get overall student progress
     * GET /api/progress/student/{studentId}/overview
     */
    public function getStudentProgressOverview($studentId)
    {
        try {
            // Get all enrollments for student
            $enrollments = CourseEnrollment::where('student_id', $studentId)->get();

            $totalCourses = $enrollments->count();
            $completedCourses = $enrollments->where('status', 3)->count(); // status 3 = đạt
            $inProgressCourses = $enrollments->where('status', 2)->count(); // status 2 = đang học

            // Calculate overall progress across all courses using CORRECT formula
            $totalProgress = 0;
            foreach ($enrollments as $enrollment) {
                $course = Course::find($enrollment->assigned_course_id);
                if ($course) {
                    // Áp dụng công thức ĐÚNG theo course_id (updated for new structure)
                    $progressData = DB::select("
                        SELECT
                            SUM(question_count) as total_questions,
                            SUM(answered_count) as total_answered
                        FROM (
                            SELECT
                                lp.lesson_part_id,
                                COUNT(DISTINCT q.questions_id) AS question_count,
                                COUNT(DISTINCT CASE WHEN sa.questions_id IS NOT NULL THEN sa.questions_id END) AS answered_count
                            FROM lesson_parts lp
                            JOIN questions q ON lp.lesson_part_id = q.lesson_part_id
                            LEFT JOIN student_answers sa ON q.questions_id = sa.questions_id AND sa.student_id = ? AND sa.course_id = ?
                            WHERE 1=1
                            GROUP BY lp.lesson_part_id
                        ) AS progress_table
                    ", [$studentId, $enrollment->assigned_course_id]);

                    $totalQuestions = $progressData[0]->total_questions ?? 0;
                    $totalAnswered = $progressData[0]->total_answered ?? 0;
                    $courseProgress = $totalQuestions > 0 ? ($totalAnswered / $totalQuestions) * 100 : 0;
                    $totalProgress += $courseProgress;
                }
            }

            $overallProgress = $totalCourses > 0 ? round($totalProgress / $totalCourses, 2) : 0;

            // Get total study time (from lesson part scores)
            $totalStudyTime = LessonPartScore::where('student_id', $studentId)
                ->count() * 30; // Assume 30 minutes per lesson part attempt

            $response = [
                'student_id' => (int)$studentId,
                'total_courses' => $totalCourses,
                'completed_courses' => $completedCourses,
                'in_progress_courses' => $inProgressCourses,
                'overall_progress_percentage' => $overallProgress,
                'total_study_time_minutes' => $totalStudyTime,
                'achievements_count' => $completedCourses, // Simple achievement count
                'current_streak_days' => 0 // Would need daily activity tracking
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
     * Lấy tiến độ tổng quan của học sinh theo courses đã đăng ký
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

            // Lấy tất cả courses mà student đã đăng ký
            $enrolledCourses = $student->courses()->get();
            $progressData = [];
            $totalCourses = $enrolledCourses->count();
            $completedCourses = 0;
            $totalProgress = 0;

            foreach ($enrolledCourses as $course) {
                $courseProgressResponse = $this->getCourseProgress($course->course_id, $studentId);
                $courseProgressData = json_decode($courseProgressResponse->getContent(), true);

                if ($courseProgressData['success']) {
                    $progressData[] = $courseProgressData['data'];
                    $totalProgress += $courseProgressData['data']['overall_progress_percentage'];

                    if ($courseProgressData['data']['is_completed']) {
                        $completedCourses++;
                    }
                }
            }

            $overallProgress = 0;
            if ($totalCourses > 0) {
                $overallProgress = $totalProgress / $totalCourses;
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'student_id' => (int)$studentId,
                    'student_name' => $student->fullname,
                    'total_courses' => $totalCourses,
                    'completed_courses' => $completedCourses,
                    'overall_progress_percentage' => round($overallProgress, 2),
                    'courses_progress' => $progressData
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
