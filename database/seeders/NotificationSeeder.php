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
        $enrollments = \App\Models\CourseEnrollment::with(['student', 'course'])->get();

        // Thông báo chung cho tất cả học sinh
        $generalNotifications = [
            [
                'title' => 'Welcome to English Learning Center',
                'message' => 'Welcome to our English learning platform! We\'re excited to have you join us on this learning journey.',
            ],
            [
                'title' => 'New Course Available',
                'message' => 'A new English course has been added to the platform. Check it out in the courses section!',
            ],
            [
                'title' => 'System Maintenance Notice',
                'message' => 'The system will undergo maintenance this weekend from 2 AM to 6 AM. Please plan accordingly.',
            ],
            [
                'title' => 'Holiday Schedule Update',
                'message' => 'Please note the updated class schedule for the upcoming holidays. Check your course calendar.',
            ],
            [
                'title' => 'New Learning Resources',
                'message' => 'We\'ve added new learning materials and practice exercises to help improve your English skills.',
            ],
        ];

        // Tạo thông báo chung
        foreach ($generalNotifications as $notification) {
            Notification::create([
                'admin_id' => $admin->admin_id,
                'title' => $notification['title'],
                'message' => $notification['message'],
                'notification_date' => Carbon::now()->subDays(rand(1, 30)),
            ]);
        }

        // Tạo thông báo dựa trên enrollment status
        foreach ($enrollments as $enrollment) {
            $this->createEnrollmentNotifications($admin, $enrollment);
        }

        // Tạo thông báo ngẫu nhiên cho học sinh
        foreach ($students as $student) {
            $this->createRandomNotifications($admin, $student);
        }
    }

    private function createEnrollmentNotifications($admin, $enrollment)
    {
        $statusValue = $enrollment->status;
        $courseName = $enrollment->course->course_name;

        switch ($statusValue) {
            case 1: // Pending
                Notification::create([
                    'admin_id' => $admin->admin_id,
                    'title' => 'Enrollment Pending',
                    'message' => "Your enrollment in {$courseName} is pending approval. We'll notify you once it's confirmed.",
                    'notification_date' => Carbon::parse($enrollment->registration_date)->addHours(1),
                ]);
                break;

            case 2: // Studying
                Notification::create([
                    'admin_id' => $admin->admin_id,
                    'title' => 'Course Started',
                    'message' => "Welcome to {$courseName}! Your course has started. Good luck with your studies!",
                    'notification_date' => Carbon::parse($enrollment->registration_date)->addDays(1),
                ]);
                break;

            case 3: // Passed
                Notification::create([
                    'admin_id' => $admin->admin_id,
                    'title' => 'Congratulations!',
                    'message' => "Congratulations! You have successfully completed {$courseName}. Well done!",
                    'notification_date' => Carbon::parse($enrollment->registration_date)->addDays(rand(30, 60)),
                ]);
                break;

            case 4: // Failed
                Notification::create([
                    'admin_id' => $admin->admin_id,
                    'title' => 'Course Completion',
                    'message' => "Your {$courseName} course has ended. Please contact your teacher for feedback and next steps.",
                    'notification_date' => Carbon::parse($enrollment->registration_date)->addDays(rand(30, 60)),
                ]);
                break;
        }
    }

    private function createRandomNotifications($admin, $student)
    {
        $randomNotifications = [
            [
                'title' => 'Assignment Reminder',
                'message' => 'Don\'t forget to submit your homework assignment before the deadline.',
            ],
            [
                'title' => 'Quiz Available',
                'message' => 'A new quiz is available for your course. Complete it to test your knowledge!',
            ],
            [
                'title' => 'Progress Update',
                'message' => 'Great progress! Keep up the excellent work in your English studies.',
            ],
            [
                'title' => 'Study Tips',
                'message' => 'Remember to practice speaking English daily for better fluency improvement.',
            ],
            [
                'title' => 'Class Schedule',
                'message' => 'Please check your class schedule for any updates or changes.',
            ],
        ];

        // Mỗi học sinh có 1-3 thông báo ngẫu nhiên
        $numNotifications = rand(1, 3);
        $selectedNotifications = array_rand($randomNotifications, $numNotifications);

        if (!is_array($selectedNotifications)) {
            $selectedNotifications = [$selectedNotifications];
        }

        foreach ($selectedNotifications as $index) {
            $notification = $randomNotifications[$index];

            Notification::create([
                'admin_id' => $admin->admin_id,
                'title' => $notification['title'],
                'message' => $notification['message'],
                'notification_date' => Carbon::now()->subDays(rand(1, 20)),
            ]);
        }
    }
}
