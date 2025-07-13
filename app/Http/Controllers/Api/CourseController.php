<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseEnrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
     * Lấy khóa học theo ID (for Kotlin with updated data classes)
     * GET /api/courses/{courseId}
     */
    public function getCourseById($courseId)
    {
        try {
            $course = Course::findOrFail($courseId);

            // Load relationships separately for safety
            $lesson = null;
            $teachers = [];
            $students = [];
            $totalStudents = 0;
            $activeStudents = 0;

            try {
                // Try to load lesson with lesson parts
                $course->load(['lesson.lessonParts']);
                if ($course->lesson) {
                    // Transform lesson parts to match Kotlin LessonPart data class
                    $lessonParts = [];
                    if ($course->lesson->lessonParts) {
                        $lessonParts = $course->lesson->lessonParts->map(function($part) {
                            return [
                                'lesson_part_id' => (int) $part->lesson_part_id,
                                'level' => (string) ($part->level ?? ''),
                                'part_type' => (string) ($part->part_type ?? ''),
                                'content' => (string) ($part->content ?? ''),
                                'order_index' => (int) ($part->order_index ?? 0),
                                'created_at' => (string) $part->created_at,
                                'updated_at' => (string) $part->updated_at,
                                'questions' => [], // Will be loaded separately if needed
                                'lesson' => null, // Avoid circular reference
                                'partType' => (string) ($part->part_type ?? ''), // Alias
                                'type' => (string) ($part->part_type ?? ''), // Alias
                                'description' => (string) ($part->description ?? ''), // Additional field
                                'progress' => 0.0, // Additional field
                            ];
                        })->toArray();
                    }

                    $lesson = [
                        'level' => (string) ($course->lesson->level ?? ''),
                        'title' => (string) ($course->lesson->lesson_title ?? ''),
                        'description' => (string) ($course->lesson->description ?? ''),
                        'order_index' => (int) ($course->lesson->order_index ?? 0),
                        'created_at' => (string) $course->lesson->created_at,
                        'updated_at' => (string) $course->lesson->updated_at,
                        'lessonParts' => $lessonParts,
                        'courses' => [], // Empty for single course API
                    ];
                }
            } catch (\Exception $e) {
                // Lesson loading failed, keep null
            }

            try {
                // Try to load teachers using direct query
                $teacherAssignments = DB::table('teacher_course_assignments')
                    ->where('course_id', $course->course_id)
                    ->join('teachers', 'teacher_course_assignments.teacher_id', '=', 'teachers.teacher_id')
                    ->select('teachers.*', 'teacher_course_assignments.role', 'teacher_course_assignments.assigned_date')
                    ->get();

                $teachers = $teacherAssignments->map(function($teacher) {
                    return [
                        'teacher_id' => (int) $teacher->teacher_id,
                        'fullname' => (string) ($teacher->fullname ?? ''),
                        'username' => (string) ($teacher->username ?? ''),
                        'password' => (string) ($teacher->password ?? ''),
                        'role' => (string) ($teacher->role ?? 'Main Teacher'), // Direct role field
                        'date_of_birth' => (string) ($teacher->date_of_birth ?? ''),
                        'gender' => (int) ($teacher->gender ?? 0),
                        'email' => (string) ($teacher->email ?? ''),
                        'avatar' => (string) ($teacher->avatar ?? ''),
                        'is_status' => (int) ($teacher->is_status ?? 1),
                        'created_at' => (string) $teacher->created_at,
                        'updated_at' => (string) $teacher->updated_at,
                    ];
                })->toArray();
            } catch (\Exception $e) {
                // Teachers loading failed, keep empty array
                $teachers = [];
            }

            try {
                // Try to load students using direct query
                $studentEnrollments = DB::table('course_enrollments')
                    ->where('assigned_course_id', $course->course_id)
                    ->join('students', 'course_enrollments.student_id', '=', 'students.student_id')
                    ->select('students.*', 'course_enrollments.status', 'course_enrollments.registration_date')
                    ->get();

                $totalStudents = $studentEnrollments->count();
                $activeStudents = $studentEnrollments->where('status', 2)->count(); // status 2 = studying

                $students = $studentEnrollments->map(function($student) {
                    return [
                        'student_id' => (int) $student->student_id,
                        'avatar' => (string) ($student->avatar ?? ''),
                        'fullname' => (string) ($student->fullname ?? ''),
                        'username' => (string) ($student->username ?? ''),
                        'password' => (string) ($student->password ?? ''),
                        'date_of_birth' => (string) ($student->date_of_birth ?? ''),
                        'gender' => (int) ($student->gender ?? 0),
                        'email' => (string) ($student->email ?? ''),
                        'is_status' => (int) ($student->is_status ?? 1),
                        'created_at' => (string) $student->created_at,
                        'updated_at' => (string) $student->updated_at,
                    ];
                })->toArray();
            } catch (\Exception $e) {
                // Students loading failed, keep empty array
                $students = [];
                $totalStudents = 0;
                $activeStudents = 0;
            }

            // Transform course data to match updated Kotlin Course data class
            $transformedCourse = [
                'course_id' => (int) $course->course_id,
                'level' => (string) ($course->level ?? ''),
                'course_name' => (string) ($course->course_name ?? ''),
                'year' => (string) ($course->year ?? ''),
                'description' => (string) ($course->description ?? ''),
                'status' => (string) ($course->status ?? ''),
                'starts_date' => (string) ($course->starts_date ?? ''),
                'created_at' => (string) $course->created_at,
                'updated_at' => (string) $course->updated_at,
                'lesson' => $lesson,
                'teachers' => $teachers,
                'student_count' => $activeStudents,
                'total_students' => $totalStudents,
                'students' => $students,
            ];

            // Return direct Course object for Kotlin compatibility
            return response()->json($transformedCourse, 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Không tìm thấy khóa học'
            ], 404);
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
