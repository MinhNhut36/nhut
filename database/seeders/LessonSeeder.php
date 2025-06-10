<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LessonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void{
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
    }
}
