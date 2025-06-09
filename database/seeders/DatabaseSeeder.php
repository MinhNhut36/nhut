<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
   

        Student::create([         
                'avatar' => 'avatar1.png',
                'fullname' => 'Nguyễn Thị Huyền Trang',
                'username' => 'trang',
                'password' => bcrypt('123456'),
                'date_of_birth' => '2002-05-10',
                'gender' => 0,
                'email' => 'vana@example.com',
                'is_status' => 1
            ]);
        
             Teacher::create([         
                'fullname' => 'Gv nhut',
                'username' => 'gvnhut',
                'password' => bcrypt('123456'),
                'date_of_birth' => '2002-05-10',
                'gender' => 1,
                'email' => 'vana@example.com',
                'is_status' => 1
            ]);
             User::create([         
                'fullname' => 'Admin',
                'username' => 'ad1',
                'password' => bcrypt('123456'), 
                'email' => 'vana@example.com',
            ]);
               $lessons = [
            [
                'level' => 'A1',
                'title' => 'Anh Văn 1',
                'description' => '...',
                'order_index' => 1,
            ],
            [
                'level' => 'A2',
                'title' => 'Anh Văn 2',
                'description' => '...',
                'order_index' => 2,
            ],
            [
                'level' => 'A3',
                'title' => 'Anh Văn 3',
                'description' => '...',
                'order_index' => 3,
            ],
            [
                'level' => 'A2/6',
                'title' => 'Anh Văn 2/6',
                'description' => '...',
                'order_index' => 4,
            ],
        ];

        foreach ($lessons as $lesson) {
            Lesson::create($lesson); // Laravel sẽ tự động thêm created_at và updated_at
        }
        $courses = [
        [
            'level' => 'A1',
            'year' => now(),
            'course_name' => 'Khóa học A1 KH01',
            'description' => 'KH01',
            'status' => 'Đang mở lớp',
        ],
        [
            'level' => 'A1',
            'year' => now(),
            'course_name' => 'Khóa học A1 KH02',
            'description' => 'KH02',
            'status' => 'Đang mở lớp',
        ],
        [
            'level' => 'A2',
            'year' => now(),
            'course_name' => 'Khóa học A2 KH70',
            'description' => 'KH70',
            'status' => 'Đang mở lớp',
        ],
        [
            'level' => 'A3',
            'year' => now(),
            'course_name' => 'Khóa học A3 KH21',
            'description' => 'KH22',
            'status' => 'Đang mở lớp',
        ],
          [
            'level' => 'A2/6',
            'year' => now(),
            'course_name' => 'Khóa học A2/6 KH22',
            'description' => 'KH22',
            'status' => 'Đã hoàn thành',
        ],
        ];

        foreach ($courses as $course) {
            Course::create($course); 
        }
    }
}
