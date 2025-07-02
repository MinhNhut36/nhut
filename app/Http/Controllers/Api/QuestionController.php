<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Answer;
use App\Models\StudentAnswer;
use App\Models\LessonPartScore;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    /**
     * Get questions by lesson part with answers
     * GET /api/questions/lesson-part/{lessonPartId}
     */
    public function getQuestionsByLessonPart($lessonPartId)
    {
        try {
            $questions = Question::where('lesson_part_id', $lessonPartId)
                ->with('answers')
                ->orderBy('order_index')
                ->get();

            return response()->json($questions, 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Server error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get answers for question
     * GET /api/answers/question/{questionId}
     */
    public function getAnswersForQuestion($questionId)
    {
        try {
            $answers = Answer::where('questions_id', $questionId)
                ->orderBy('order_index')
                ->get();

            return response()->json($answers, 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Server error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Submit lesson part score
     * POST /api/lesson-part-scores
     */
    public function submitLessonPartScore(Request $request)
    {
        try {
            $validated = $request->validate([
                'student_id' => 'required|integer',
                'lesson_part_id' => 'required|integer',
                'course_id' => 'required|integer',
                'attempt_no' => 'sometimes|integer',
                'score' => 'required|numeric',
                'total_questions' => 'required|integer',
                'correct_answers' => 'required|integer'
            ]);

            // Auto-calculate attempt_no if not provided
            if (!isset($validated['attempt_no'])) {
                $validated['attempt_no'] = LessonPartScore::where('student_id', $validated['student_id'])
                    ->where('lesson_part_id', $validated['lesson_part_id'])
                    ->max('attempt_no') + 1;
            }

            // Save lesson part score
            $lessonPartScore = LessonPartScore::create([
                'student_id' => $validated['student_id'],
                'lesson_part_id' => $validated['lesson_part_id'],
                'course_id' => $validated['course_id'],
                'attempt_no' => $validated['attempt_no'],
                'score' => $validated['score'],
                'total_questions' => $validated['total_questions'],
                'correct_answers' => $validated['correct_answers'],
                'submit_time' => now()
            ]);

            // Check if lesson part is completed (70% or higher)
            $completionPercentage = ($validated['correct_answers'] / $validated['total_questions']) * 100;
            $isCompleted = $completionPercentage >= 70;

            // Update or create student progress
            $progressUpdated = false;
            if ($isCompleted) {
                try {
                    \App\Models\StudentProgress::updateOrCreate(
                        ['score_id' => $lessonPartScore->score_id],
                        [
                            'completion_status' => 1,
                            'last_updated' => now()
                        ]
                    );
                    $progressUpdated = true;
                } catch (\Exception $e) {
                    // Progress update failed but score was saved
                    $progressUpdated = false;
                }
            }

            // Calculate overall course progress for this student
            $courseProgress = $this->calculateStudentCourseProgress($validated['student_id'], $validated['course_id']);

            return response()->json([
                'success' => true,
                'message' => 'Score submitted successfully',
                'score_data' => [
                    'score_id' => $lessonPartScore->score_id,
                    'score' => $lessonPartScore->score,
                    'attempt_no' => $lessonPartScore->attempt_no,
                    'submit_time' => $lessonPartScore->submit_time,
                    'completion_percentage' => round($completionPercentage, 2)
                ],
                'progress_updated' => $progressUpdated,
                'is_completed' => $isCompleted,
                'course_progress_percentage' => round($courseProgress, 2)
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Calculate student's overall progress for a course using CORRECT formula
     * Private helper method
     */
    private function calculateStudentCourseProgress($studentId, $courseId)
    {
        try {
            // Get course to find level
            $course = \App\Models\Course::find($courseId);
            if (!$course) {
                return 0;
            }

            // Áp dụng công thức ĐÚNG theo course_id (updated for new structure)
            $progressData = \Illuminate\Support\Facades\DB::select("
                SELECT
                    SUM(question_count) as total_questions,
                    SUM(answered_count) as total_answered
                FROM (
                    SELECT
                        lp.lesson_part_id,
                        COUNT(DISTINCT q.questions_id) AS question_count,
                        COUNT(DISTINCT CASE WHEN sa.questions_id IS NOT NULL THEN sa.questions_id END) AS answered_count
                    FROM lesson_parts lp
                    JOIN questions q ON lp.lesson_part_id = q.lesson_part_id
                    LEFT JOIN student_answers sa ON q.questions_id = sa.questions_id AND sa.student_id = ? AND sa.course_id = ?
                    WHERE lp.level = ?
                    GROUP BY lp.lesson_part_id
                ) AS progress_table
            ", [$studentId, $courseId, $course->level]);

            $totalQuestions = $progressData[0]->total_questions ?? 0;
            $totalAnswered = $progressData[0]->total_answered ?? 0;

            return $totalQuestions > 0 ? ($totalAnswered / $totalQuestions) * 100 : 0;

        } catch (\Exception) {
            return 0;
        }
    }

    /**
     * Calculate if answer is correct by comparing with correct answer
     */
    private function calculateCorrectness($question, $answerText)
    {
        try {
            $correctAnswer = Answer::where('questions_id', $question->questions_id)
                ->where('is_correct', 1)
                ->first();

            if (!$correctAnswer) {
                return false;
            }

            return strtolower(trim($answerText)) === strtolower(trim($correctAnswer->answer_text));
        } catch (\Exception) {
            return false;
        }
    }

    /**
     * Submit student answers by course and lesson part
     * POST /api/student-answers/student/{studentId}/course/{courseId}/lesson-part/{lessonPartId}
     */
    public function submitStudentAnswerByCourseAndLessonPart(Request $request, $studentId, $courseId, $lessonPartId)
    {
        try {
            $validated = $request->validate([
                'answers' => 'required|array',
                'answers.*.question_id' => 'required|integer|exists:questions,questions_id',
                'answers.*.answer_text' => 'required|string',
                'answers.*.is_correct' => 'sometimes|boolean'
            ]);

            $updatedAnswers = [];
            $totalQuestions = count($validated['answers']);
            $correctAnswers = 0;

            foreach ($validated['answers'] as $answerData) {
                // Kiểm tra question thuộc lesson_part
                $question = Question::where('questions_id', $answerData['question_id'])
                    ->where('lesson_part_id', $lessonPartId)
                    ->first();

                if (!$question) {
                    return response()->json([
                        'error' => 'Question not found in specified lesson part'
                    ], 404);
                }

                // Tính toán is_correct nếu không được truyền vào
                if (!isset($answerData['is_correct'])) {
                    $answerData['is_correct'] = $this->calculateCorrectness($question, $answerData['answer_text']);
                }

                if ($answerData['is_correct']) {
                    $correctAnswers++;
                }

                // Update hoặc create student answer
                $studentAnswer = StudentAnswer::updateOrCreate(
                    [
                        'student_id' => $studentId,
                        'questions_id' => $answerData['question_id']
                    ],
                    [
                        'answer_text' => $answerData['answer_text'],
                        'course_id' => $courseId,
                        'answered_at' => now()
                    ]
                );

                $updatedAnswers[] = $studentAnswer;
            }

            // Tính điểm và cập nhật lesson part score
            $score = ($totalQuestions > 0) ? ($correctAnswers / $totalQuestions) * 10 : 0;

            // Lấy attempt_no hiện tại
            $currentAttempt = LessonPartScore::where('student_id', $studentId)
                ->where('lesson_part_id', $lessonPartId)
                ->max('attempt_no') ?? 0;

            $lessonPartScore = LessonPartScore::updateOrCreate(
                [
                    'student_id' => $studentId,
                    'lesson_part_id' => $lessonPartId
                ],
                [
                    'course_id' => $courseId,
                    'attempt_no' => $currentAttempt + 1,
                    'score' => $score,
                    'total_questions' => $totalQuestions,
                    'correct_answers' => $correctAnswers,
                    'submit_time' => now()
                ]
            );

            // Cập nhật student progress nếu đạt 70%
            if ($score >= 7.0) {
                \App\Models\StudentProgress::updateOrCreate(
                    ['score_id' => $lessonPartScore->score_id],
                    [
                        'completion_status' => 1,
                        'last_updated' => now()
                    ]
                );
            }

            return response()->json([
                'message' => 'Student answers updated successfully',
                'data' => [
                    'updated_answers' => $updatedAnswers,
                    'lesson_part_score' => $lessonPartScore,
                    'total_questions' => $totalQuestions,
                    'correct_answers' => $correctAnswers,
                    'score' => $score,
                    'completion_percentage' => ($score / 10) * 100
                ]
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'error' => 'Validation failed',
                'details' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Server error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get recent submission score and progress
     * GET /api/student-answers/recent-submission/student/{studentId}/course/{courseId}/lesson-part/{lessonPartId}
     */
    public function getRecentSubmissionScoreAndProgress(Request $request, $studentId, $courseId, $lessonPartId)
    {
        try {
            $submissionTime = $request->query('submission_time');

            if (!$submissionTime) {
                return response()->json([
                    'error' => 'submission_time parameter is required'
                ], 400);
            }

            // Lấy student answers từ thời điểm submission_time
            $studentAnswers = StudentAnswer::where('student_id', $studentId)
                ->whereHas('question', function($query) use ($lessonPartId) {
                    $query->where('lesson_part_id', $lessonPartId);
                })
                ->where('answered_at', '>=', $submissionTime)
                ->with(['question' => function($query) {
                    $query->select('questions_id', 'lesson_part_id', 'question_text', 'question_type');
                }])
                ->orderBy('answered_at', 'desc')
                ->get();

            if ($studentAnswers->isEmpty()) {
                return response()->json([
                    'message' => 'No recent submissions found',
                    'data' => []
                ], 200);
            }

            // Lấy lesson part score
            $lessonPartScore = LessonPartScore::where('student_id', $studentId)
                ->where('lesson_part_id', $lessonPartId)
                ->first();

            // Lấy student progress
            $studentProgress = null;
            if ($lessonPartScore) {
                $studentProgress = \App\Models\StudentProgress::where('score_id', $lessonPartScore->score_id)
                    ->first();
            }

            // Tính toán thống kê - cần tính lại is_correct vì không lưu trong DB
            $totalAnswers = $studentAnswers->count();
            $correctAnswers = 0;

            foreach ($studentAnswers as $answer) {
                if ($this->calculateCorrectness($answer->question, $answer->answer_text)) {
                    $correctAnswers++;
                }
            }

            $accuracy = $totalAnswers > 0 ? ($correctAnswers / $totalAnswers) * 100 : 0;

            return response()->json([
                'message' => 'Recent submission data retrieved successfully',
                'data' => [
                    'student_id' => $studentId,
                    'course_id' => $courseId,
                    'lesson_part_id' => $lessonPartId,
                    'submission_time' => $submissionTime,
                    'recent_answers' => $studentAnswers,
                    'lesson_part_score' => $lessonPartScore,
                    'student_progress' => $studentProgress,
                    'statistics' => [
                        'total_answers' => $totalAnswers,
                        'correct_answers' => $correctAnswers,
                        'accuracy_percentage' => round($accuracy, 2),
                        'score' => $lessonPartScore ? $lessonPartScore->score : 0,
                        'is_completed' => $studentProgress ? $studentProgress->completion_status : false,
                        'attempts_count' => $lessonPartScore ? $lessonPartScore->attempts_count : 0
                    ]
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Server error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
