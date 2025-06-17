<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StudentRequest;
use App\Models\Student;
use App\Models\LessonPartScore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiStudentController extends Controller
{
    /**
     * Lấy thông tin học sinh theo ID
     * GET /api/students/{studentId}
     */
    public function getStudentById($studentId)
    {
        try {
            $student = Student::find($studentId);

            if (!$student) {
                return response()->json([
                    'error' => 'Không tìm thấy học sinh'
                ], 404);
            }

            return response()->json($student, 200);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Lỗi server',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lấy danh sách tất cả học sinh
     * GET /api/students
     */
    public function getAllStudents()
    {
        try {
            $students = Student::where('is_status', 1)->get();
            return response()->json($students, 200);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Lỗi server',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cập nhật thông tin học sinh
     * PUT /api/students/{studentId}
     */
    public function updateStudent(Request $request, $studentId)
    {
        try {
            $student = Student::find($studentId);

            if (!$student) {
                return response()->json([
                    'error' => 'Không tìm thấy học sinh'
                ], 404);
            }

            $student->update($request->all());

            return response()->json($student, 200);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Lỗi server',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lấy điểm số của học sinh
     * GET /api/scores/student/{studentId}
     */
    public function getScoresByStudentId($studentId)
    {
        try {
            $scores = LessonPartScore::where('student_id', $studentId)->get();
            return response()->json($scores, 200);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Lỗi server',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lấy điểm số theo lesson part và học sinh
     * GET /api/scores/lesson-part/{lessonPartId}/student/{studentId}
     */
    public function getScoreByLessonPartAndStudent($lessonPartId, $studentId)
    {
        try {
            $scores = LessonPartScore::where('lesson_part_id', $lessonPartId)
                                   ->where('student_id', $studentId)
                                   ->get();
            return response()->json($scores, 200);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Lỗi server',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Nộp điểm số
     * POST /api/scores
     */
    public function submitScore(Request $request)
    {
        try {
            $score = LessonPartScore::create($request->all());
            return response()->json($score, 201);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Lỗi server',
                'message' => $e->getMessage()
            ], 500);
        }
    }






}
