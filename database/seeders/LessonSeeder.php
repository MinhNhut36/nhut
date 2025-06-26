<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Lesson;

class LessonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $lessons = [
            [
                'level' => 'A1',
                'title' => 'English Beginner A1',
                'description' => 'Khóa học tiếng Anh cơ bản dành cho người mới bắt đầu. Học từ vựng, ngữ pháp và giao tiếp cơ bản.',
                'order_index' => 1,
            ],
            [
                'level' => 'A2',
                'title' => 'English Elementary A2',
                'description' => 'Khóa học tiếng Anh sơ cấp, phát triển kỹ năng giao tiếp và hiểu biết ngữ pháp nâng cao.',
                'order_index' => 2,
            ],
            [
                'level' => 'A3',
                'title' => 'English Pre-Intermediate A3',
                'description' => 'Khóa học tiếng Anh trước trung cấp, tập trung vào kỹ năng đọc hiểu và viết.',
                'order_index' => 3,
            ],
            [
                'level' => 'TA 2/6',
                'title' => 'English Intensive TA 2/6',
                'description' => 'Khóa học tiếng Anh chuyên sâu, phát triển toàn diện 4 kỹ năng nghe-nói-đọc-viết.',
                'order_index' => 4,
            ],
        ];

        foreach ($lessons as $lesson) {
            Lesson::create($lesson); // Laravel sẽ tự động thêm created_at và updated_at
        }
    }
}
