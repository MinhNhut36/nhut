<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void{
        $courses = [
        [
            'level' => '1',
            'year' => now(),
            'description' => 'KH20',
            'status' => 1,
        ],
        [
            'level' => '3',
            'year' => now(),
            'description' => 'KH80',
            'status' => 1,
        ],
        [
            'level' => '4',
            'year' => now(),
            'description' => 'KH70',
            'status' => 1,
        ],
        [
            'level' => '2',
            'year' => now(),
            'description' => 'KH22',
            'status' => 0,
        ],
        ];

        foreach ($courses as $course) {
            Course::create($course); // Laravel sẽ tự động thêm created_at và updated_at
        }
    }
}
