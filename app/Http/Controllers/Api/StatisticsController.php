<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Student;
use App\Models\CourseEnrollment;
use App\Models\LessonPartScore;
use App\Models\StudentAnswer;
use App\Models\Question;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{
    /**
     * Get comprehensive statistics
     * GET /api/statistics/overview
     */
    public function getOverviewStatistics()
    {
        try {
            // Total counts
            $totalStudents = Student::count();
            $totalCourses = Course::count();
            $totalEnrollments = CourseEnrollment::count();
            $totalQuestions = Question::count();

            // Enrollment statistics
            $pendingEnrollments = CourseEnrollment::where('status', 1)->count();
            $activeEnrollments = CourseEnrollment::where('status', 2)->count();
            $completedEnrollments = CourseEnrollment::where('status', 3)->count();
            $failedEnrollments = CourseEnrollment::where('status', 4)->count();

            // Course level distribution
            $courseLevelDistribution = Course::select('level', DB::raw('count(*) as count'))
                ->groupBy('level')
                ->orderBy('level')
                ->get()
                ->pluck('count', 'level');

            // Student progress statistics
            $studentsWithProgress = DB::table('student_answers')
                ->distinct('student_id')
                ->count();

            // Average completion rate
            $avgCompletionRate = $this->calculateAverageCompletionRate();

            // Recent activity (last 7 days)
            $recentAnswers = StudentAnswer::where('answered_at', '>=', now()->subDays(7))->count();
            $recentScores = LessonPartScore::where('submit_time', '>=', now()->subDays(7))->count();

            $response = [
                'overview' => [
                    'total_students' => $totalStudents,
                    'total_courses' => $totalCourses,
                    'total_enrollments' => $totalEnrollments,
                    'total_questions' => $totalQuestions,
                    'students_with_progress' => $studentsWithProgress,
                    'average_completion_rate' => round($avgCompletionRate, 2)
                ],
                'enrollment_status' => [
                    'pending' => $pendingEnrollments,
                    'active' => $activeEnrollments,
                    'completed' => $completedEnrollments,
                    'failed' => $failedEnrollments
                ],
                'course_levels' => $courseLevelDistribution,
                'recent_activity' => [
                    'answers_last_7_days' => $recentAnswers,
                    'scores_last_7_days' => $recentScores
                ]
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
     * Get detailed course statistics
     * GET /api/statistics/courses
     */
    public function getCourseStatistics()
    {
        try {
            $courses = Course::with(['enrollments'])->get();
            
            $courseStats = $courses->map(function($course) {
                $enrollments = $course->enrollments;
                $totalStudents = $enrollments->count();
                $activeStudents = $enrollments->where('status', 2)->count();
                $completedStudents = $enrollments->where('status', 3)->count();
                $failedStudents = $enrollments->where('status', 4)->count();
                
                // Calculate average progress for this course
                $avgProgress = $this->calculateCourseAverageProgress($course->course_id);
                
                return [
                    'course_id' => $course->course_id,
                    'course_name' => $course->course_name,
                    'level' => $course->level,
                    'total_students' => $totalStudents,
                    'active_students' => $activeStudents,
                    'completed_students' => $completedStudents,
                    'failed_students' => $failedStudents,
                    'completion_rate' => $totalStudents > 0 ? round(($completedStudents / $totalStudents) * 100, 2) : 0,
                    'average_progress' => round($avgProgress, 2)
                ];
            });

            return response()->json($courseStats, 200);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Server error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get student performance statistics
     * GET /api/statistics/students/performance
     */
    public function getStudentPerformanceStatistics()
    {
        try {
            // Simplified top performing students
            $topStudents = DB::table('course_enrollments')
                ->select('student_id', DB::raw('COUNT(*) as total_courses'))
                ->groupBy('student_id')
                ->orderBy('total_courses', 'DESC')
                ->limit(10)
                ->get();

            // Students with most activity (by answers submitted)
            $mostActiveStudents = DB::table('student_answers')
                ->select('student_id', DB::raw('COUNT(*) as total_answers'))
                ->groupBy('student_id')
                ->orderBy('total_answers', 'DESC')
                ->limit(10)
                ->get();

            // Performance distribution
            $performanceDistribution = DB::table('lesson_part_scores')
                ->select(
                    DB::raw('CASE 
                        WHEN (correct_answers / total_questions) >= 0.9 THEN "Excellent (90-100%)"
                        WHEN (correct_answers / total_questions) >= 0.8 THEN "Good (80-89%)"
                        WHEN (correct_answers / total_questions) >= 0.7 THEN "Average (70-79%)"
                        WHEN (correct_answers / total_questions) >= 0.6 THEN "Below Average (60-69%)"
                        ELSE "Poor (<60%)"
                    END as performance_level'),
                    DB::raw('COUNT(*) as count')
                )
                ->groupBy('performance_level')
                ->get();

            $response = [
                'top_students' => $topStudents,
                'most_active_students' => $mostActiveStudents,
                'performance_distribution' => $performanceDistribution
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
     * Calculate average completion rate across all courses
     */
    private function calculateAverageCompletionRate()
    {
        $courses = Course::all();
        $totalRate = 0;
        $courseCount = 0;

        foreach ($courses as $course) {
            $enrollments = CourseEnrollment::where('assigned_course_id', $course->course_id)->get();
            $totalStudents = $enrollments->count();
            $completedStudents = $enrollments->where('status', 3)->count();
            
            if ($totalStudents > 0) {
                $rate = ($completedStudents / $totalStudents) * 100;
                $totalRate += $rate;
                $courseCount++;
            }
        }

        return $courseCount > 0 ? $totalRate / $courseCount : 0;
    }

    /**
     * Calculate average progress for a specific course
     */
    private function calculateCourseAverageProgress($courseId)
    {
        $course = Course::find($courseId);
        if (!$course) {
            return 0;
        }

        $enrollments = CourseEnrollment::where('assigned_course_id', $courseId)->get();
        if ($enrollments->isEmpty()) {
            return 0;
        }

        $totalProgress = 0;
        $studentCount = 0;

        foreach ($enrollments as $enrollment) {
            $progress = $this->calculateStudentCourseProgress($enrollment->student_id, $courseId);
            $totalProgress += $progress;
            $studentCount++;
        }

        return $studentCount > 0 ? $totalProgress / $studentCount : 0;
    }

    /**
     * Calculate individual student progress for a course using CORRECT formula
     */
    private function calculateStudentCourseProgress($studentId, $courseId)
    {
        try {
            $course = Course::find($courseId);
            if (!$course) {
                return 0;
            }

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
                    WHERE lp.level = ?
                    GROUP BY lp.lesson_part_id
                ) AS progress_table
            ", [$studentId, $courseId, $course->level]);

            $totalQuestions = $progressData[0]->total_questions ?? 0;
            $totalAnswered = $progressData[0]->total_answered ?? 0;

            return $totalQuestions > 0 ? ($totalAnswered / $totalQuestions) * 100 : 0;

        } catch (\Exception) {
            return 0;
        }
    }
}
