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
            4 => 'TA 2/6',
        ];

        $parts = [
            ['title' => 'Basic Vocabulary', 'desc' => 'Từ vựng cơ bản cho trình độ %s - Học các từ vựng thiết yếu hàng ngày'],
            ['title' => 'Essential Grammar', 'desc' => 'Ngữ pháp nền tảng cho trình độ %s - Các cấu trúc câu cơ bản'],
            ['title' => 'Listening Skills', 'desc' => 'Luyện nghe tiếng Anh trình độ %s - Phát triển kỹ năng nghe hiểu'],
            ['title' => 'Speaking Practice', 'desc' => 'Luyện nói tiếng Anh trình độ %s - Thực hành giao tiếp hàng ngày'],
            ['title' => 'Reading Comprehension', 'desc' => 'Luyện đọc hiểu tiếng Anh trình độ %s - Hiểu văn bản và bài đọc'],
            ['title' => 'Writing Skills', 'desc' => 'Luyện viết tiếng Anh trình độ %s - Viết câu và đoạn văn cơ bản'],
            ['title' => 'Pronunciation', 'desc' => 'Luyện phát âm tiếng Anh trình độ %s - Phát âm chuẩn và rõ ràng'],
            ['title' => 'Communication', 'desc' => 'Giao tiếp tiếng Anh trình độ %s - Tình huống giao tiếp thực tế'],
            ['title' => 'Review & Practice', 'desc' => 'Ôn tập và thực hành trình độ %s - Củng cố kiến thức đã học'],
            ['title' => 'Assessment Test', 'desc' => 'Bài kiểm tra đánh giá trình độ %s - Đánh giá năng lực học viên'],
            ['title' => 'Advanced Topics', 'desc' => 'Chủ đề nâng cao trình độ %s - Mở rộng kiến thức chuyên sâu'],
            ['title' => 'Cultural Context', 'desc' => 'Bối cảnh văn hóa trình độ %s - Hiểu văn hóa và xã hội'],
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
