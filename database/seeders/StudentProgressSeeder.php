<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StudentProgres;
use App\Models\Student;
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

        // Tạo progress cho mỗi score (50% scores sẽ có progress)
        foreach ($scores->random($scores->count() * 0.5) as $score) {
            StudentProgres::create([
                'score_id' => $score->score_id,
                'completion_status' => rand(0, 1), // 0: chưa hoàn thành, 1: đã hoàn thành
                'last_updated' => Carbon::now()->subDays(rand(1, 20)),
            ]);
        }
    }
}
