<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Answer;
use App\Models\StudentAnswer;
use App\Models\LessonPartScore;
use App\Models\StudentProgress;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    /**
     * Get question by ID with answers for Kotlin
     * GET /api/questions/{questionId}
     */
    public function getQuestionById($questionId)
    {
        try {
            $question = Question::with('answers')->findOrFail($questionId);

            // Transform data to match Kotlin data class structure
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

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Question not found',
                'message' => 'The specified question does not exist'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Server error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get questions by lesson part with answers
     * GET /api/questions/lesson-part/{lessonPartId}
     */
    public function getQuestionsByLessonPart($lessonPartId)
    {
        try {
            // Lấy câu hỏi ngẫu nhiên và với đáp án ngẫu nhiên
            $questions = Question::where('lesson_part_id', $lessonPartId)
                ->inRandomOrder() // Random câu hỏi
                ->with(['answers' => function ($query) {
                    $query->inRandomOrder(); // Random đáp án
                }])
                ->orderBy('order_index') // Nếu vẫn muốn ưu tiên order_index sau khi random
                ->get();

            // Transform questions để đảm bảo định dạng JSON như mong muốn
            $transformedQuestions = $questions->map(function($question) {
                return [
                    'questions_id'    => $question->questions_id,
                    'lesson_part_id'  => $question->lesson_part_id,
                    'question_type'   => $question->question_type,
                    'question_text'   => $question->question_text,
                    'media_url'       => $question->media_url,
                    'order_index'     => $question->order_index,
                    'created_at'      => $question->created_at->toISOString(),
                    'updated_at'      => $question->updated_at->toISOString(),
                    'answers'         => collect($question->answers)->map(function($answer) {
                        return [
                            'answers_id'     => $answer->answers_id,
                            'questions_id'   => $answer->questions_id,
                            'match_key'      => $answer->match_key,
                            'answer_text'    => $answer->answer_text,
                            'is_correct'     => $answer->is_correct,
                            'feedback'       => $answer->feedback,
                            'media_url'      => $answer->media_url,
                            'order_index'    => $answer->order_index,
                            'created_at'     => $answer->created_at->toISOString(),
                            'updated_at'     => $answer->updated_at->toISOString(),
                        ];
                    })->toArray(),
                ];
            });

            return response()->json($transformedQuestions, 200);
        } catch (\Exception $e) {
            return response()->json([
                'error'   => 'Server error',
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

            // Transform answers to ensure proper serialization
            $transformedAnswers = $answers->map(function($answer) {
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
            });

            return response()->json($transformedAnswers, 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Server error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Test endpoint for debugging
     * POST /api/lesson-part-scores/test
     */
    public function testSubmitLessonPartScore(Request $request)
    {
        \Log::info('Test endpoint called', ['request' => $request->all()]);
        return response()->json([
            'success' => true,
            'message' => 'Test endpoint working',
            'received_data' => $request->all()
        ]);
    }

    /**
     * Submit lesson part score
     * POST /api/lesson-part-scores
     * Matches Kotlin LessonPartScoreRequest and LessonPartScoreResponse
     */
    public function submitLessonPartScore(Request $request)
    {
        try {
            // Log incoming request for debugging
            \Log::info('submitLessonPartScore called', [
                'request_data' => $request->all()
            ]);

            // Validate input to match Kotlin LessonPartScoreRequest
            $validated = $request->validate([
                'student_id' => 'required|integer|exists:students,student_id',
                'lesson_part_id' => 'required|integer|exists:lesson_parts,lesson_part_id',
                'course_id' => 'required|integer|exists:courses,course_id',
                'attempt_no' => 'nullable|integer|min:1',
                'score' => 'required|numeric|min:0|max:10',
                'total_questions' => 'required|integer|min:1',
                'correct_answers' => 'required|integer|min:0'
            ]);

            \Log::info('Validation passed', ['validated' => $validated]);

            // Additional validation: correct_answers should not exceed total_questions
            if ($validated['correct_answers'] > $validated['total_questions']) {
                \Log::error('correct_answers exceeds total_questions', [
                    'correct_answers' => $validated['correct_answers'],
                    'total_questions' => $validated['total_questions']
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Correct answers cannot exceed total questions',
                    'score_data' => null,
                    'progress_updated' => false,
                    'is_completed' => false,
                    'course_progress_percentage' => 0.0
                ], 422);
            }

            // Auto-calculate attempt_no if not provided (null in Kotlin)
            if (!isset($validated['attempt_no']) || is_null($validated['attempt_no'])) {
                $maxAttempt = LessonPartScore::where('student_id', $validated['student_id'])
                    ->where('lesson_part_id', $validated['lesson_part_id'])
                    ->max('attempt_no');
                $validated['attempt_no'] = ($maxAttempt ?? 0) + 1;
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

            \Log::info('LessonPartScore created', ['score_id' => $lessonPartScore->score_id]);

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
            $courseProgress = 0.0;
            try {
                $courseProgress = $this->calculateStudentCourseProgress($validated['student_id'], $validated['course_id']);
                \Log::info('Course progress calculated', ['progress' => $courseProgress]);
            } catch (\Exception $e) {
                \Log::error('Error calculating course progress', ['error' => $e->getMessage()]);
                $courseProgress = 0.0;
            }

            // Return response matching Kotlin LessonPartScoreResponse
            return response()->json([
                'success' => true,
                'message' => 'Score submitted successfully',
                'score_data' => [
                    'score_id' => (int) $lessonPartScore->score_id,
                    'student_id' => (int) $lessonPartScore->student_id,
                    'lesson_part_id' => (int) $lessonPartScore->lesson_part_id,
                    'course_id' => (int) $lessonPartScore->course_id,
                    'attempt_no' => (int) $lessonPartScore->attempt_no,
                    'score' => (float) $lessonPartScore->score,
                    'total_questions' => (int) $lessonPartScore->total_questions,
                    'correct_answers' => (int) $lessonPartScore->correct_answers,
                    'submit_time' => (string) $lessonPartScore->submit_time,
                    'completion_percentage' => (float) round($completionPercentage, 2)
                ],
                'progress_updated' => (bool) $progressUpdated,
                'is_completed' => (bool) $isCompleted,
                'course_progress_percentage' => (float) round($courseProgress, 2)
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation failed in submitLessonPartScore', [
                'errors' => $e->errors(),
                'request_data' => $request->all()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'score_data' => null,
                'progress_updated' => false,
                'is_completed' => false,
                'course_progress_percentage' => 0.0
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error in submitLessonPartScore', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage(),
                'score_data' => null,
                'progress_updated' => false,
                'is_completed' => false,
                'course_progress_percentage' => 0.0
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
    * Submit student answers by course and lesson part (Create new answers)
    * POST /api/student-answers/student/{studentId}/course/{courseId}/lesson-part/{lessonPartId}
    */
    public function submitStudentAnswerByCourseAndLessonPart(Request $request, $studentId, $courseId, $lessonPartId)
    {
        try {
            // Validate input according to Kotlin StudentAnswersUpdateRequest
            $validated = $request->validate([
                'answers' => 'required|array|min:1',
                'answers.*.question_id' => 'required|integer|exists:questions,questions_id',
                'answers.*.answer_text' => 'required|string',
                'answers.*.is_correct' => 'nullable|boolean', // Optional from Kotlin
            ]);

            // Validate student, course, lesson_part exist
            $student = \App\Models\Student::findOrFail($studentId);
            $course = \App\Models\Course::findOrFail($courseId);
            $lessonPart = \App\Models\LessonPart::findOrFail($lessonPartId);

            $totalQuestions = count($validated['answers']);
            $correctAnswers = 0;
            $createdAnswers = [];

            // Delete existing answers for this student/lesson_part to create fresh
            StudentAnswer::where('student_id', $studentId)
                        ->whereHas('question', function($query) use ($lessonPartId) {
                            $query->where('lesson_part_id', $lessonPartId);
                        })
                        ->delete();

            foreach ($validated['answers'] as $answerData) {
                // Verify question belongs to lesson_part
                $question = Question::where('questions_id', $answerData['question_id'])
                                    ->where('lesson_part_id', $lessonPartId)
                                    ->first();

                if (!$question) {
                    return response()->json([
                        'success' => false,
                        'message' => "Question {$answerData['question_id']} not found in lesson part {$lessonPartId}",
                        'data' => null
                    ], 404);
                }

                // Calculate correctness if not provided
                $isCorrect = $answerData['is_correct'] ?? $this->calculateCorrectness($question, $answerData['answer_text']);

                if ($isCorrect) {
                    $correctAnswers++;
                }

                // Create new student answer
                $studentAnswer = StudentAnswer::create([
                    'student_id' => $studentId,
                    'questions_id' => $answerData['question_id'],
                    'course_id' => $courseId,
                    'answer_text' => $answerData['answer_text'],
                    'is_correct' => $isCorrect ? 1 : 0,
                    'answered_at' => now(),
                ]);

                $createdAnswers[] = $studentAnswer;
            }

            // Calculate score (0-10 scale) and progress percentage
            $score = $totalQuestions > 0
                ? round(($correctAnswers / $totalQuestions) * 10, 2)
                : 0.0;
            $progress = round(($correctAnswers / $totalQuestions) * 100, 2);

            // Create new lesson part score record
            $attemptNo = LessonPartScore::where('student_id', $studentId)
                                       ->where('lesson_part_id', $lessonPartId)
                                       ->where('course_id', $courseId)
                                       ->count() + 1;

            $lessonPartScore = LessonPartScore::create([
                'student_id' => $studentId,
                'lesson_part_id' => $lessonPartId,
                'course_id' => $courseId,
                'score' => $score,
                'total_questions' => $totalQuestions,
                'correct_answers' => $correctAnswers,
                'attempt_no' => $attemptNo,
                'submit_time' => now(),
            ]);

            // Update student progress if score >= 70%
            if ($score >= 7.0) {
                StudentProgress::updateOrCreate(
                    ['score_id' => $lessonPartScore->score_id],
                    [
                        'completion_status' => true,
                        'last_updated' => now(),
                    ]
                );
            }

            // Return response matching Kotlin StudentAnswersUpdateResponse
            return response()->json([
                'success' => true,
                'message' => 'Student answers submitted successfully',
                'data' => [
                    'updated_count' => $totalQuestions,
                    'score' => $score,
                    'progress' => $progress,
                ]
            ], 201); // 201 for created

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'data'    => ['errors' => $e->errors()],
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage(),
            ], 500);
        }
    }


    /**
     * Get recent submission score and progress for Kotlin
     * GET /api/student-answers/recent-submission/student/{studentId}/course/{courseId}/lesson-part/{lessonPartId}
     */
    public function getRecentSubmissionScoreAndProgress(Request $request, $studentId, $courseId, $lessonPartId)
    {
        try {
            // Validate parameters
            $student = \App\Models\Student::findOrFail($studentId);
            $course = \App\Models\Course::findOrFail($courseId);
            $lessonPart = \App\Models\LessonPart::findOrFail($lessonPartId);

            // Get submission_time parameter (optional)
            $submissionTime = $request->query('submission_time');

            // If submission_time provided, get answers from that time onwards
            // If not provided, get the most recent submission
            if ($submissionTime) {
                $studentAnswersRaw = StudentAnswer::where('student_id', $studentId)
                    ->whereHas('question', fn($q) => $q->where('lesson_part_id', $lessonPartId))
                    ->where('answered_at', '>=', $submissionTime)
                    ->with('question.answers')
                    ->orderBy('answered_at', 'desc')
                    ->get();
            } else {
                // Get most recent submission (latest answered_at)
                $latestAnswerTime = StudentAnswer::where('student_id', $studentId)
                    ->whereHas('question', fn($q) => $q->where('lesson_part_id', $lessonPartId))
                    ->max('answered_at');

                if (!$latestAnswerTime) {
                    // No submissions found
                    return response()->json([
                        'success' => true,
                        'data' => [
                            'score' => null,
                            'progress' => null,
                            'submission_time' => null,
                            'answers_count' => 0,
                            'total_questions' => \App\Models\Question::where('lesson_part_id', $lessonPartId)->count(),
                            'correct_answers' => 0,
                            'student_answers' => []
                        ]
                    ], 200);
                }

                $submissionTime = $latestAnswerTime;
                $studentAnswersRaw = StudentAnswer::where('student_id', $studentId)
                    ->whereHas('question', fn($q) => $q->where('lesson_part_id', $lessonPartId))
                    ->where('answered_at', '>=', $submissionTime)
                    ->with('question.answers')
                    ->orderBy('answered_at', 'desc')
                    ->get();
            }

            // Calculate totals
            $totalAnswers = $studentAnswersRaw->count();
            $totalQuestions = \App\Models\Question::where('lesson_part_id', $lessonPartId)->count();

            // Map to Kotlin StudentAnswerDetail structure
            $studentAnswers = $studentAnswersRaw->map(function($ans) {
                $question = $ans->question;

                // Find correct answer and feedback
                $correctAnswerModel = $question->answers
                    ->first(fn($a) => $a->is_correct == 1);

                $isCorrect = $this->calculateCorrectness($question, $ans->answer_text);

                return [
                    'question_id' => $question->questions_id,
                    'question_text' => $question->question_text,
                    'student_answer' => $ans->answer_text,
                    'correct_answer' => $correctAnswerModel->answer_text ?? null,
                    'is_correct' => $isCorrect,
                    'feedback' => $correctAnswerModel->feedback ?? null,
                ];
            });

            // Get lesson part score (latest submission)
            $lessonPartScore = LessonPartScore::where('student_id', $studentId)
                ->where('lesson_part_id', $lessonPartId)
                ->latest('submit_time')
                ->first();

            $score = $lessonPartScore ? (double)$lessonPartScore->score : null;

            // Calculate course progress percentage
            $progress = null;
            if (method_exists($this, 'calculateStudentCourseProgress')) {
                $progress = round($this->calculateStudentCourseProgress($studentId, $courseId), 2);
            }

            // Count correct answers
            $correctCount = $studentAnswers->where('is_correct', true)->count();

            // Return response matching Kotlin RecentSubmissionResponse with proper type casting
            return response()->json([
                'success' => true,
                'data' => [
                    'score' => $score !== null ? (float) $score : null,
                    'progress' => $progress !== null ? (float) $progress : null,
                    'submission_time' => $submissionTime !== null ? (string) $submissionTime : null,
                    'answers_count' => (int) $totalAnswers,
                    'total_questions' => (int) $totalQuestions,
                    'correct_answers' => (int) $correctCount,
                    'student_answers' => $studentAnswers->map(function($answer) {
                        return [
                            'question_id' => (int) $answer['question_id'],
                            'question_text' => (string) $answer['question_text'],
                            'student_answer' => (string) $answer['student_answer'],
                            'correct_answer' => $answer['correct_answer'] !== null ? (string) $answer['correct_answer'] : null,
                            'is_correct' => (bool) $answer['is_correct'],
                            'feedback' => $answer['feedback'] !== null ? (string) $answer['feedback'] : null,
                        ];
                    })->values()->toArray(),
                ]
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'data' => null
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'data' => null
            ], 500);
        }
    }
}
