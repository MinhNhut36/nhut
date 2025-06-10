<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LessonPartScoresSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $lessonPartScores = [
            [
                'student_id' => 1,
                'lesson_part_id' => 1,
                'course_id' => 1,
                'attempt_no' => 1,
                'score' => 80,
                'total_questions'=> 10,
                'correct_answers' => 8,
                'submit_time' => now(),
            ],
            [
                'student_id' => 2,
                'lesson_part_id' => 1,
                'course_id' => 1,
                'attempt_no' => 1,
                'score' => 50,
                'total_questions'=> 10,
                'correct_answers' => 5,
                'submit_time' => now(),
            ],
            [
                'student_id' => 1,
                'lesson_part_id' => 2,
                'course_id' => 2,
                'attempt_no' => 1,
                'score' => 80,
                'total_questions'=> 10,
                'correct_answers' => 8,
                'submit_time' => now(),
            ],
        ];

        foreach ($lessonPartScores as $score) {
            LessonPartScore::create($score);
        }
    }
}
