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
        $students = Student::all();
        $lessonParts = LessonPart::all();
        $courses = Course::all();

        // Tạo điểm số cho mỗi học sinh với các lesson parts
        foreach ($students as $student) {
            // Mỗi học sinh làm 10-15 lesson parts ngẫu nhiên
            $randomLessonParts = $lessonParts->random(rand(10, 15));

            foreach ($randomLessonParts as $lessonPart) {
                // Tìm course có cùng level với lesson part
                $course = $courses->where('level', $lessonPart->level)->first();

                if ($course) {
                    // Tạo 1-2 lần thử cho mỗi lesson part
                    for ($i = 1; $i <= rand(1, 2); $i++) {
                        $totalQuestions = rand(8, 12);
                        $correctAnswers = rand(4, $totalQuestions);
                        $score = round(($correctAnswers / $totalQuestions) * 10, 2);

                        LessonPartScore::create([
                            'lesson_part_id' => $lessonPart->lesson_part_id,
                            'student_id' => $student->student_id,
                            'course_id' => $course->course_id,
                            'attempt_no' => $i,
                            'score' => $score,
                            'total_questions' => $totalQuestions,
                            'correct_answers' => $correctAnswers,
                            'submit_time' => Carbon::now()->subDays(rand(1, 45)),
                        ]);
                    }
                }
            }
        }
    }
}
