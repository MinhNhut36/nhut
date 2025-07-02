<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StudentAnswer;
use App\Models\Student;
use App\Models\Question;
use App\Models\Course;
use Carbon\Carbon;

class StudentAnswerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students = Student::all();
        $questions = Question::all();
        $courses = Course::all();

        foreach ($students as $student) {
            // Mỗi học sinh trả lời 10-20 câu hỏi (tối đa là số questions có sẵn)
            $maxQuestions = min($questions->count(), 20);
            $randomQuestions = $questions->random(rand(10, $maxQuestions));
            
            foreach ($randomQuestions as $question) {
                $course = $courses->random();
                
                // Tạo câu trả lời mẫu
                $sampleAnswers = [
                    'Hello',
                    'am',
                    'True',
                    'went',
                    'children',
                    'I am a student',
                    'Thank you',
                    'False',
                    'good',
                    'because'
                ];
                
                StudentAnswer::create([
                    'student_id' => $student->student_id,
                    'questions_id' => $question->questions_id,
                    'course_id' => $course->course_id,
                    'answer_text' => $sampleAnswers[array_rand($sampleAnswers)],
                    'is_correct' => rand(0, 1),
                    'submit_time' => Carbon::now()->subDays(rand(1, 30)),
                    'answered_at' => Carbon::now()->subDays(rand(1, 30)),
                ]);
            }
        }
    }
}
