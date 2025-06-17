<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Answer;
use App\Models\Question;

class AnswerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $questions = Question::all();
        
        $answerTemplates = [
            'single_choice' => [
                ['Xin chào', 'Tạm biệt', 'Cảm ơn', 'Xin lỗi'],
                ['am', 'is', 'are', 'be'],
                ['Goodbye', 'Hello', 'Please', 'Sorry'],
                ['/həˈloʊ/', '/ɡʊdˈbaɪ/', '/θæŋk/', '/sɔːri/'],
                ['went', 'goes', 'going', 'gone']
            ],
            'multiple_choice' => [
                ['Hello', 'Hi', 'Hey', 'Goodbye'],
                ['Good morning', 'Good afternoon', 'Good evening', 'Good night'],
                ['I am happy', 'I are happy', 'I is happy', 'I be happy'],
                ['big', 'large', 'huge', 'small'],
                ['run', 'ran', 'running', 'runs']
            ],
            'true_false' => [
                ['True', 'False'],
                ['True', 'False'],
                ['True', 'False'],
                ['True', 'False'],
                ['True', 'False']
            ],
            'short_answer' => [
                ['ate'],
                ['Cảm ơn'],
                ['am'],
                ['children'],
                ['I study hard because I want to pass the exam.']
            ]
        ];

        foreach ($questions as $question) {
            $questionType = $question->question_type;
            
            if ($questionType === 'short_answer') {
                // Chỉ 1 đáp án đúng cho short answer
                Answer::create([
                    'questions_id' => $question->questions_id,
                    'match_key' => 'A',
                    'answer_text' => $answerTemplates[$questionType][0][0],
                    'is_correct' => 1,
                    'feedback' => 'Correct answer!',
                    'media_url' => null,
                    'order_index' => 1,
                ]);
            } elseif ($questionType === 'true_false') {
                // 2 đáp án cho true/false
                Answer::create([
                    'questions_id' => $question->questions_id,
                    'match_key' => 'A',
                    'answer_text' => 'True',
                    'is_correct' => 1,
                    'feedback' => 'That is correct!',
                    'media_url' => null,
                    'order_index' => 1,
                ]);
                
                Answer::create([
                    'questions_id' => $question->questions_id,
                    'match_key' => 'B',
                    'answer_text' => 'False',
                    'is_correct' => 0,
                    'feedback' => 'That is incorrect.',
                    'media_url' => null,
                    'order_index' => 2,
                ]);
            } else {
                // 4 đáp án cho single_choice và multiple_choice
                $answers = $answerTemplates[$questionType][0]; // Lấy template đầu tiên
                
                for ($i = 0; $i < 4; $i++) {
                    $matchKeys = ['A', 'B', 'C', 'D'];
                    $isCorrect = ($i === 0) ? 1 : 0; // Đáp án đầu tiên là đúng
                    
                    if ($questionType === 'multiple_choice' && $i < 2) {
                        $isCorrect = 1; // 2 đáp án đầu đúng cho multiple choice
                    }
                    
                    Answer::create([
                        'questions_id' => $question->questions_id,
                        'match_key' => $matchKeys[$i],
                        'answer_text' => $answers[$i] ?? 'Option ' . ($i + 1),
                        'is_correct' => $isCorrect,
                        'feedback' => $isCorrect ? 'Correct!' : 'Try again.',
                        'media_url' => null,
                        'order_index' => $i + 1,
                    ]);
                }
            }
        }
    }
}
