<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Teacher;

class ApiTeacherController extends Controller
{
    /**
     * Lấy tất cả giảng viên
     * GET /api/teachers
     */
    public function getAllTeachers()
    {
        try {
            $teachers = Teacher::where('is_status', 1)->with('courses')->get();
            return response()->json($teachers, 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Lỗi server',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lấy giảng viên theo ID
     * GET /api/teachers/{teacherId}
     */
    public function getTeacherById($teacherId)
    {
        try {
            $teacher = Teacher::with(['courses', 'assignments'])->find($teacherId);

            if (!$teacher) {
                return response()->json([
                    'error' => 'Không tìm thấy giảng viên'
                ], 404);
            }

            return response()->json($teacher, 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Lỗi server',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lấy giảng viên theo khóa học
     * GET /api/teachers/course/{courseId}
     */
    public function getTeachersByCourseId($courseId)
    {
        try {
            $teachers = Teacher::whereHas('courses', function ($query) use ($courseId) {
                $query->where('courses.course_id', $courseId);
            })->with('courses')->get();

            return response()->json($teachers, 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Lỗi server',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
