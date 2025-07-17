<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ExamResult;
use App\Models\StudentEvaluation;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    /**
     * Lấy kết quả thi theo ID
     * GET /api/exam-results/{examId}
     */
    public function getExamById($examId)
    {
        try {
            $examResult = ExamResult::with(['student', 'course'])
                                   ->findOrFail($examId);

            return response()->json($examResult, 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Không tìm thấy kết quả thi',
                'message' => 'Exam result not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Lỗi server',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lấy kết quả thi theo học sinh
     * GET /api/exam-results/student/{studentId}
     */
    public function getExamResultsByStudentId($studentId)
    {
        try {
            $results = ExamResult::where('student_id', $studentId)
                                ->with(['student', 'course'])
                                ->orderBy('exam_date', 'desc')
                                ->get();

            // Transform data to match Kotlin ExamResult data class
            $transformedResults = $results->map(function($result) {
                return [
                    'exam_result_id' => (int) $result->exam_result_id,
                    'student_id' => (int) $result->student_id,
                    'course_id' => (int) $result->course_id,
                    'exam_date' => (string) $result->exam_date,
                    'listening_score' => (float) $result->listening_score,
                    'reading_score' => (float) $result->reading_score,
                    'speaking_score' => (float) $result->speaking_score,
                    'writing_score' => (float) $result->writing_score,
                    'overall_status' => (int) $result->overall_status,
                    'created_at' => (string) $result->created_at,
                    'updated_at' => (string) $result->updated_at,
                    'student' => $result->student,
                    'course' => $result->course,
                    'average_score' => (float) round(($result->listening_score + $result->reading_score +
                                                    $result->speaking_score + $result->writing_score) / 4, 2)
                ];
            });

            return response()->json($transformedResults, 200);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Lỗi server',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    

    /**
     * Lấy kết quả thi theo khóa học và học sinh cụ thể
     * GET /api/exam-results/course/{courseId}/student/{studentId}
     */
    public function getExamResultsByCourseAndStudent($courseId, $studentId)
    {
        try {
            $results = ExamResult::where('course_id', $courseId)
                                ->where('student_id', $studentId)
                                ->with(['student', 'course'])
                                ->orderBy('exam_date', 'desc')
                                ->get();

            return response()->json([
                'success' => true,
                'data' => $results
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'error' => 'Resource not found',
                'message' => 'The specified course or student does not exist'
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
     * Nộp kết quả thi (Tạo mới hoặc cập nhật dựa trên examId)
     * POST /api/exam-results/{examId}
     */
    public function submitExamResult(Request $request, $examId)
    {
        try {
            // Validate input
            $validated = $request->validate([
                'student_id' => 'required|integer|exists:students,student_id',
                'course_id' => 'required|integer|exists:courses,course_id',
                'exam_date' => 'required|date',
                'listening_score' => 'required|numeric|min:0|max:10',
                'speaking_score' => 'required|numeric|min:0|max:10',
                'reading_score' => 'required|numeric|min:0|max:10',
                'writing_score' => 'required|numeric|min:0|max:10',
                'overall_status' => 'required|integer|in:0,1'
            ]);

            $examData = [
                'student_id' => $validated['student_id'],
                'course_id' => $validated['course_id'],
                'exam_date' => $validated['exam_date'],
                'listening_score' => $validated['listening_score'],
                'speaking_score' => $validated['speaking_score'],
                'reading_score' => $validated['reading_score'],
                'writing_score' => $validated['writing_score'],
                'overall_status' => $validated['overall_status']
            ];

            // Check if examId exists in database
            $examResult = ExamResult::find($examId);

            if ($examResult) {
                // Update existing exam result
                $examResult->update($examData);
                $message = 'Exam result updated successfully';
                $statusCode = 200;
            } else {
                // Create new exam result with specified ID
                $examData['exam_result_id'] = $examId;
                $examResult = ExamResult::create($examData);
                $message = 'Exam result created successfully';
                $statusCode = 201;
            }

            // Load relationships and transform data to match Kotlin ExamResult data class
            $examResult->load(['student', 'course']);

            $transformedData = [
                'exam_result_id' => (int) $examResult->exam_result_id,
                'student_id' => (int) $examResult->student_id,
                'course_id' => (int) $examResult->course_id,
                'exam_date' => (string) $examResult->exam_date,
                'listening_score' => (float) $examResult->listening_score,
                'reading_score' => (float) $examResult->reading_score,
                'speaking_score' => (float) $examResult->speaking_score,
                'writing_score' => (float) $examResult->writing_score,
                'overall_status' => (int) $examResult->overall_status,
                'created_at' => (string) $examResult->created_at,
                'updated_at' => (string) $examResult->updated_at,
                'student' => $examResult->student,
                'course' => $examResult->course,
                'average_score' => (float) round(($examResult->listening_score + $examResult->reading_score +
                                                $examResult->speaking_score + $examResult->writing_score) / 4, 2)
            ];

            // Return response matching Kotlin ApiResponse<ExamResult> format
            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => $transformedData,
                'error' => null
            ], $statusCode);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'data' => null,
                'error' => 'Validation failed'
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => null,
                'error' => 'Server error'
            ], 500);
        }
    }
    
    /**
     * Lấy đánh giá học sinh
     * GET /api/evaluations/student/{studentId}
     */
    public function getStudentEvaluations($studentId)
    {
        try {
            $evaluations = StudentEvaluation::where('student_id', $studentId)
                                          ->with(['student', 'examResult'])
                                          ->orderBy('evaluation_date', 'desc')
                                          ->get();
            
            return response()->json($evaluations, 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Lỗi server',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Tạo đánh giá học sinh
     * POST /api/evaluations
     */
    public function createStudentEvaluation(Request $request)
    {
        try {
            $evaluation = StudentEvaluation::create($request->all());
            
            return response()->json($evaluation, 201);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Lỗi server',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
