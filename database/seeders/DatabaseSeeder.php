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
                'level' => '1',
                'title' => 'Anh Văn 1',
                'description' => '...',
                'order_index' => 1,
            ],
            [
                'level' => '2',
                'title' => 'Anh Văn 2',
                'description' => '...',
                'order_index' => 2,
            ],
            [
                'level' => '3',
                'title' => 'Anh Văn 3',
                'description' => '...',
                'order_index' => 2,
            ],
            [
                'level' => '4',
                'title' => 'Anh Văn 2/6',
                'description' => '...',
                'order_index' => 2,
            ],
        ];

        foreach ($lessons as $lesson) {
            Lesson::create($lesson); // Laravel sẽ tự động thêm created_at và updated_at
        }
        $courses = [
        [
            'level' => '1',
            'year' => now(),
            'description' => 'KH20',
            'status' => 1,
        ],
        [
            'level' => '3',
            'year' => now(),
            'description' => 'KH80',
            'status' => 1,
        ],
        [
            'level' => '4',
            'year' => now(),
            'description' => 'KH70',
            'status' => 1,
        ],
        [
            'level' => '2',
            'year' => now(),
            'description' => 'KH22',
            'status' => 0,
        ],
        ];

        foreach ($courses as $course) {
            Course::create($course); 
        }
    }
}
