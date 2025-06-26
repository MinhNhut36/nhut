<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ClassPost;
use App\Models\Course;
use App\Models\Teacher;
use Carbon\Carbon;

class ClassPostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teacherAssignments = \App\Models\TeacherCourseAssignment::with(['teacher', 'course'])->get();

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
            'Exam Preparation Guidelines',
            'New Vocabulary Words',
            'Listening Exercise Available',
            'Group Project Assignment',
            'Cultural Exchange Discussion',
            'English Movie Recommendation',
            'Grammar Review Session',
            'Writing Tips and Tricks',
            'Conversation Practice',
            'Study Group Formation',
            'Progress Update'
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
            'Exam is coming up! Here\'s what you need to know and how to prepare effectively.',
            'This week we\'re learning 20 new vocabulary words. Please review them before next class.',
            'I\'ve uploaded a new listening exercise. Complete it by Wednesday for extra practice.',
            'Time for our group project! Form teams of 3-4 students and choose your topic.',
            'Let\'s talk about different cultures and traditions. Share something interesting from your country!',
            'I recommend watching "The Pursuit of Happyness" with English subtitles this weekend.',
            'We\'ll have a grammar review session tomorrow. Bring your questions!',
            'Here are some writing tips to help you improve your essays and compositions.',
            'Practice conversations with your classmates. Use the phrases we learned today.',
            'Anyone interested in forming a study group? Let me know in the comments.',
            'Great progress everyone! Keep up the excellent work in your English studies.'
        ];

        foreach ($teacherAssignments as $assignment) {
            // Mỗi teacher-course assignment có 3-6 bài viết
            $numPosts = rand(3, 6);

            for ($i = 0; $i < $numPosts; $i++) {
                $titleIndex = rand(0, count($postTitles) - 1);
                $contentIndex = rand(0, count($postContents) - 1);

                $createdAt = Carbon::now()->subDays(rand(1, 45));
                $updatedAt = $createdAt->copy()->addDays(rand(0, 3));

                ClassPost::create([
                    'course_id' => $assignment->course_id,
                    'teacher_id' => $assignment->teacher_id,
                    'title' => $postTitles[$titleIndex],
                    'content' => $postContents[$contentIndex],
                    'status' => rand(0, 1) ? 1 : 0, // 80% active, 20% inactive
                    'created_at' => $createdAt,
                    'updated_at' => $updatedAt,
                ]);
            }
        }
    }
}
