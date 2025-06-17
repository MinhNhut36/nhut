<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ExamResult;
use App\Models\Student;
use App\Models\Course;
use Carbon\Carbon;

class ExamResultSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students = Student::all();
        $courses = Course::all();

        foreach ($students as $student) {
            // Mỗi học sinh có 2-3 kết quả thi
            $numExams = rand(2, 3);
            
            for ($i = 0; $i < $numExams; $i++) {
                $course = $courses->random();
                
                // Tạo điểm số ngẫu nhiên (0-10)
                $listeningScore = round(rand(50, 100) / 10, 1);
                $speakingScore = round(rand(50, 100) / 10, 1);
                $readingScore = round(rand(50, 100) / 10, 1);
                $writingScore = round(rand(50, 100) / 10, 1);
                
                // Tính điểm trung bình
                $averageScore = ($listeningScore + $speakingScore + $readingScore + $writingScore) / 4;
                $overallStatus = $averageScore >= 6.0 ? 1 : 0; // Pass if >= 6.0
                
                ExamResult::create([
                    'student_id' => $student->student_id,
                    'course_id' => $course->course_id,
                    'exam_date' => Carbon::now()->subDays(rand(1, 60)),
                    'lisstening_score' => $listeningScore, // Note: typo in migration
                    'speaking_score' => $speakingScore,
                    'reading_score' => $readingScore,
                    'writing_score' => $writingScore,
                    'overall_status' => $overallStatus,
                ]);
            }
        }
    }
}
