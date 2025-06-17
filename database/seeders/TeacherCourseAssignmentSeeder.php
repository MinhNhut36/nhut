<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TeacherCourseAssignment;
use App\Models\Teacher;
use App\Models\Course;
use Carbon\Carbon;

class TeacherCourseAssignmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teachers = Teacher::all();
        $courses = Course::all();
        
        $roles = ['Main Teacher', 'Assistant Teacher', 'Substitute Teacher'];
        
        foreach ($courses as $course) {
            // Mỗi khóa học có 1-2 giáo viên
            $randomTeachers = $teachers->random(rand(1, 2));
            
            foreach ($randomTeachers as $index => $teacher) {
                TeacherCourseAssignment::create([
                    'teacher_id' => $teacher->teacher_id,
                    'course_id' => $course->course_id,
                    'role' => $roles[$index % count($roles)],
                    'assigned_at' => Carbon::now()->subDays(rand(1, 60)),
                ]);
            }
        }
    }
}
