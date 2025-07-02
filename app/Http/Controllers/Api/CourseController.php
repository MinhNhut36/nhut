<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseEnrollment;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    /**
     * Lấy tất cả khóa học
     * GET /api/courses
     */
    public function getAllCourses()
    {
        try {
            $courses = Course::with(['lesson', 'teachers'])->get();
            return response()->json($courses, 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Lỗi server',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Lấy khóa học theo ID
     * GET /api/courses/{courseId}
     */
    public function getCourseById($courseId)
    {
        try {
            $course = Course::with(['lesson', 'teachers', 'students'])->find($courseId);
            
            if (!$course) {
                return response()->json([
                    'error' => 'Không tìm thấy khóa học'
                ], 404);
            }
            
            return response()->json($course, 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Lỗi server',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Lấy khóa học theo học sinh
     * GET /api/courses/student/{studentId}
     */
    public function getCoursesByStudentId($studentId)
    {
        try {
            $courses = Course::whereHas('enrollments', function($query) use ($studentId) {
                $query->where('student_id', $studentId);
            })->with(['lesson', 'teachers'])->get();
            
            return response()->json($courses, 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Lỗi server',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Lấy khóa học theo level
     * GET /api/courses/level/{level}
     */
    public function getCoursesByLevel($level)
    {
        try {
            $courses = Course::where('level', $level)
                           ->with(['lesson', 'teachers'])
                           ->get();
            
            return response()->json($courses, 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Lỗi server',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Lấy đăng ký khóa học theo học sinh
     * GET /api/enrollments/student/{studentId}
     */
    public function getEnrollmentsByStudentId($studentId)
    {
        try {
            $enrollments = CourseEnrollment::where('student_id', $studentId)
                                         ->with(['course', 'student'])
                                         ->get();
            
            return response()->json($enrollments, 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Lỗi server',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Đăng ký khóa học
     * POST /api/enrollments
     */
    public function enrollStudent(Request $request)
    {
        try {
            $validated = $request->validate([
                'student_id' => 'required|integer|exists:students,student_id',
                'course_id' => 'required|integer|exists:courses,course_id',
                'enrollment_date' => 'sometimes|date'
            ]);

            // Map course_id to assigned_course_id for database compatibility
            $enrollmentData = [
                'student_id' => $validated['student_id'],
                'assigned_course_id' => $validated['course_id'],
                'enrollment_date' => $validated['enrollment_date'] ?? now(),
                'status' => 1 // PENDING_CONFIRMATION
            ];

            // Kiểm tra xem đã đăng ký chưa
            $existingEnrollment = CourseEnrollment::where('student_id', $validated['student_id'])
                                                ->where('assigned_course_id', $validated['course_id'])
                                                ->first();

            if ($existingEnrollment) {
                return response()->json([
                    'error' => 'Học sinh đã đăng ký khóa học này'
                ], 409);
            }

            $enrollment = CourseEnrollment::create($enrollmentData);

            return response()->json($enrollment, 201);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Lỗi server',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Lấy đăng ký theo khóa học
     * GET /api/enrollments/course/{courseId}
     */
    public function getEnrollmentsByCourseId($courseId)
    {
        try {
            $enrollments = CourseEnrollment::where('assigned_course_id', $courseId)
                                         ->with(['student', 'course'])
                                         ->get();
            
            return response()->json($enrollments, 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Lỗi server',
                'message' => $e->getMessage()
            ], 500);
        }
    }



    /**
     * Get student count for a course
     * GET /api/courses/{courseId}/students/count
     */
    public function getCourseStudentCount($courseId)
    {
        try {
            $course = Course::find($courseId);
            if (!$course) {
                return response()->json([
                    'success' => false,
                    'message' => 'Course not found'
                ], 404);
            }

            // Get enrollment counts by status
            $enrollments = CourseEnrollment::where('assigned_course_id', $courseId)->get();

            $totalStudents = $enrollments->count();
            $activeStudents = $enrollments->where('status', 2)->count(); // status 2 = đang học
            $completedStudents = $enrollments->where('status', 3)->count(); // status 3 = đạt
            $pendingStudents = $enrollments->where('status', 1)->count(); // status 1 = chờ xác nhận
            $failedStudents = $enrollments->where('status', 4)->count(); // status 4 = không đạt

            $response = [
                'course_id' => (int)$courseId,
                'course_name' => $course->course_name,
                'total_students' => $totalStudents,
                'active_students' => $activeStudents,
                'completed_students' => $completedStudents,
                'pending_students' => $pendingStudents,
                'failed_students' => $failedStudents,
                'completion_rate' => $totalStudents > 0 ? round(($completedStudents / $totalStudents) * 100, 2) : 0
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
