<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Course;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $courses = [
            [
                'level' => 'A1',
                'year' => Carbon::now()->year,
                'course_name' => 'Khóa học trực tiếp KH01',
                'description' => 'KH01',
                'status' => 'Đang mở lớp',
                'starts_date' => now(),
            ],
            [
                'level' => 'A2',
                'year' => Carbon::now()->year,
                'course_name' => 'Khóa học trực tiếp KH01',
                'description' => 'KH01',
                'status' => 'Đang mở lớp',
                'starts_date' => now(),
            ],
            [
                'level' => 'A3',
                'year' => Carbon::now()->year,
                'course_name' => 'Khóa học trực tiếp KH01',
                'description' => 'KH01',
                'status' => 'Đang mở lớp',
                'starts_date' => now(),
            ],
            [
                'level' => 'A2/6',
                'year' => Carbon::now()->year,
                'course_name' => 'Khóa học trực tiếp KH01',
                'description' => 'KH01',
                'status' => 'Đang mở lớp',
                'starts_date' => now(),
            ],
            [
                'level' => 'A2/6',
                'year' => Carbon::now()->year,
                'course_name' => 'Khóa học A2/6 KH22',
                'description' => 'KH22',
                'status' => 'Đã hoàn thành',
                'starts_date' => now(),
            ],
        ];

        foreach ($courses as $course) {
            Course::create($course);
        }
    }
}
