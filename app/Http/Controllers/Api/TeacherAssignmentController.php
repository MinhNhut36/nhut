<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TeacherCourseAssignment;
use App\Models\Course;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TeacherAssignmentController extends Controller
{
    /**
     * Lấy phân công giảng viên theo khóa học
     * GET /api/teacher-assignments/course/{courseId}
     */
    public function getTeacherAssignmentsByCourseId($courseId)
    {
        try {
            // Validate course exists
            $course = Course::findOrFail($courseId);

            $assignments = TeacherCourseAssignment::where('course_id', $courseId)
                ->with(['teacher', 'course'])
                ->orderBy('assigned_at', 'desc')
                ->get();

            $transformedAssignments = $assignments->map(function ($assignment) {
                return [
                    'assignment_id' => $assignment->assignment_id,
                    'role' => $assignment->role,
                    'assigned_at' => $assignment->assigned_at,
                    'created_at' => $assignment->created_at,
                    'updated_at' => $assignment->updated_at,
                    'teacher' => [
                        'teacher_id' => $assignment->teacher->teacher_id,
                        'fullname' => $assignment->teacher->fullname,
                        'email' => $assignment->teacher->email,
                        'phone' => $assignment->teacher->phone ?? null,
                        'avatar' => $assignment->teacher->avatar ?? null
                    ],
                    'course' => [
                        'course_id' => $assignment->course->course_id,
                        'course_name' => $assignment->course->course_name,
                        'level' => $assignment->course->level,
                        'schedule' => $assignment->course->schedule ?? null
                    ]
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $transformedAssignments,
                'meta' => [
                    'course_id' => $courseId,
                    'course_name' => $course->course_name,
                    'total_assignments' => $transformedAssignments->count(),
                    'teachers_count' => $transformedAssignments->unique('teacher.teacher_id')->count(),
                    'roles' => $transformedAssignments->pluck('role')->unique()->values()
                ]
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'error' => 'Course not found',
                'message' => 'The specified course does not exist'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Server error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lấy phân công giảng viên theo ID
     * GET /api/teacher-assignments/{assignmentId}
     */
    public function getTeacherAssignmentById($assignmentId)
    {
        try {
            $assignment = TeacherCourseAssignment::with(['teacher', 'course'])
                ->findOrFail($assignmentId);

            return response()->json([
                'success' => true,
                'data' => [
                    'assignment_id' => $assignment->assignment_id,
                    'role' => $assignment->role,
                    'assigned_at' => $assignment->assigned_at,
                    'created_at' => $assignment->created_at,
                    'updated_at' => $assignment->updated_at,
                    'teacher' => [
                        'teacher_id' => $assignment->teacher->teacher_id,
                        'fullname' => $assignment->teacher->fullname,
                        'email' => $assignment->teacher->email,
                        'phone' => $assignment->teacher->phone ?? null,
                        'avatar' => $assignment->teacher->avatar ?? null
                    ],
                    'course' => [
                        'course_id' => $assignment->course->course_id,
                        'course_name' => $assignment->course->course_name,
                        'level' => $assignment->course->level,
                        'schedule' => $assignment->course->schedule ?? null
                    ]
                ]
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'error' => 'Assignment not found',
                'message' => 'The specified teacher assignment does not exist'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Server error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lấy phân công theo giảng viên
     * GET /api/teacher-assignments/teacher/{teacherId}
     */
    public function getAssignmentsByTeacherId($teacherId)
    {
        try {
            // Validate teacher exists
            $teacher = Teacher::findOrFail($teacherId);

            $assignments = TeacherCourseAssignment::where('teacher_id', $teacherId)
                ->with(['teacher', 'course'])
                ->orderBy('assigned_at', 'desc')
                ->get();

            $transformedAssignments = $assignments->map(function ($assignment) {
                return [
                    'assignment_id' => $assignment->assignment_id,
                    'role' => $assignment->role,
                    'assigned_at' => $assignment->assigned_at,
                    'course' => [
                        'course_id' => $assignment->course->course_id,
                        'course_name' => $assignment->course->course_name,
                        'level' => $assignment->course->level,
                        'schedule' => $assignment->course->schedule ?? null
                    ]
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $transformedAssignments,
                'teacher_info' => [
                    'teacher_id' => $teacher->teacher_id,
                    'fullname' => $teacher->fullname,
                    'email' => $teacher->email
                ],
                'meta' => [
                    'total_assignments' => $transformedAssignments->count(),
                    'courses_count' => $transformedAssignments->unique('course.course_id')->count(),
                    'roles' => $transformedAssignments->pluck('role')->unique()->values()
                ]
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'error' => 'Teacher not found',
                'message' => 'The specified teacher does not exist'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Server error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Tạo phân công giảng viên mới
     * POST /api/teacher-assignments
     */
    public function createTeacherAssignment(Request $request)
    {
        try {
            $validated = $request->validate([
                'teacher_id' => 'required|integer|exists:teachers,teacher_id',
                'course_id' => 'required|integer|exists:courses,course_id',
                'role' => 'required|string|in:Giảng viên,Trợ giảng,Phụ trách',
                'assigned_at' => 'required|date'
            ]);

            // Check if assignment already exists
            $existingAssignment = TeacherCourseAssignment::where('teacher_id', $validated['teacher_id'])
                ->where('course_id', $validated['course_id'])
                ->where('role', $validated['role'])
                ->first();

            if ($existingAssignment) {
                return response()->json([
                    'success' => false,
                    'error' => 'Assignment already exists',
                    'message' => 'This teacher is already assigned to this course with the same role'
                ], 409);
            }

            $assignment = TeacherCourseAssignment::create($validated);

            return response()->json([
                'success' => true,
                'data' => $assignment->load(['teacher', 'course']),
                'message' => 'Teacher assignment created successfully'
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'error' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Server error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cập nhật phân công giảng viên
     * PUT /api/teacher-assignments/{assignmentId}
     */
    public function updateTeacherAssignment(Request $request, $assignmentId)
    {
        try {
            $assignment = TeacherCourseAssignment::findOrFail($assignmentId);

            $validated = $request->validate([
                'role' => 'sometimes|string|in:Giảng viên,Trợ giảng,Phụ trách',
                'assigned_at' => 'sometimes|date'
            ]);

            $assignment->update($validated);

            return response()->json([
                'success' => true,
                'data' => $assignment->load(['teacher', 'course']),
                'message' => 'Teacher assignment updated successfully'
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'error' => 'Assignment not found',
                'message' => 'The specified teacher assignment does not exist'
            ], 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'error' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Server error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Xóa phân công giảng viên
     * DELETE /api/teacher-assignments/{assignmentId}
     */
    public function deleteTeacherAssignment($assignmentId)
    {
        try {
            $assignment = TeacherCourseAssignment::findOrFail($assignmentId);
            $assignment->delete();

            return response()->json([
                'success' => true,
                'message' => 'Teacher assignment deleted successfully'
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'error' => 'Assignment not found',
                'message' => 'The specified teacher assignment does not exist'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Server error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
