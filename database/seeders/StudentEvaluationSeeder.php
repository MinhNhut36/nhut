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
            'Recommended for advanced level course.',
            'Strong vocabulary development.',
            'Needs improvement in pronunciation.',
            'Great participation in group activities.',
            'Consistent attendance and effort.',
            'Ready for next level challenges.'
        ];

        foreach ($examResults as $examResult) {
            // Tìm progress records của student này thông qua lesson_part_scores
            $studentProgresses = StudentProgres::whereHas('lessonPartScore', function($query) use ($examResult) {
                $query->where('student_id', $examResult->student_id);
            })->get();

            if ($studentProgresses->isNotEmpty()) {
                // Tạo evaluation cho student này
                $randomProgress = $studentProgresses->random();

                // Final status dựa trên exam result
                $finalStatus = $this->determineFinalStatus($examResult);

                StudentEvaluation::create([
                    'student_id' => $examResult->student_id,
                    'progress_id' => $randomProgress->progress_id,
                    'exam_result_id' => $examResult->exam_result_id,
                    'evaluation_date' => Carbon::parse($examResult->exam_date)->addDays(rand(1, 7)),
                    'final_status' => $finalStatus,
                    'remark' => $this->getRemarkBasedOnPerformance($examResult, $remarks),
                ]);
            }
        }
    }

    private function determineFinalStatus($examResult)
    {
        $averageScore = ($examResult->listening_score + $examResult->speaking_score +
                        $examResult->reading_score + $examResult->writing_score) / 4;

        return $averageScore >= 6.0 ? 1 : 0; // 1: passed, 0: failed
    }

    private function getRemarkBasedOnPerformance($examResult, $remarks)
    {
        $averageScore = ($examResult->listening_score + $examResult->speaking_score +
                        $examResult->reading_score + $examResult->writing_score) / 4;

        if ($averageScore >= 8.0) {
            $goodRemarks = array_slice($remarks, 0, 5); // Excellent remarks
            return $goodRemarks[array_rand($goodRemarks)];
        } elseif ($averageScore >= 6.0) {
            $averageRemarks = array_slice($remarks, 5, 5); // Average remarks
            return $averageRemarks[array_rand($averageRemarks)];
        } else {
            $improvementRemarks = array_slice($remarks, 10, 5); // Need improvement remarks
            return $improvementRemarks[array_rand($improvementRemarks)];
        }
    }
}
