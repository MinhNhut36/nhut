<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('questions')->insert(
        [
            [
                'contents_id' => 1,
                'question_text' => 'Câu hỏi 1',
                'question_type' => 'Trắc nghiệm',
                'media_url' => 'https://example.com/media1.jpg',
                'order_index' => 1,
            ],
            [
                'contents_id' => 2,
                'question_text' => 'Câu hỏi 2',
                'question_type' => 'Trắc nghiệm',
                'media_url' => 'https://example.com/media1.jpg',
                'order_index' => 1,
            ],
        ]
        );
    }
}
