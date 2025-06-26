<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
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
            // Lấy course và level của nó
            $course = Course::find($courseId);

            if (!$course) {
                return response()->json([
                    'error' => 'Không tìm thấy khóa học'
                ], 404);
            }

            // Lấy tất cả questions thuộc course này thông qua lesson parts
            $questions = Question::join('lesson_parts', 'questions.lesson_part_id', '=', 'lesson_parts.lesson_part_id')
                                ->where('lesson_parts.level', $course->level)
                                ->select('questions.*', 'lesson_parts.part_type', 'lesson_parts.lesson_part_id')
                                ->with('Answers')
                                ->orderBy('lesson_parts.order_index')
                                ->orderBy('questions.order_index')
                                ->get();

            // Group questions by lesson part for better organization
            $assignments = [];
            foreach ($questions as $question) {
                $partType = $question->part_type;

                if (!isset($assignments[$partType])) {
                    $assignments[$partType] = [
                        'lesson_part_id' => $question->lesson_part_id,
                        'part_type' => $partType,
                        'level' => $course->level,
                        'questions' => []
                    ];
                }

                $assignments[$partType]['questions'][] = [
                    'question_id' => $question->questions_id,
                    'question_text' => $question->question_text,
                    'question_type' => $question->question_type,
                    'media_url' => $question->media_url,
                    'order_index' => $question->order_index,
                    'answers' => $question->Answers
                ];
            }

            return response()->json(array_values($assignments), 200);

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
            // Trong hệ thống này, assignment ID = lesson_part_id
            $lessonPart = \App\Models\LessonPart::find($assignmentId);

            if (!$lessonPart) {
                return response()->json([
                    'error' => 'Không tìm thấy bài tập'
                ], 404);
            }

            // Lấy tất cả questions thuộc lesson part này
            $questions = Question::where('lesson_part_id', $assignmentId)
                                ->with('Answers')
                                ->orderBy('order_index')
                                ->get();

            $assignment = [
                'assignment_id' => $lessonPart->lesson_part_id,
                'title' => $lessonPart->part_type,
                'level' => $lessonPart->level,
                'content' => $lessonPart->content,
                'order_index' => $lessonPart->order_index,
                'total_questions' => $questions->count(),
                'questions' => $questions->map(function($question) {
                    return [
                        'question_id' => $question->questions_id,
                        'question_text' => $question->question_text,
                        'question_type' => $question->question_type,
                        'media_url' => $question->media_url,
                        'order_index' => $question->order_index,
                        'answers' => $question->Answers
                    ];
                })
            ];

            return response()->json($assignment, 200);

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
            // Kiểm tra assignment (lesson part) có tồn tại không
            $lessonPart = \App\Models\LessonPart::find($assignmentId);

            if (!$lessonPart) {
                return response()->json([
                    'error' => 'Không tìm thấy bài tập'
                ], 404);
            }

            // Lấy tất cả questions thuộc assignment này (updated for new structure)
            $questions = Question::where('lesson_part_id', $assignmentId)
                                ->with('Answers')
                                ->orderBy('order_index')
                                ->get();

            // Format response
            $formattedQuestions = $questions->map(function($question) {
                return [
                    'question_id' => $question->questions_id,
                    'question_text' => $question->question_text,
                    'question_type' => $question->question_type,
                    'media_url' => $question->media_url,
                    'order_index' => $question->order_index,
                    'answers' => $question->Answers
                ];
            });

            return response()->json($formattedQuestions, 200);

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
            $validated = $request->validate([
                'student_id' => 'required|integer',
                'questions_id' => 'required|integer',
                'course_id' => 'required|integer',
                'answer_text' => 'required|string'
            ]);

            $answer = StudentAnswer::create([
                'student_id' => $validated['student_id'],
                'questions_id' => $validated['questions_id'],
                'course_id' => $validated['course_id'],
                'answer_text' => $validated['answer_text'],
                'answered_at' => now()
            ]);

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
