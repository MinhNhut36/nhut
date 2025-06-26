<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;
use Carbon\Carbon;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $courses = [
            // A1 Level Courses (6 courses)
            [
                'level' => 'A1',
                'year' => Carbon::now()->year,
                'course_name' => 'A1 Cơ Bản Tối 2-4-6',
                'description' => 'Lịch học: Thứ 2-4-6 buổi tối (19h00-21h00). Hình thức: Học trực tiếp tại lớp. Thời gian: 8 tuần.',
                'status' => 'Đang mở lớp',
                'starts_date' => now(),
            ],
            [
                'level' => 'A1',
                'year' => Carbon::now()->year,
                'course_name' => 'A1 Cơ Bản Tối 3-5-7',
                'description' => 'Lịch học: Thứ 3-5-7 buổi tối (19h00-21h00). Hình thức: Học trực tiếp tại lớp. Thời gian: 8 tuần.',
                'status' => 'Đang mở lớp',
                'starts_date' => now()->addDays(7),
            ],
            [
                'level' => 'A1',
                'year' => Carbon::now()->year,
                'course_name' => 'A1 Cơ Bản Sáng 2-4-6',
                'description' => 'Lịch học: Thứ 2-4-6 buổi sáng (7h30-9h30). Hình thức: Học trực tiếp tại lớp. Thời gian: 8 tuần.',
                'status' => 'Đang mở lớp',
                'starts_date' => now()->addDays(14),
            ],
            [
                'level' => 'A1',
                'year' => Carbon::now()->year,
                'course_name' => 'A1 Cơ Bản Chiều 3-5-7',
                'description' => 'Lịch học: Thứ 3-5-7 buổi chiều (14h00-16h00). Hình thức: Học trực tiếp tại lớp. Thời gian: 8 tuần.',
                'status' => 'Đang mở lớp',
                'starts_date' => now()->addDays(21),
            ],
            [
                'level' => 'A1',
                'year' => Carbon::now()->year,
                'course_name' => 'A1 Cuối Tuần Sáng T7-CN',
                'description' => 'Lịch học: Thứ 7-CN buổi sáng (8h00-11h00). Hình thức: Học trực tiếp tại lớp. Thời gian: 9 tuần.',
                'status' => 'Đang mở lớp',
                'starts_date' => now()->addDays(28),
            ],
            [
                'level' => 'A1',
                'year' => Carbon::now()->year,
                'course_name' => 'A1 Cuối Tuần Chiều T7-CN',
                'description' => 'Lịch học: Thứ 7-CN buổi chiều (13h30-16h30). Hình thức: Học trực tiếp tại lớp. Thời gian: 9 tuần.',
                'status' => 'Đã hoàn thành',
                'starts_date' => now()->subMonths(3),
            ],

            // A2 Level Courses (6 courses)
            [
                'level' => 'A2',
                'year' => Carbon::now()->year,
                'course_name' => 'A2 Trung Cấp Tối 2-4-6',
                'description' => 'Lịch học: Thứ 2-4-6 buổi tối (19h00-21h00). Hình thức: Học trực tiếp tại lớp. Thời gian: 8 tuần.',
                'status' => 'Đang mở lớp',
                'starts_date' => now(),
            ],
            [
                'level' => 'A2',
                'year' => Carbon::now()->year,
                'course_name' => 'A2 Trung Cấp Tối 3-5-7',
                'description' => 'Lịch học: Thứ 3-5-7 buổi tối (19h00-21h00). Hình thức: Học trực tiếp tại lớp. Thời gian: 8 tuần.',
                'status' => 'Đang mở lớp',
                'starts_date' => now()->addDays(7),
            ],
            [
                'level' => 'A2',
                'year' => Carbon::now()->year,
                'course_name' => 'A2 Trung Cấp Sáng 2-4-6',
                'description' => 'Lịch học: Thứ 2-4-6 buổi sáng (7h30-9h30). Hình thức: Học trực tiếp tại lớp. Thời gian: 8 tuần.',
                'status' => 'Đang mở lớp',
                'starts_date' => now()->addDays(14),
            ],
            [
                'level' => 'A2',
                'year' => Carbon::now()->year,
                'course_name' => 'A2 Trung Cấp Chiều 3-5-7',
                'description' => 'Lịch học: Thứ 3-5-7 buổi chiều (14h00-16h00). Hình thức: Học trực tiếp tại lớp. Thời gian: 8 tuần.',
                'status' => 'Đang mở lớp',
                'starts_date' => now()->addDays(21),
            ],
            [
                'level' => 'A2',
                'year' => Carbon::now()->year,
                'course_name' => 'A2 Cuối Tuần Sáng T7-CN',
                'description' => 'Lịch học: Thứ 7-CN buổi sáng (8h00-11h00). Hình thức: Học trực tiếp tại lớp. Thời gian: 9 tuần.',
                'status' => 'Đang mở lớp',
                'starts_date' => now()->addDays(28),
            ],
            [
                'level' => 'A2',
                'year' => Carbon::now()->year,
                'course_name' => 'A2 Cuối Tuần Chiều T7-CN',
                'description' => 'Lịch học: Thứ 7-CN buổi chiều (13h30-16h30). Hình thức: Học trực tiếp tại lớp. Thời gian: 9 tuần.',
                'status' => 'Đã hoàn thành',
                'starts_date' => now()->subMonths(3),
            ],

            // A3 Level Courses (6 courses)
            [
                'level' => 'A3',
                'year' => Carbon::now()->year,
                'course_name' => 'A3 Nâng Cao Sáng 2-4-6',
                'description' => 'Lịch học: Thứ 2-4-6 buổi sáng (7h30-9h30). Hình thức: Học trực tiếp tại lớp. Thời gian: 8 tuần.',
                'status' => 'Đang mở lớp',
                'starts_date' => now(),
            ],
            [
                'level' => 'A3',
                'year' => Carbon::now()->year,
                'course_name' => 'A3 Nâng Cao Chiều 2-4-6',
                'description' => 'Lịch học: Thứ 2-4-6 buổi chiều (14h00-16h00). Hình thức: Học trực tiếp tại lớp. Thời gian: 8 tuần.',
                'status' => 'Đang mở lớp',
                'starts_date' => now()->addDays(7),
            ],
            [
                'level' => 'A3',
                'year' => Carbon::now()->year,
                'course_name' => 'A3 Nâng Cao Tối 2-4-6',
                'description' => 'Lịch học: Thứ 2-4-6 buổi tối (19h00-21h00). Hình thức: Học trực tiếp tại lớp. Thời gian: 8 tuần.',
                'status' => 'Đang mở lớp',
                'starts_date' => now()->addDays(14),
            ],
            [
                'level' => 'A3',
                'year' => Carbon::now()->year,
                'course_name' => 'A3 Nâng Cao Sáng 3-5-7',
                'description' => 'Lịch học: Thứ 3-5-7 buổi sáng (7h30-9h30). Hình thức: Học trực tiếp tại lớp. Thời gian: 8 tuần.',
                'status' => 'Đang mở lớp',
                'starts_date' => now()->addDays(21),
            ],
            [
                'level' => 'A3',
                'year' => Carbon::now()->year,
                'course_name' => 'A3 Nâng Cao Chiều 3-5-7',
                'description' => 'Lịch học: Thứ 3-5-7 buổi chiều (14h00-16h00). Hình thức: Học trực tiếp tại lớp. Thời gian: 8 tuần.',
                'status' => 'Đang mở lớp',
                'starts_date' => now()->addDays(28),
            ],
            [
                'level' => 'A3',
                'year' => Carbon::now()->year,
                'course_name' => 'A3 Cuối Tuần Sáng T7-CN',
                'description' => 'Lịch học: Thứ 7-CN buổi sáng (8h00-11h00). Hình thức: Học trực tiếp tại lớp. Thời gian: 9 tuần.',
                'status' => 'Đã hoàn thành',
                'starts_date' => now()->subMonths(3),
            ],

            // TA 2/6 Level Courses (6 courses)
            [
                'level' => 'TA 2/6',
                'year' => Carbon::now()->year,
                'course_name' => 'TA 2/6 Chuyên Sâu Sáng 2-4-6',
                'description' => 'Lịch học: Thứ 2-4-6 buổi sáng (7h30-9h30). Hình thức: Học trực tiếp tại lớp. Thời gian: 8 tuần.',
                'status' => 'Đang mở lớp',
                'starts_date' => now(),
            ],
            [
                'level' => 'TA 2/6',
                'year' => Carbon::now()->year,
                'course_name' => 'TA 2/6 Chuyên Sâu Chiều 2-4-6',
                'description' => 'Lịch học: Thứ 2-4-6 buổi chiều (14h00-16h00). Hình thức: Học trực tiếp tại lớp. Thời gian: 8 tuần.',
                'status' => 'Đang mở lớp',
                'starts_date' => now()->addDays(7),
            ],
            [
                'level' => 'TA 2/6',
                'year' => Carbon::now()->year,
                'course_name' => 'TA 2/6 Chuyên Sâu Tối 2-4-6',
                'description' => 'Lịch học: Thứ 2-4-6 buổi tối (19h00-21h00). Hình thức: Học trực tiếp tại lớp. Thời gian: 8 tuần.',
                'status' => 'Đang mở lớp',
                'starts_date' => now()->addDays(14),
            ],
            [
                'level' => 'TA 2/6',
                'year' => Carbon::now()->year,
                'course_name' => 'TA 2/6 Chuyên Sâu Sáng 3-5-7',
                'description' => 'Lịch học: Thứ 3-5-7 buổi sáng (7h30-9h30). Hình thức: Học trực tiếp tại lớp. Thời gian: 8 tuần.',
                'status' => 'Đang mở lớp',
                'starts_date' => now()->addDays(21),
            ],
            [
                'level' => 'TA 2/6',
                'year' => Carbon::now()->year,
                'course_name' => 'TA 2/6 Chuyên Sâu Chiều 3-5-7',
                'description' => 'Lịch học: Thứ 3-5-7 buổi chiều (14h00-16h00). Hình thức: Học trực tiếp tại lớp. Thời gian: 8 tuần.',
                'status' => 'Đang mở lớp',
                'starts_date' => now()->addDays(28),
            ],
            [
                'level' => 'TA 2/6',
                'year' => Carbon::now()->year,
                'course_name' => 'TA 2/6 Cuối Tuần Sáng T7-CN',
                'description' => 'Lịch học: Thứ 7-CN buổi sáng (8h00-11h00). Hình thức: Học trực tiếp tại lớp. Thời gian: 9 tuần.',
                'status' => 'Đã hoàn thành',
                'starts_date' => now()->subMonths(3),
            ],
        ];

        foreach ($courses as $course) {
            Course::create($course);
        }
    }
}
