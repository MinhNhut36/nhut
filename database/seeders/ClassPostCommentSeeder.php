<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ClassPostComment;
use App\Models\ClassPost;
use App\Models\Teacher;
use App\Models\Student;
use Carbon\Carbon;

class ClassPostCommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $posts = ClassPost::all();
        $teachers = Teacher::all();
        $students = Student::all();

        $studentComments = [
            'Thank you for the explanation!',
            'This is very helpful.',
            'I have a question about this topic.',
            'Great lesson today!',
            'Could you please clarify this point?',
            'I understand now, thanks!',
            'When is the deadline for this assignment?',
            'This exercise is challenging but fun.',
            'I need more practice with this.',
            'Can we have more examples?',
            'I\'m looking forward to the next lesson.',
            'This topic is interesting.',
            'Thanks for your patience with us.',
            'I\'m confused about this part.',
            'Could you explain this again?',
            'This helps me a lot!',
            'I will practice more at home.',
            'When is our next test?',
            'Can I ask a question in class?',
            'I love learning English!'
        ];

        $teacherComments = [
            'Great question! Let me explain...',
            'Excellent work everyone!',
            'Remember to practice daily.',
            'I\'m proud of your progress.',
            'Don\'t hesitate to ask questions.',
            'Keep up the good work!',
            'This is exactly right!',
            'Let\'s review this together.',
            'You\'re making great progress.',
            'Practice makes perfect!',
            'I\'ll provide more examples next class.',
            'Well done on the assignment!',
            'Your participation is excellent.',
            'Let\'s work on this together.',
            'I\'m here to help you succeed.'
        ];

        foreach ($posts as $post) {
            // Chỉ tạo comments cho posts của courses có enrollments
            $courseEnrollments = \App\Models\CourseEnrollment::where('assigned_course_id', $post->course_id)->get();

            if ($courseEnrollments->isEmpty()) {
                continue;
            }

            // Mỗi bài viết có 1-5 bình luận
            $numComments = rand(1, 5);

            for ($i = 0; $i < $numComments; $i++) {
                // 70% bình luận từ học sinh enrolled, 30% từ giảng viên
                $isStudent = rand(1, 10) <= 7;

                $studentId = null;
                $teacherId = null;

                if ($isStudent && $courseEnrollments->isNotEmpty()) {
                    // Chọn student từ enrollments của course này
                    $enrollment = $courseEnrollments->random();
                    $studentId = $enrollment->student_id;
                    $commentContent = $studentComments[array_rand($studentComments)];
                } else {
                    // Chọn teacher (ưu tiên teacher của post này)
                    if (rand(1, 10) <= 7) {
                        $teacherId = $post->teacher_id; // Teacher của post
                    } else {
                        $teacherId = $teachers->random()->teacher_id; // Teacher khác
                    }
                    $commentContent = $teacherComments[array_rand($teacherComments)];
                }

                $createdAt = Carbon::parse($post->created_at)->addHours(rand(1, 72));

                ClassPostComment::create([
                    'post_id' => $post->post_id,
                    'student_id' => $studentId,
                    'teacher_id' => $teacherId,
                    'content' => $commentContent,
                    'status' => rand(0, 1) ? 1 : 0, // 90% active, 10% inactive
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt->copy()->addMinutes(rand(0, 30)),
                ]);
            }
        }
    }
}
