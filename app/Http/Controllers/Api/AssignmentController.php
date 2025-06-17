<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Answer;
use App\Models\StudentAnswer;
use Illuminate\Http\Request;

class AssignmentController extends Controller
{
    /**
     * Lấy bài tập theo khóa học
     * GET /api/assignments/course/{courseId}
     */
    public function getAssignmentsByCourseId($courseId)
    {
        try {
            // Trong database hiện tại, assignments được quản lý thông qua questions
            // Tạm thời trả về empty array
            return response()->json([], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Lỗi server',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Lấy bài tập theo ID
     * GET /api/assignments/{assignmentId}
     */
    public function getAssignmentById($assignmentId)
    {
        try {
            // Tạm thời trả về empty object
            return response()->json(null, 404);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Lỗi server',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Lấy câu hỏi theo assignment ID
     * GET /api/questions/assignment/{assignmentId}
     */
    public function getQuestionsByAssignmentId($assignmentId)
    {
        try {
            // Tạm thời trả về empty array
            return response()->json([], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Lỗi server',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Lấy câu hỏi theo ID
     * GET /api/questions/{questionId}
     */
    public function getQuestionById($questionId)
    {
        try {
            $question = Question::with('Answers')->find($questionId);
            
            if (!$question) {
                return response()->json([
                    'error' => 'Không tìm thấy câu hỏi'
                ], 404);
            }
            
            return response()->json($question, 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Lỗi server',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Nộp câu trả lời
     * POST /api/student-answers
     */
    public function submitAnswer(Request $request)
    {
        try {
            $answer = StudentAnswer::create($request->all());
            
            return response()->json($answer, 201);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Lỗi server',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Lấy câu trả lời của học sinh
     * GET /api/student-answers/student/{studentId}
     */
    public function getAnswersByStudentId($studentId)
    {
        try {
            $answers = StudentAnswer::where('student_id', $studentId)
                                  ->with(['Question', 'Course', 'Student'])
                                  ->get();
            
            return response()->json($answers, 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Lỗi server',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Lấy đáp án theo câu hỏi
     * GET /api/answers/question/{questionId}
     */
    public function getAnswersByQuestionId($questionId)
    {
        try {
            $answers = Answer::where('questions_id', $questionId)
                           ->orderBy('order_index')
                           ->get();
            
            return response()->json($answers, 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Lỗi server',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Tạo đáp án mới
     * POST /api/answers
     */
    public function createAnswer(Request $request)
    {
        try {
            $answer = Answer::create($request->all());
            
            return response()->json($answer, 201);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Lỗi server',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
