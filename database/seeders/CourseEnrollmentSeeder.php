<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CourseEnrollment;
use App\Models\Student;
use App\Models\Course;
use Carbon\Carbon;

class CourseEnrollmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students = Student::all();
        $courses = Course::all();
        
        // Đăng ký học sinh vào các khóa học
        foreach ($students as $student) {
            // Mỗi học sinh có thể đăng ký 2-4 khóa học
            $randomCourses = $courses->random(rand(2, 4));

            foreach ($randomCourses as $course) {
                // Random status từ 1-3, nhưng chỉ tạo record nếu status >= 1
                $status = rand(1, 3);

                // Chỉ tạo record nếu status >= 1 (đã có đăng ký)
                if ($status >= 1) {
                    CourseEnrollment::create([
                        'student_id' => $student->student_id,
                        'assigned_course_id' => $course->course_id,
                        'registration_date' => Carbon::now()->subDays(rand(1, 30)),
                        'status' => $status, // 1: chờ xác nhận, 2: đang học, 3: hoàn thành
                    ]);
                }
                // Status 0 (chưa đăng ký) = không tạo record
            }
        }
    }
}
