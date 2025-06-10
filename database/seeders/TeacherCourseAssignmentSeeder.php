<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TeacherCourseAssignmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TeacherCourseAssignment::create(
        [
            [
                'teacher_id' => 1,
                'course_id' => 1,
                'role' => 'chính',
                'assigned_at' => now(),
            ],
            [
                'teacher_id' => 2,
                'course_id' => 2,
                'role' => 'chính',
                'assigned_at' => now(),
            ],
            [
                'teacher_id' => 1,
                'course_id' => 2,
                'role' => 'phụ',
                'assigned_at' => now(),
            ],
        ]
        );
    }
}
