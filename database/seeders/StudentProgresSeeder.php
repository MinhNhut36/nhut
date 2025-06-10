<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StudentProgresSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $studentProgresses = [
            [
                'student_id' => 1,
                'score_id' => 1,
                'completion_status' => 1,
                'last_updated' => now(),
            ],
            [
                'student_id' => 2,
                'score_id' => 1,
                'completion_status' => 1,
                'last_updated' => now(),
            ],
            [
                'student_id' => 2,
                'score_id' => 2,
                'completion_status' => 1,
                'last_updated' => now(),
            ],
        ];

        foreach ($studentProgresses as $progress) {
            StudentProgress::create($progress);
        }
    }
}
