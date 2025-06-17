<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ClassPost;
use App\Models\Course;
use App\Models\Teacher;
use App\Models\Student;
use Carbon\Carbon;

class ClassPostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $courses = Course::all();
        $teachers = Teacher::all();
        $students = Student::all();
        
        $postTitles = [
            'Welcome to English Class!',
            'Today\'s Lesson: Present Tense',
            'Homework Assignment - Week 1',
            'Grammar Practice Exercise',
            'Vocabulary Quiz Next Week',
            'Speaking Practice Session',
            'Reading Comprehension Tips',
            'Pronunciation Workshop',
            'Class Discussion: Daily Routines',
            'Exam Preparation Guidelines'
        ];
        
        $postContents = [
            'Welcome everyone! I\'m excited to start this English journey with you. Please introduce yourselves in the comments.',
            'Today we learned about present tense verbs. Remember to practice the examples we discussed in class.',
            'Your homework for this week is to complete exercises 1-5 in your workbook. Due date is Friday.',
            'Let\'s practice grammar together! I\'ve posted some exercises for you to try.',
            'Don\'t forget we have a vocabulary quiz next Tuesday. Study the words from Unit 1-3.',
            'Join us for speaking practice this Thursday at 3 PM. We\'ll work on pronunciation and fluency.',
            'Here are some tips to improve your reading comprehension skills. Practice daily for best results.',
            'Our pronunciation workshop was great! Keep practicing the sounds we learned today.',
            'Let\'s discuss our daily routines in English. Share what you do every morning!',
            'Exam is coming up! Here\'s what you need to know and how to prepare effectively.'
        ];

        foreach ($courses as $course) {
            // Mỗi khóa học có 5-8 bài viết
            for ($i = 0; $i < rand(5, 8); $i++) {
                // 70% bài viết từ giáo viên, 30% từ học sinh
                $isTeacher = rand(1, 10) <= 7;
                
                if ($isTeacher) {
                    $author = $teachers->random();
                    $authorType = 'teacher';
                    $authorId = $author->teacher_id;
                } else {
                    $author = $students->random();
                    $authorType = 'student';
                    $authorId = $author->student_id;
                }
                
                ClassPost::create([
                    'course_id' => $course->course_id,
                    'author_id' => $authorId,
                    'author_type' => $authorType,
                    'title' => $postTitles[$i % count($postTitles)],
                    'content' => $postContents[$i % count($postContents)],
                    'status' => 1,
                    'created_at' => Carbon::now()->subDays(rand(1, 30)),
                    'updated_at' => Carbon::now()->subDays(rand(0, 5)),
                ]);
            }
        }
    }
}
