<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\LessonPartScore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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



    /**
     * Đổi mật khẩu học sinh
     * PUT /api/students/{studentId}/change-password
     */
    public function changePassword(Request $request, $studentId)
    {
        try {
            // Validate input
            $validator = Validator::make($request->all(), [
                'current_password' => 'required|string',
                'new_password' => 'required|string|min:6',
                'new_password_confirmation' => 'required|string|same:new_password'
            ], [
                'current_password.required' => 'Mật khẩu hiện tại là bắt buộc',
                'new_password.required' => 'Mật khẩu mới là bắt buộc',
                'new_password.min' => 'Mật khẩu mới phải có ít nhất 6 ký tự',
                'new_password_confirmation.required' => 'Xác nhận mật khẩu là bắt buộc',
                'new_password_confirmation.same' => 'Xác nhận mật khẩu không khớp'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dữ liệu không hợp lệ',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Tìm student
            $student = Student::find($studentId);
            if (!$student) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy học sinh'
                ], 404);
            }

            // Kiểm tra mật khẩu hiện tại
            if (!Hash::check($request->current_password, $student->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Mật khẩu hiện tại không đúng',
                    'errors' => [
                        'current_password' => ['Mật khẩu hiện tại không chính xác']
                    ]
                ], 400);
            }

            // Cập nhật mật khẩu mới
            $student->password = Hash::make($request->new_password);
            $student->save();

            return response()->json([
                'success' => true,
                'message' => 'Mật khẩu đã được thay đổi thành công',
                'data' => [
                    'student_id' => (int)$student->student_id,
                    'updated_at' => $student->updated_at->toISOString()
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi server',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
