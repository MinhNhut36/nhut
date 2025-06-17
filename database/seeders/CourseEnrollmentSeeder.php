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
            // Mỗi học sinh đăng ký 2-3 khóa học ngẫu nhiên
            $randomCourses = $courses->random(rand(2, 3));
            
            foreach ($randomCourses as $course) {
                CourseEnrollment::create([
                    'student_id' => $student->student_id,
                    'assigned_course_id' => $course->course_id,
                    'registration_date' => Carbon::now()->subDays(rand(1, 30)),
                    'status' => rand(0, 2), // 0: chưa bắt đầu, 1: đang học, 2: đã hoàn thành
                ]);
            }
        }
    }
}
