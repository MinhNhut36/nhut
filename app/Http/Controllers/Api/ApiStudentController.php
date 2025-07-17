<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\LessonPartScore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

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

            // Transform student to ensure enum values are properly serialized
            $studentArray = $student->toArray();
            if ($student->gender) {
                $studentArray['gender'] = $student->gender->value;
            }
            if ($student->is_status) {
                $studentArray['is_status'] = $student->is_status->value;
            }

            return response()->json($studentArray, 200);

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

            // Transform students to ensure enum values are properly serialized
            $transformedStudents = $students->map(function($student) {
                $studentArray = $student->toArray();
                if ($student->gender) {
                    $studentArray['gender'] = $student->gender->value;
                }
                if ($student->is_status) {
                    $studentArray['is_status'] = $student->is_status->value;
                }
                return $studentArray;
            });

            return response()->json($transformedStudents, 200);

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

            // Transform student to ensure enum values are properly serialized
            $studentArray = $student->toArray();
            if ($student->gender) {
                $studentArray['gender'] = $student->gender->value;
            }
            if ($student->is_status) {
                $studentArray['is_status'] = $student->is_status->value;
            }

            return response()->json($studentArray, 200);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Lỗi server',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lấy điểm số cao nhất của học sinh cho mỗi lesson_part (for Kotlin)
     * GET /api/scores/student/{studentId}
     */
    public function getScoresByStudentId($studentId)
    {
        try {
            // Validate student exists
            $student = Student::findOrFail($studentId);

            // Get all scores and filter to highest per lesson_part in PHP
            $allScores = LessonPartScore::where('student_id', $studentId)
                ->orderBy('lesson_part_id')
                ->orderBy('score', 'desc')
                ->get();

            // Group by lesson_part_id and take highest score for each
            $highestScores = $allScores->groupBy('lesson_part_id')
                ->map(function($scoresForLessonPart) {
                    return $scoresForLessonPart->sortByDesc('score')->first();
                })
                ->values();

            // Transform to match Kotlin LessonPartScore data class
            $transformedScores = $highestScores->map(function($score) {
                return [
                    'score_id' => (int) $score->score_id,
                    'student_id' => (int) $score->student_id,
                    'lesson_part_id' => (int) $score->lesson_part_id,
                    'course_id' => (int) $score->course_id,
                    'attempt_no' => (int) $score->attempt_no,
                    'score' => (float) $score->score,
                    'total_questions' => (int) $score->total_questions,
                    'correct_answers' => (int) $score->correct_answers,
                    'submit_time' => (string) $score->submit_time,
                    'created_at' => (string) $score->created_at,
                    'updated_at' => (string) $score->updated_at,
                ];
            });

            // Return direct array for Kotlin List<LessonPartScore>
            return response()->json($transformedScores->toArray(), 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Không tìm thấy học sinh'
            ], 404);
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
