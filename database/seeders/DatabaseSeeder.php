<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
   
          $this->call([
            AdminSeeder::class,
            StudentSeeder::class,
            TeacherSeeder::class,
            LessonSeeder::class,
            CourseSeeder::class,
            LessonPartSeeder::class,

            ComprehensiveQuestionsSeeder::class, // Seeder mới với 10 câu hỏi mỗi lesson part, đã tối ưu
            CourseEnrollmentSeeder::class,
            TeacherCourseAssignmentSeeder::class,
            RealisticStudentAnswerSeeder::class, // Tạo student answers realistic
            ExamResultSeeder::class, // Updated để tạo exam results realistic
            LessonPartScoreSeeder::class, // Updated để tạo scores dựa trên enrollments
            StudentProgressSeeder::class, // Updated để tạo progress dựa trên scores
            StudentEvaluationSeeder::class, // Updated để tạo evaluations dựa trên exam results
            ClassPostSeeder::class, // Updated để tạo posts dựa trên teacher assignments
            ClassPostCommentSeeder::class, // Updated để tạo comments realistic
            NotificationSeeder::class, // Updated để tạo notifications dựa trên enrollments
        ]);
                      
    }
}
