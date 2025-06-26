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
     * Submit student answer
     * POST /api/student-answers
     */
    public function submitStudentAnswer(Request $request)
    {
        try {
            $validated = $request->validate([
                'student_id' => 'required|integer',
                'questions_id' => 'required|integer',
                'course_id' => 'required|integer',
                'answer_text' => 'required|string'
            ]);

            // Get correct answer
            $correctAnswer = Answer::where('questions_id', $validated['questions_id'])
                ->where('is_correct', 1)
                ->first();

            $isCorrect = false;
            $feedback = 'Incorrect answer';
            $scorePoints = 0;

            if ($correctAnswer) {
                $isCorrect = strtolower(trim($validated['answer_text'])) === 
                           strtolower(trim($correctAnswer->answer_text));
                $feedback = $isCorrect ? 'Correct!' : ($correctAnswer->feedback ?? 'Try again');
                $scorePoints = $isCorrect ? 10 : 0;
            }

            // Save student answer
            StudentAnswer::create([
                'student_id' => $validated['student_id'],
                'questions_id' => $validated['questions_id'],
                'course_id' => $validated['course_id'],
                'answer_text' => $validated['answer_text'],
                'answered_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'is_correct' => $isCorrect,
                'correct_answer' => $correctAnswer?->answer_text,
                'feedback' => $feedback,
                'score_points' => $scorePoints
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
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
                'attempt_no' => 'required|integer',
                'score' => 'required|numeric',
                'total_questions' => 'required|integer',
                'correct_answers' => 'required|integer'
            ]);

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
                    \App\Models\StudentProgres::updateOrCreate(
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
}
