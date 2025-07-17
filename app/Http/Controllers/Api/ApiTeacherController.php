<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Teacher;

class ApiTeacherController extends Controller
{
    /**
     * Lấy tất cả giáo viên
     * GET /api/teachers
     */
    public function getAllTeachers()
    {
        try {
            $teachers = Teacher::where('is_status', 1)->with('courses')->get();

            // Transform teachers to ensure enum values are properly serialized
            $transformedTeachers = $teachers->map(function($teacher) {
                $teacherArray = $teacher->toArray();
                if (isset($teacherArray['courses'])) {
                    $teacherArray['courses'] = collect($teacherArray['courses'])->map(function($course) use ($teacher) {
                        $courseModel = $teacher->courses->where('course_id', $course['course_id'])->first();
                        $course['status'] = $courseModel && $courseModel->status ? $courseModel->status->value : '';
                        return $course;
                    })->toArray();
                }
                return $teacherArray;
            });

            return response()->json($transformedTeachers, 200);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Lỗi server',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lấy giáo viên theo ID
     * GET /api/teachers/{teacherId}
     */
    public function getTeacherById($teacherId)
    {
        try {
            $teacher = Teacher::with(['courses', 'assignments'])->find($teacherId);

            if (!$teacher) {
                return response()->json([
                    'error' => 'Không tìm thấy giáo viên'
                ], 404);
            }

            // Transform teacher to ensure enum values are properly serialized
            $teacherArray = $teacher->toArray();
            if (isset($teacherArray['courses'])) {
                $teacherArray['courses'] = collect($teacherArray['courses'])->map(function($course) use ($teacher) {
                    $courseModel = $teacher->courses->where('course_id', $course['course_id'])->first();
                    $course['status'] = $courseModel && $courseModel->status ? $courseModel->status->value : '';
                    return $course;
                })->toArray();
            }

            return response()->json($teacherArray, 200);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Lỗi server',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lấy giáo viên theo khóa học
     * GET /api/teachers/course/{courseId}
     */
    public function getTeachersByCourseId($courseId)
    {
        try {
            $teachers = Teacher::whereHas('courses', function($query) use ($courseId) {
                $query->where('courses.course_id', $courseId);
            })->with('courses')->get();

            // Transform teachers to ensure enum values are properly serialized
            $transformedTeachers = $teachers->map(function($teacher) {
                $teacherArray = $teacher->toArray();
                if (isset($teacherArray['courses'])) {
                    $teacherArray['courses'] = collect($teacherArray['courses'])->map(function($course) use ($teacher) {
                        $courseModel = $teacher->courses->where('course_id', $course['course_id'])->first();
                        $course['status'] = $courseModel && $courseModel->status ? $courseModel->status->value : '';
                        return $course;
                    })->toArray();
                }
                return $teacherArray;
            });

            return response()->json($transformedTeachers, 200);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Lỗi server',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
