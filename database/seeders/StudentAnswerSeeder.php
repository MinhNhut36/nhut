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
            // Mỗi học sinh trả lời 20-30 câu hỏi
            $randomQuestions = $questions->random(rand(20, 30));
            
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
                    'answered_at' => Carbon::now()->subDays(rand(1, 30)),
                ]);
            }
        }
    }
}
