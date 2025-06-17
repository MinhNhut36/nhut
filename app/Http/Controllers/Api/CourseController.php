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
            // Kiểm tra xem đã đăng ký chưa
            $existingEnrollment = CourseEnrollment::where('student_id', $request->student_id)
                                                ->where('assigned_course_id', $request->assigned_course_id)
                                                ->first();
            
            if ($existingEnrollment) {
                return response()->json([
                    'error' => 'Học sinh đã đăng ký khóa học này'
                ], 409);
            }
            
            $enrollment = CourseEnrollment::create($request->all());
            
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
}
