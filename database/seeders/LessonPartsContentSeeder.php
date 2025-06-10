<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LessonPartsContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

     public function run(): void{
        $lessonparts = [
        [
            'level' => '1',
            'part_type' => 'Nói',
            'content' => '...',
            'order_index' => 1,
        ],
        [
            'level' => '2',
            'part_type' => 'Nghe',
            'content' => '...',
            'order_index' => 2,
        ],
        [
            'level' => '3',
            'part_type' => 'Đọc',
            'content' => '...',
            'order_index' => 3,
        ],
        [
            'level' => '4',
            'part_type' => 'Viết',
            'content' => '...',
            'order_index' => 4,
        ],
        ];

        foreach ($lessonparts as $lessonpart) {
            Course::create($lessonpart); // Laravel sẽ tự động thêm created_at và updated_at
        }
    }
}
