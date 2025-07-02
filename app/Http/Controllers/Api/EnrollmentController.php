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

    /**
     * Smart course registration based on level and schedule
     * POST /api/enrollments/smart-register/student/{studentId}
     */
    public function smartCourseRegistration(Request $request, $studentId)
    {
        try {
            $validated = $request->validate([
                'level' => 'required|string|in:A1,A2,A3,TA 2/6',
                'schedule_preference' => 'sometimes|string', // Optional field
            ]);

            // Tìm các courses phù hợp với level
            $matchingCourses = Course::where('level', $validated['level'])
                ->where('status', 'Đang mở lớp')
                ->get();

            // Filter by schedule preference if provided
            if (isset($validated['schedule_preference'])) {
                $matchingCourses = $matchingCourses->filter(function($course) use ($validated) {
                    return stripos($course->description, $validated['schedule_preference']) !== false;
                });
            }

            if ($matchingCourses->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No courses found matching the specified level and schedule',
                    'available_schedules' => $this->getAvailableSchedules($validated['level'])
                ], 404);
            }

            // Kiểm tra student đã đăng ký course nào trong level này chưa
            $existingEnrollment = CourseEnrollment::where('student_id', $studentId)
                ->whereHas('course', function($query) use ($validated) {
                    $query->where('level', $validated['level']);
                })
                ->whereIn('status', [1, 2]) // Pending hoặc studying
                ->first();

            if ($existingEnrollment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Student already enrolled in a course of this level',
                    'existing_enrollment' => [
                        'course_id' => $existingEnrollment->assigned_course_id,
                        'course_name' => $existingEnrollment->course->course_name,
                        'status' => $existingEnrollment->status
                    ]
                ], 409);
            }

            // Đếm số học sinh trong mỗi course
            $coursesWithStudentCount = $matchingCourses->map(function($course) {
                $studentCount = CourseEnrollment::where('assigned_course_id', $course->course_id)
                    ->whereIn('status', [1, 2, 3]) // Pending, studying, passed
                    ->count();

                return [
                    'course' => $course,
                    'student_count' => $studentCount
                ];
            })->sortBy('student_count');

            // Áp dụng logic phân bổ
            $selectedCourse = $this->selectCourseByAllocationLogic($coursesWithStudentCount);

            // Tạo enrollment
            $enrollment = CourseEnrollment::create([
                'student_id' => $studentId,
                'assigned_course_id' => $selectedCourse['course']->course_id,
                'status' => 1, // Pending confirmation
                'registration_date' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Student successfully registered for course',
                'enrollment' => [
                    'enrollment_id' => $enrollment->enrollment_id,
                    'student_id' => $studentId,
                    'assigned_course_id' => $selectedCourse['course']->course_id,
                    'course_name' => $selectedCourse['course']->course_name,
                    'level' => $selectedCourse['course']->level,
                    'description' => $selectedCourse['course']->description,
                    'status' => $enrollment->status,
                    'registration_date' => $enrollment->registration_date,
                    'current_student_count' => $selectedCourse['student_count'] + 1
                ],
                'allocation_info' => [
                    'total_matching_courses' => $coursesWithStudentCount->count(),
                    'selected_course_previous_count' => $selectedCourse['student_count'],
                    'allocation_reason' => $this->getAllocationReason($coursesWithStudentCount, $selectedCourse)
                ]
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Select course based on allocation logic
     */
    private function selectCourseByAllocationLogic($coursesWithStudentCount)
    {
        $courses = $coursesWithStudentCount->values();

        if ($courses->count() == 1) {
            return $courses->first();
        }

        // Lấy course có ít học sinh nhất và ít nhì
        $minCount = $courses->first()['student_count'];
        $secondMinCount = $courses->count() > 1 ? $courses->get(1)['student_count'] : $minCount;

        // Nếu chênh lệch > 3, chọn course có ít học sinh nhất
        if ($secondMinCount - $minCount > 3) {
            return $courses->first();
        }

        // Nếu chênh lệch <= 3, chọn ngẫu nhiên trong các courses có sĩ số gần nhau
        $similarCourses = $courses->filter(function($course) use ($minCount) {
            return $course['student_count'] - $minCount <= 3;
        });

        return $similarCourses->random();
    }

    /**
     * Get allocation reason for logging
     */
    private function getAllocationReason($coursesWithStudentCount, $selectedCourse)
    {
        $courses = $coursesWithStudentCount->values();

        if ($courses->count() == 1) {
            return 'Only one course available';
        }

        $minCount = $courses->first()['student_count'];
        $secondMinCount = $courses->count() > 1 ? $courses->get(1)['student_count'] : $minCount;

        if ($secondMinCount - $minCount > 3) {
            return 'Assigned to course with fewest students (difference > 3)';
        }

        return 'Random assignment among courses with similar student counts (difference <= 3)';
    }

    /**
     * Get available schedules for a level
     */
    private function getAvailableSchedules($level)
    {
        try {
            $schedules = Course::where('level', $level)
                ->where('status', 'Đang mở lớp')
                ->pluck('description')
                ->map(function($description) {
                    // Extract schedule from description
                    if (preg_match('/Lịch học: (.+?)\. Hình thức/', $description, $matches)) {
                        return $matches[1];
                    }
                    return $description;
                })
                ->unique()
                ->values();

            return $schedules;
        } catch (\Exception) {
            return [];
        }
    }
}
