<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StudentEvaluation;
use App\Models\Student;
use App\Models\ExamResult;
use App\Models\StudentProgres;
use Carbon\Carbon;

class StudentEvaluationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students = Student::all();
        $examResults = ExamResult::all();
        
        $remarks = [
            'Excellent progress! Keep up the good work.',
            'Good improvement in speaking skills.',
            'Need more practice in grammar.',
            'Outstanding performance in all areas.',
            'Shows great potential, needs more confidence.',
            'Consistent effort and good results.',
            'Needs to focus more on listening skills.',
            'Very good participation in class activities.',
            'Homework completion is excellent.',
            'Recommended for advanced level course.'
        ];

        foreach ($students as $student) {
            // Mỗi học sinh có 1-2 đánh giá
            $numEvaluations = rand(1, 2);

            for ($i = 0; $i < $numEvaluations; $i++) {
                // Lấy exam result của học sinh này
                $studentExamResult = $examResults->where('student_id', $student->student_id)->first();

                // Lấy progress thông qua lesson_part_scores của học sinh
                $studentScore = \App\Models\LessonPartScore::where('student_id', $student->student_id)->first();
                $studentProgress = null;
                if ($studentScore) {
                    $studentProgress = StudentProgres::where('score_id', $studentScore->score_id)->first();
                }

                if ($studentExamResult && $studentProgress) {
                    StudentEvaluation::create([
                        'student_id' => $student->student_id,
                        'progress_id' => $studentProgress->progress_id,
                        'exam_result_id' => $studentExamResult->exam_result_id,
                        'evaluation_date' => Carbon::now()->subDays(rand(1, 30)),
                        'final_status' => rand(0, 1), // 0: chưa hoàn thành, 1: đã hoàn thành
                        'remark' => $remarks[array_rand($remarks)],
                    ]);
                }
            }
        }
    }
}
