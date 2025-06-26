<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StudentProgres;
use App\Models\LessonPartScore;
use Carbon\Carbon;

class StudentProgressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $scores = LessonPartScore::all();

        // Tạo progress cho mỗi score dựa trên logic thực tế
        foreach ($scores as $score) {
            // Completion status dựa trên điểm số (>= 7.0 là hoàn thành)
            $completionStatus = $score->score >= 7.0 ? 1 : 0;

            StudentProgres::create([
                'score_id' => $score->score_id,
                'completion_status' => $completionStatus,
                'last_updated' => Carbon::parse($score->submit_time)->addMinutes(rand(1, 30)),
            ]);
        }
    }
}
