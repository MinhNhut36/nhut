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
                'description' => 'Lịch học: Thứ 2-4-6 buổi sáng (7h30-9h30). Hình thức: Học trực tiếp tại lớp. Thời gian: 3 tháng.',
                'status' => 'Đang mở lớp',
                'starts_date' => now(),
            ],
            [
                'level' => 'A2',
                'year' => Carbon::now()->year,
                'course_name' => 'Khóa học trực tiếp KH02',
                'description' => 'Lịch học: Thứ 3-5-7 buổi chiều (14h00-16h00). Hình thức: Học trực tiếp tại lớp. Thời gian: 4 tháng.',
                'status' => 'Đang mở lớp',
                'starts_date' => now(),
            ],
            [
                'level' => 'A3',
                'year' => Carbon::now()->year,
                'course_name' => 'Khóa học trực tiếp KH03',
                'description' => 'Lịch học: Thứ 2-4-6 buổi tối (19h00-21h00). Hình thức: Học trực tiếp tại lớp. Thời gian: 4 tháng.',
                'status' => 'Đang mở lớp',
                'starts_date' => now(),
            ],
            [
                'level' => 'A2/6',
                'year' => Carbon::now()->year,
                'course_name' => 'Khóa học trực tiếp KH04',
                'description' => 'Lịch học: Thứ 7-CN buổi sáng (8h00-11h00). Hình thức: Học trực tiếp tại lớp. Thời gian: 6 tháng.',
                'status' => 'Đang mở lớp',
                'starts_date' => now(),
            ],
            [
                'level' => 'B1',
                'year' => Carbon::now()->year,
                'course_name' => 'Khóa học online KH05',
                'description' => 'Lịch học: Thứ 3-5-7 buổi tối (20h00-22h00). Hình thức: Học online qua Zoom. Thời gian: 5 tháng.',
                'status' => 'Đang mở lớp',
                'starts_date' => now(),
            ],
            [
                'level' => 'A1',
                'year' => Carbon::now()->year,
                'course_name' => 'Khóa học cuối tuần KH06',
                'description' => 'Lịch học: Thứ 7-CN buổi chiều (13h30-16h30). Hình thức: Học trực tiếp tại lớp. Thời gian: 4 tháng.',
                'status' => 'Đang mở lớp',
                'starts_date' => now(),
            ],
            [
                'level' => 'A2',
                'year' => Carbon::now()->year,
                'course_name' => 'Khóa học A2 KH22',
                'description' => 'Lịch học: Thứ 2-4-6 buổi chiều (15h00-17h00). Hình thức: Học trực tiếp tại lớp. Thời gian: 3 tháng.',
                'status' => 'Đã hoàn thành',
                'starts_date' => now()->subMonths(6),
            ],
        ];

        foreach ($courses as $course) {
            Course::create($course);
        }
    }
}
