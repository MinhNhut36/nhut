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
        
        $comments = [
            'Thank you for the explanation!',
            'This is very helpful.',
            'I have a question about this topic.',
            'Great lesson today!',
            'Could you please clarify this point?',
            'I understand now, thanks!',
            'When is the deadline for this assignment?',
            'This exercise is challenging but fun.',
            'I need more practice with this.',
            'Excellent teaching method!',
            'Can we have more examples?',
            'I\'m looking forward to the next lesson.',
            'This topic is interesting.',
            'I agree with this approach.',
            'Thanks for your patience with us.'
        ];

        foreach ($posts as $post) {
            // Mỗi bài viết có 2-6 bình luận
            $numComments = rand(2, 6);
            
            for ($i = 0; $i < $numComments; $i++) {
                // 60% bình luận từ học sinh, 40% từ giáo viên
                $isStudent = rand(1, 10) <= 6;
                
                if ($isStudent) {
                    $author = $students->random();
                    $authorType = 'student';
                    $authorId = $author->student_id;
                } else {
                    $author = $teachers->random();
                    $authorType = 'teacher';
                    $authorId = $author->teacher_id;
                }
                
                ClassPostComment::create([
                    'post_id' => $post->post_id,
                    'author_id' => $authorId,
                    'author_type' => $authorType,
                    'content' => $comments[array_rand($comments)],
                    'status' => 1,
                    'created_at' => Carbon::parse($post->created_at)->addHours(rand(1, 48)),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }
    }
}
