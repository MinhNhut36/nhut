<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ExamResult;

use Carbon\Carbon;

class ExamResultSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $enrollments = \App\Models\CourseEnrollment::with(['student', 'course'])->get();

        foreach ($enrollments as $enrollment) {
            // Chỉ tạo exam results cho courses đã passed (status = 3)
            $statusValue = $enrollment->status->value;
            if ($statusValue == 3) {
                $this->createPassedExamResult($enrollment);
            } elseif ($statusValue == 4) {
                $this->createFailedExamResult($enrollment);
            }
        }
    }

    private function createPassedExamResult($enrollment)
    {
        // Passed courses: tất cả kỹ năng >= 5.0, trung bình >= 6.0
        $listeningScore = round(rand(50, 90) / 10, 1); // 5.0-9.0
        $speakingScore = round(rand(50, 90) / 10, 1);
        $readingScore = round(rand(50, 90) / 10, 1);
        $writingScore = round(rand(50, 90) / 10, 1);

        // Ensure all scores >= 5.0
        $listeningScore = max(5.0, $listeningScore);
        $speakingScore = max(5.0, $speakingScore);
        $readingScore = max(5.0, $readingScore);
        $writingScore = max(5.0, $writingScore);

        ExamResult::create([
            'student_id' => $enrollment->student_id,
            'course_id' => $enrollment->assigned_course_id,
            'exam_date' => Carbon::parse($enrollment->registration_date)->addDays(rand(45, 90)),
            'lisstening_score' => $listeningScore,
            'speaking_score' => $speakingScore,
            'reading_score' => $readingScore,
            'writing_score' => $writingScore,
            'overall_status' => 1, // Passed
        ]);
    }

    private function createFailedExamResult($enrollment)
    {
        // Failed courses: ít nhất 1 kỹ năng < 5.0 hoặc trung bình < 6.0
        $scores = [];
        for ($i = 0; $i < 4; $i++) {
            $scores[] = round(rand(20, 80) / 10, 1); // 2.0-8.0
        }

        // Ensure at least one skill < 5.0 or average < 6.0
        $average = array_sum($scores) / 4;
        if ($average >= 6.0 && min($scores) >= 5.0) {
            // Force one score to be low
            $scores[rand(0, 3)] = round(rand(20, 49) / 10, 1);
        }

        ExamResult::create([
            'student_id' => $enrollment->student_id,
            'course_id' => $enrollment->assigned_course_id,
            'exam_date' => Carbon::parse($enrollment->registration_date)->addDays(rand(45, 90)),
            'lisstening_score' => $scores[0],
            'speaking_score' => $scores[1],
            'reading_score' => $scores[2],
            'writing_score' => $scores[3],
            'overall_status' => 0, // Failed
        ]);
    }
}
