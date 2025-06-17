<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ExamResult;
use App\Models\StudentEvaluation;
use Illuminate\Http\Request;

class ExamController extends Controller
{
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
     * Lấy kết quả thi theo khóa học
     * GET /api/exam-results/course/{courseId}
     */
    public function getExamResultsByCourseId($courseId)
    {
        try {
            $results = ExamResult::where('course_id', $courseId)
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
