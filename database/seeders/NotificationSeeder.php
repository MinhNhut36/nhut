<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Notification;
use App\Models\User;
use App\Models\Student;
use Carbon\Carbon;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::first();
        $students = Student::all();
        
        $notifications = [
            [
                'title' => 'Welcome to English Learning Center',
                'message' => 'Welcome to our English learning platform! We\'re excited to have you join us on this learning journey.',
                'target' => 0, // Tất cả học sinh
            ],
            [
                'title' => 'New Course Available',
                'message' => 'A new English course has been added to the platform. Check it out in the courses section!',
                'target' => 0, // Tất cả học sinh
            ],
            [
                'title' => 'System Maintenance Notice',
                'message' => 'The system will undergo maintenance this weekend from 2 AM to 6 AM. Please plan accordingly.',
                'target' => 0, // Tất cả học sinh
            ],
            [
                'title' => 'Assignment Reminder',
                'message' => 'Don\'t forget to submit your homework assignment before the deadline.',
                'target' => 0, // Tất cả học sinh
            ],
            [
                'title' => 'Exam Schedule Released',
                'message' => 'The exam schedule for this semester has been released. Please check your course page for details.',
                'target' => 0, // Tất cả học sinh
            ],
        ];

        // Tạo thông báo chung
        foreach ($notifications as $notification) {
            Notification::create([
                'admin' => $admin->admin_id,
                'target' => $notification['target'],
                'title' => $notification['title'],
                'message' => $notification['message'],
                'notification_date' => Carbon::now()->subDays(rand(1, 10)),
                'status' => rand(0, 1), // 0: chưa gửi, 1: đã gửi
            ]);
        }

        // Tạo thông báo cá nhân cho từng học sinh
        foreach ($students as $student) {
            $personalNotifications = [
                [
                    'title' => 'Course Enrollment Confirmation',
                    'message' => 'Your enrollment in the English course has been confirmed. Welcome aboard!',
                ],
                [
                    'title' => 'Progress Update',
                    'message' => 'Great job! You\'ve completed 50% of your current course. Keep up the good work!',
                ],
                [
                    'title' => 'Quiz Results Available',
                    'message' => 'Your quiz results are now available. Check your progress in the dashboard.',
                ],
            ];

            foreach ($personalNotifications as $notification) {
                Notification::create([
                    'admin' => $admin->admin_id,
                    'target' => $student->student_id,
                    'title' => $notification['title'],
                    'message' => $notification['message'],
                    'notification_date' => Carbon::now()->subDays(rand(1, 15)),
                    'status' => rand(0, 1),
                ]);
            }
        }
    }
}
