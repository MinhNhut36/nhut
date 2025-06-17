<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Question;
use App\Models\LessonPartContent;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Lấy tất cả lesson part contents
        $contents = LessonPartContent::all();
        
        $questionTypes = ['single_choice', 'multiple_choice', 'true_false', 'short_answer'];
        
        $questionTemplates = [
            'single_choice' => [
                'What is the correct translation of "Hello"?',
                'Choose the correct verb form:',
                'Which word means "goodbye"?',
                'Select the correct pronunciation:',
                'What is the past tense of "go"?',
                'Choose the correct article:',
                'Which sentence is grammatically correct?',
                'What does this word mean?',
                'Select the correct preposition:',
                'Choose the right answer:'
            ],
            'multiple_choice' => [
                'Which of the following are correct? (Select all)',
                'Choose all the correct translations:',
                'Select all grammatically correct sentences:',
                'Which words are synonyms?',
                'Choose all correct verb forms:',
                'Select all appropriate responses:',
                'Which phrases are polite?',
                'Choose all correct spellings:',
                'Select all past tense verbs:',
                'Which are question words?'
            ],
            'true_false' => [
                'True or False: "I am" is present tense.',
                'True or False: "Cat" is a verb.',
                'True or False: "Hello" is a greeting.',
                'True or False: "Red" is a color.',
                'True or False: "Run" is past tense.',
                'True or False: "The" is an article.',
                'True or False: "Beautiful" is an adjective.',
                'True or False: "Quickly" is an adverb.',
                'True or False: "And" is a conjunction.',
                'True or False: "Book" can be a noun.'
            ],
            'short_answer' => [
                'Write the past tense of "eat":',
                'Translate "Thank you" to Vietnamese:',
                'Complete the sentence: "I ___ a student."',
                'What is the plural of "child"?',
                'Write a sentence using "because":',
                'What is the opposite of "hot"?',
                'Complete: "She ___ to school every day."',
                'Write the comparative form of "good":',
                'What question word asks about time?',
                'Complete: "There ___ many books on the table."'
            ]
        ];

        foreach ($contents as $content) {
            // Tạo 10 câu hỏi cho mỗi content
            for ($i = 1; $i <= 10; $i++) {
                $questionType = $questionTypes[array_rand($questionTypes)];
                $templates = $questionTemplates[$questionType];
                $questionText = $templates[($i - 1) % count($templates)];
                
                Question::create([
                    'contents_id' => $content->contents_id,
                    'question_type' => $questionType,
                    'question_text' => $questionText,
                    'media_url' => $i <= 3 ? 'https://example.com/audio/question_' . $content->contents_id . '_' . $i . '.mp3' : null,
                    'order_index' => $i,
                ]);
            }
        }
    }
}
