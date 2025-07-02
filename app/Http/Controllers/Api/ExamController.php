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
            
            return response()->json($results, 200);
            
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
            // Validate course and student exist
            $course = \App\Models\Course::findOrFail($courseId);
            $student = \App\Models\Student::findOrFail($studentId);

            $results = ExamResult::where('course_id', $courseId)
                                ->where('student_id', $studentId)
                                ->with(['student', 'course'])
                                ->orderBy('exam_date', 'desc')
                                ->get();

            // Transform data with detailed information
            $transformedResults = $results->map(function($result) {
                $averageScore = ($result->listening_score + $result->speaking_score +
                               $result->reading_score + $result->writing_score) / 4;

                return [
                    'exam_result_id' => $result->exam_result_id,
                    'exam_date' => $result->exam_date,
                    'scores' => [
                        'listening' => $result->listening_score,
                        'speaking' => $result->speaking_score,
                        'reading' => $result->reading_score,
                        'writing' => $result->writing_score,
                        'average' => round($averageScore, 2)
                    ],
                    'overall_status' => $result->overall_status,
                    'status_text' => $result->overall_status ? 'Passed' : 'Failed',
                    'created_at' => $result->created_at,
                    'updated_at' => $result->updated_at
                ];
            });

            // Calculate student performance statistics
            $bestScore = $transformedResults->count() > 0 ? $transformedResults->max('scores.average') : 0;
            $latestScore = $transformedResults->count() > 0 ? $transformedResults->first()['scores']['average'] : 0;
            $totalAttempts = $transformedResults->count();
            $passedAttempts = $transformedResults->where('overall_status', 1)->count();

            return response()->json([
                'success' => true,
                'data' => $transformedResults,
                'student_info' => [
                    'student_id' => $student->student_id,
                    'fullname' => $student->fullname,
                    'email' => $student->email,
                    'phone' => $student->phone ?? null
                ],
                'course_info' => [
                    'course_id' => $course->course_id,
                    'course_name' => $course->course_name,
                    'level' => $course->level
                ],
                'performance_summary' => [
                    'total_attempts' => $totalAttempts,
                    'passed_attempts' => $passedAttempts,
                    'failed_attempts' => $totalAttempts - $passedAttempts,
                    'success_rate' => $totalAttempts > 0 ? round(($passedAttempts / $totalAttempts) * 100, 2) : 0,
                    'best_score' => $bestScore,
                    'latest_score' => $latestScore,
                    'improvement' => $totalAttempts > 1 ? round($latestScore - $transformedResults->last()['scores']['average'], 2) : 0
                ]
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
     * Nộp kết quả thi
     * POST /api/exam-results
     */
    public function submitExamResult(Request $request)
    {
        try {
            $result = ExamResult::create($request->all());
            
            return response()->json($result, 201);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Lỗi server',
                'message' => $e->getMessage()
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
