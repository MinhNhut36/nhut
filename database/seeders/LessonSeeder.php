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
                'title' => 'Anh Văn 1',
                'description' => 'Dài lắm',
                'order_index' => 1,
            ],
            [
                'level' => 'A2',
                'title' => 'Anh Văn 2',
                'description' => 'Dài lắm',
                'order_index' => 2,
            ],
            [
                'level' => 'A3',
                'title' => 'Anh Văn 3',
                'description' => 'Dài lắm',
                'order_index' => 3,
            ],
            [
                'level' => 'A2/6',
                'title' => 'Anh Văn 2/6',
                'description' => 'Dài lắm',
                'order_index' => 4,
            ],
        ];

        foreach ($lessons as $lesson) {
            Lesson::create($lesson); // Laravel sẽ tự động thêm created_at và updated_at
        }
    }
}
