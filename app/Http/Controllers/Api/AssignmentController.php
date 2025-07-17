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

            // Get all questions from all lesson parts since level column was removed
            // TODO: Need to redesign relationship between courses and lesson_parts
            $questions = Question::join('lesson_parts', 'questions.lesson_part_id', '=', 'lesson_parts.lesson_part_id')
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

            // Transform question to ensure proper serialization
            $transformedQuestion = [
                'questions_id' => $question->questions_id,
                'lesson_part_id' => $question->lesson_part_id,
                'question_type' => $question->question_type,
                'question_text' => $question->question_text,
                'media_url' => $question->media_url,
                'order_index' => $question->order_index,
                'created_at' => $question->created_at->toISOString(),
                'updated_at' => $question->updated_at->toISOString(),
                'answers' => $question->answers->map(function($answer) {
                    return [
                        'answers_id' => $answer->answers_id,
                        'questions_id' => $answer->questions_id,
                        'match_key' => $answer->match_key,
                        'answer_text' => $answer->answer_text,
                        'is_correct' => $answer->is_correct,
                        'feedback' => $answer->feedback,
                        'media_url' => $answer->media_url,
                        'order_index' => $answer->order_index,
                        'created_at' => $answer->created_at->toISOString(),
                        'updated_at' => $answer->updated_at->toISOString()
                    ];
                })->toArray()
            ];

            return response()->json($transformedQuestion, 200);

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
     * Lấy câu trả lời của học sinh theo course và lesson part
     * GET /api/student-answers/student/{studentId}/course/{courseId}/lesson-part/{lessonPartId}
     */
    public function getAnswersByStudentCourseAndLessonPart(Request $request, $studentId, $courseId, $lessonPartId)
    {
        try {
            $query = StudentAnswer::where('student_id', $studentId)
                                 ->where('course_id', $courseId)
                                 ->whereHas('Question', function($q) use ($lessonPartId) {
                                     $q->where('lesson_part_id', $lessonPartId);
                                 })
                                 ->with(['Question.lessonPart', 'Course', 'Student']);

            // Filter by date range if provided
            if ($request->has('from_date')) {
                $query->where('answered_at', '>=', $request->from_date);
            }

            if ($request->has('to_date')) {
                $query->where('answered_at', '<=', $request->to_date);
            }

            // Order by most recent first
            $answers = $query->orderBy('answered_at', 'desc')->get();

            // Add additional computed fields
            $answers = $answers->map(function($answer) {
                $answer->lesson_part_id = $answer->Question?->lesson_part_id;
                $answer->lesson_part_name = $answer->Question?->lessonPart?->part_name;
                return $answer;
            });

            return response()->json([
                'success' => true,
                'data' => $answers,
                'total_count' => $answers->count(),
                'student_id' => $studentId,
                'course_id' => $courseId,
                'lesson_part_id' => $lessonPartId,
                'filters_applied' => [
                    'from_date' => $request->from_date,
                    'to_date' => $request->to_date
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Lỗi server',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lấy câu trả lời của học sinh theo course, lesson part và answered_at
     * GET /api/student-answers/student/{studentId}/course/{courseId}/lesson-part/{lessonPartId}/answered-at/{answeredAt}
     */
    public function getAnswersByStudentCourseAndLessonPartAndDate(Request $request, $studentId, $courseId, $lessonPartId, $answeredAt)
    {
        try {
            // Parse answered_at parameter (expect format: YYYY-MM-DD or YYYY-MM-DD_HH:MM:SS)
            $answeredAtDate = str_replace('_', ' ', $answeredAt);

            // Validate date format
            if (!strtotime($answeredAtDate)) {
                return response()->json([
                    'success' => false,
                    'error' => 'Invalid answered_at format. Use YYYY-MM-DD or YYYY-MM-DD_HH:MM:SS'
                ], 400);
            }

            $query = StudentAnswer::where('student_id', $studentId)
                                 ->where('course_id', $courseId)
                                 ->whereHas('Question', function($q) use ($lessonPartId) {
                                     $q->where('lesson_part_id', $lessonPartId);
                                 })
                                 ->with(['Question.lessonPart', 'Course', 'Student']);

            // Filter by specific date or date range
            if (strlen($answeredAtDate) == 10) {
                // Date only (YYYY-MM-DD) - get all answers for that day
                $startTime = $answeredAtDate . ' 00:00:00';
                $endTime = $answeredAtDate . ' 23:59:59';
                $query->whereBetween('answered_at', [$startTime, $endTime]);
            } else {
                // DateTime - get answers within 1 hour of specified time
                $startTime = date('Y-m-d H:i:s', strtotime($answeredAtDate . ' -30 minutes'));
                $endTime = date('Y-m-d H:i:s', strtotime($answeredAtDate . ' +30 minutes'));
                $query->whereBetween('answered_at', [$startTime, $endTime]);
            }

            // Additional query parameters
            if ($request->has('exact_time') && $request->exact_time == 'true') {
                // Exact timestamp match (within 1 minute)
                $startTime = date('Y-m-d H:i:s', strtotime($answeredAtDate . ' -30 seconds'));
                $endTime = date('Y-m-d H:i:s', strtotime($answeredAtDate . ' +30 seconds'));
                $query->whereBetween('answered_at', [$startTime, $endTime]);
            }

            // Order by answered_at
            $answers = $query->orderBy('answered_at', 'desc')->get();

            // Add additional computed fields
            $answers = $answers->map(function($answer) {
                $answer->lesson_part_id = $answer->Question?->lesson_part_id;
                $answer->lesson_part_name = $answer->Question?->lessonPart?->part_name;
                $answer->formatted_answered_at = $answer->answered_at?->format('Y-m-d H:i:s');
                return $answer;
            });

            return response()->json([
                'success' => true,
                'data' => $answers,
                'total_count' => $answers->count(),
                'student_id' => $studentId,
                'course_id' => $courseId,
                'lesson_part_id' => $lessonPartId,
                'answered_at_filter' => $answeredAtDate,
                'query_parameters' => [
                    'exact_time' => $request->exact_time ?? 'false'
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
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
