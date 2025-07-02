<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LessonPartScore;
use App\Models\Student;
use App\Models\LessonPart;
use App\Models\Course;
use Carbon\Carbon;

class LessonPartScoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $enrollments = \App\Models\CourseEnrollment::with(['student', 'course'])->get();

        foreach ($enrollments as $enrollment) {
            $this->createScoresForEnrollment($enrollment);
        }
    }

    private function createScoresForEnrollment($enrollment)
    {
        $statusValue = $enrollment->status->value;

        // Chỉ tạo scores cho enrollments có status >= 2 (studying, passed, failed)
        if ($statusValue < 2) {
            return;
        }

        // Get all lesson parts since level column was removed
        // TODO: Need to redesign relationship between courses and lesson_parts
        $lessonParts = LessonPart::all();

        foreach ($lessonParts as $lessonPart) {
            $this->createScoreForLessonPart($enrollment, $lessonPart, $statusValue);
        }
    }

    private function createScoreForLessonPart($enrollment, $lessonPart, $statusValue)
    {
        // Số lần thử dựa trên status
        $maxAttempts = match($statusValue) {
            2 => rand(1, 2), // Studying: 1-2 attempts
            3 => rand(2, 3), // Passed: 2-3 attempts (improvement over time)
            4 => rand(2, 4), // Failed: 2-4 attempts (multiple tries)
            default => 1
        };

        for ($attempt = 1; $attempt <= $maxAttempts; $attempt++) {
            $totalQuestions = 10; // Mỗi lesson part có 10 câu hỏi

            // Tỷ lệ đúng dựa trên status và attempt
            $correctRate = $this->getCorrectRate($statusValue, $attempt, $maxAttempts);
            $correctAnswers = max(1, round($totalQuestions * $correctRate));
            $score = round(($correctAnswers / $totalQuestions) * 10, 2);

            LessonPartScore::create([
                'lesson_part_id' => $lessonPart->lesson_part_id,
                'student_id' => $enrollment->student_id,
                'course_id' => $enrollment->assigned_course_id,
                'attempt_no' => $attempt,
                'score' => $score,
                'total_questions' => $totalQuestions,
                'correct_answers' => $correctAnswers,
                'submit_time' => $this->getSubmitTime($enrollment, $attempt),
            ]);
        }
    }

    private function getCorrectRate($statusValue, $attempt, $maxAttempts)
    {
        switch ($statusValue) {
            case 2: // Studying
                return 0.5 + ($attempt / $maxAttempts) * 0.2; // 50-70%
            case 3: // Passed
                $baseRate = 0.6 + ($attempt / $maxAttempts) * 0.3; // 60-90%
                return min(0.95, $baseRate);
            case 4: // Failed
                return 0.3 + ($attempt / $maxAttempts) * 0.2; // 30-50%
            default:
                return 0.5;
        }
    }

    private function getSubmitTime($enrollment, $attempt)
    {
        $baseTime = Carbon::parse($enrollment->registration_date);
        $daysOffset = ($attempt - 1) * rand(7, 14) + rand(1, 7);
        return $baseTime->addDays($daysOffset);
    }
}
