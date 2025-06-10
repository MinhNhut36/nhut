<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LessonPartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $lessonParts = [
            [
                'level' => '1',
                'part_type' => 'nghe',
                'content' => 'audio',
                'order_index' => 1,
            ],
            [
                'level' => '1',
                'part_type' => 'viết',
                'content' => 'văn bản',
                'order_index' => 2,
            ],
            [
                'level' => '2',
                'part_type' => 'nghe',
                'content' => 'audio',
                'order_index' => 3,
            ],
        ];

        foreach ($lessonParts as $part) {
            LessonPart::create($part);
        }
    }
}
