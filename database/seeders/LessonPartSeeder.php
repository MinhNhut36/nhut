<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\LessonPart;
class LessonPartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $lessons = [
            1 => 'A1',
            2 => 'A2',
            3 => 'A3',
            4 => 'A2/6',
        ];

        $parts = [
            ['title' => 'Vocabulary', 'desc' => 'Từ vựng cơ bản cho trình độ %s'],
            ['title' => 'Grammar', 'desc' => 'Ngữ pháp nền tảng cho trình độ %s'],
            ['title' => 'Listening', 'desc' => 'Luyện nghe tiếng Anh trình độ %s'],
            ['title' => 'Grammar', 'desc' => 'Luyện ngữ pháp nâng cao tiếng Anh trình độ %s'],
        ];

        foreach ($lessons as $lesson_id => $level) {
            foreach ($parts as $index => $part) {
                LessonPart::create([
                    'level' => $level,
                    'part_type' => $part['title'],
                    'content' => sprintf($part['desc'], $level),
                    'order_index' => $index + 1,
                ]);
            }
        }
    }
}
