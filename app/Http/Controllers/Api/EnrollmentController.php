<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CourseEnrollment;
use App\Models\Course;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EnrollmentController extends Controller
{
    /**
     * Get enrollments with details for student
     * GET /api/enrollments/student/{studentId}
     */
    public function getStudentEnrollments($studentId)
    {
        try {
            $enrollments = CourseEnrollment::where('student_id', $studentId)
                ->with(['course'])
                ->orderBy('registration_date', 'desc')
                ->get();

            $enrollmentsWithDetails = $enrollments->map(function($enrollment) {
                // Calculate progress percentage
                $progressPercentage = $this->calculateCourseProgress($enrollment->assigned_course_id, $enrollment->student_id);
                
                // Get last activity date (from student answers)
                $lastActivity = DB::table('student_answers')
                    ->where('student_id', $enrollment->student_id)
                    ->where('course_id', $enrollment->assigned_course_id)
                    ->orderBy('answered_at', 'desc')
                    ->value('answered_at');

                return [
                    'enrollment_id' => $enrollment->enrollment_id,
                    'student_id' => $enrollment->student_id,
                    'assigned_course_id' => $enrollment->assigned_course_id,
                    'registration_date' => $enrollment->registration_date,
                    'status' => $enrollment->status,
                    'course' => [
                        'course_id' => $enrollment->course->course_id,
                        'level' => $enrollment->course->level,
                        'course_name' => $enrollment->course->course_name,
                        'description' => $enrollment->course->description,
                        'status' => $enrollment->course->status
                    ],
                    'progress_percentage' => round($progressPercentage, 2),
                    'last_activity_date' => $lastActivity
                ];
            });

            return response()->json($enrollmentsWithDetails, 200);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Server error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update enrollment status
     * PUT /api/enrollments/{enrollmentId}/status
     */
    public function updateEnrollmentStatus(Request $request, $enrollmentId)
    {
        try {
            $validated = $request->validate([
                'status' => 'required|integer|in:1,2,3,4', // 1: chờ xác nhận, 2: đang học, 3: đạt, 4: không đạt
                'notes' => 'nullable|string'
            ]);

            $enrollment = CourseEnrollment::find($enrollmentId);
            if (!$enrollment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Enrollment not found'
                ], 404);
            }

            $enrollment->update(['status' => $validated['status']]);

            // Get updated enrollment with details
            $enrollmentWithDetails = [
                'enrollment_id' => $enrollment->enrollment_id,
                'student_id' => $enrollment->student_id,
                'assigned_course_id' => $enrollment->assigned_course_id,
                'registration_date' => $enrollment->registration_date,
                'status' => $enrollment->status,
                'course' => [
                    'course_id' => $enrollment->course->course_id,
                    'level' => $enrollment->course->level,
                    'course_name' => $enrollment->course->course_name,
                    'description' => $enrollment->course->description,
                    'status' => $enrollment->course->status
                ],
                'progress_percentage' => $this->calculateCourseProgress($enrollment->assigned_course_id, $enrollment->student_id),
                'last_activity_date' => DB::table('student_answers')
                    ->where('student_id', $enrollment->student_id)
                    ->where('course_id', $enrollment->assigned_course_id)
                    ->orderBy('answered_at', 'desc')
                    ->value('answered_at')
            ];

            return response()->json([
                'success' => true,
                'message' => 'Enrollment status updated successfully',
                'enrollment' => $enrollmentWithDetails
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Calculate course progress for a student using CORRECT formula
     * Private helper method
     */
    private function calculateCourseProgress($courseId, $studentId)
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
