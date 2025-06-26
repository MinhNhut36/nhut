<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Question;
use App\Models\Answer;
use App\Models\LessonPart;
use Illuminate\Support\Facades\DB;

class ComprehensiveQuestionsSeeder extends Seeder
{
    public function run(): void
    {
        // Xóa dữ liệu cũ
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Answer::truncate();
        Question::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $lessonParts = LessonPart::all();
        
        foreach ($lessonParts as $lessonPart) {
            $this->createQuestionsForLessonPart($lessonPart);
        }
    }

    private function createQuestionsForLessonPart($lessonPart)
    {
        $questionTypes = [
            'single_choice',
            'matching',
            'classification',
            'fill_blank',
            'arrangement',
            'image_word'
        ];

        $questionsData = [];
        $answersData = [];

        // Tạo 10 câu hỏi cho mỗi lesson part
        for ($i = 0; $i < 10; $i++) {
            $questionType = $questionTypes[$i % 6]; // Lặp qua 6 loại câu hỏi
            $questionIndex = $i + 1;

            $questionData = $this->getQuestionData($questionType, $lessonPart->level, $questionIndex);

            // Tạo question record
            $question = Question::create([
                'lesson_part_id' => $lessonPart->lesson_part_id,
                'question_type' => $questionType,
                'question_text' => $questionData['question_text'],
                'order_index' => $questionIndex,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Tạo answers cho câu hỏi
            foreach ($questionData['answers'] as $answerIndex => $answerData) {
                Answer::create([
                    'questions_id' => $question->questions_id,
                    'match_key' => $answerData['match_key'] ?? ('key_' . $question->questions_id . '_' . ($answerIndex + 1)),
                    'answer_text' => $answerData['text'],
                    'is_correct' => $answerData['correct'] ? 1 : 0,
                    'order_index' => $answerData['order'] ?? ($answerIndex + 1),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    private function getQuestionData($type, $level, $index)
    {
        switch ($type) {
            case 'single_choice':
                return $this->getSingleChoiceQuestion($level, $index);
            case 'matching':
                return $this->getMatchingQuestion($level, $index);
            case 'classification':
                return $this->getClassificationQuestion($level, $index);
            case 'fill_blank':
                return $this->getFillBlankQuestion($level, $index);
            case 'arrangement':
                return $this->getArrangementQuestion($level, $index);
            case 'image_word':
                return $this->getImageWordQuestion($level, $index);
            default:
                return $this->getSingleChoiceQuestion($level, $index);
        }
    }

    private function getSingleChoiceQuestion($level, $index)
    {
        $questions = [
            'A1' => [
                "What is the correct translation of 'Hello'?",
                "Which word means 'book'?",
                "What is the past tense of 'go'?",
                "How do you say 'thank you'?",
                "What color is the sun?",
            ],
            'A2' => [
                "Which sentence is grammatically correct?",
                "What is the comparative form of 'good'?",
                "Choose the correct preposition: 'I go ___ school'",
                "What does 'beautiful' mean?",
                "Which is the correct question form?",
            ],
            'A3' => [
                "What is the correct passive voice form?",
                "Choose the appropriate modal verb",
                "What is the meaning of this idiom?",
                "Which conditional sentence is correct?",
                "What is the correct reported speech?",
            ],
            'TA 2/6' => [
                "Analyze the complex sentence structure",
                "Choose the most appropriate advanced vocabulary",
                "What is the correct academic writing style?",
                "Identify the literary device used",
                "What is the correct business English expression?",
            ]
        ];

        $questionTexts = $questions[$level] ?? $questions['A1'];
        $questionText = $questionTexts[($index - 1) % count($questionTexts)];

        return [
            'question_text' => $questionText,
            'answers' => [
                ['text' => 'Xin chào', 'correct' => true, 'match_key' => 'hello'],
                ['text' => 'Tạm biệt', 'correct' => false, 'match_key' => 'goodbye'],
                ['text' => 'Cảm ơn', 'correct' => false, 'match_key' => 'thanks'],
                ['text' => 'Xin lỗi', 'correct' => false, 'match_key' => 'sorry'],
            ]
        ];
    }

    private function getMatchingQuestion($level, $index)
    {
        return [
            'question_text' => "Match the English word with its Vietnamese meaning (Question $index)",
            'answers' => [
                ['text' => 'Táo', 'correct' => true, 'match_key' => 'apple'],
                ['text' => 'Cam', 'correct' => false, 'match_key' => 'orange'],
                ['text' => 'Chuối', 'correct' => false, 'match_key' => 'banana'],
                ['text' => 'Nho', 'correct' => false, 'match_key' => 'grape'],
            ]
        ];
    }

    private function getClassificationQuestion($level, $index)
    {
        return [
            'question_text' => "Classify these words into correct categories (Question $index)",
            'answers' => [
                ['text' => 'run', 'correct' => true, 'match_key' => 'verb', 'group_label' => 'Verb'],
                ['text' => 'beautiful', 'correct' => true, 'match_key' => 'adjective', 'group_label' => 'Adjective'],
                ['text' => 'quickly', 'correct' => true, 'match_key' => 'adverb', 'group_label' => 'Adverb'],
                ['text' => 'house', 'correct' => true, 'match_key' => 'noun', 'group_label' => 'Noun'],
            ]
        ];
    }

    private function getFillBlankQuestion($level, $index)
    {
        return [
            'question_text' => "Fill in the blank: 'I ___ to school every day' (Question $index)",
            'answers' => [
                ['text' => 'go', 'correct' => true, 'match_key' => 'blank1'],
            ]
        ];
    }

    private function getArrangementQuestion($level, $index)
    {
        return [
            'question_text' => "Arrange these words to make a correct sentence (Question $index)",
            'answers' => [
                ['text' => 'I', 'correct' => true, 'match_key' => 'word1', 'order' => 1],
                ['text' => 'am', 'correct' => true, 'match_key' => 'word2', 'order' => 2],
                ['text' => 'a', 'correct' => true, 'match_key' => 'word3', 'order' => 3],
                ['text' => 'student', 'correct' => true, 'match_key' => 'word4', 'order' => 4],
                ['text' => 'teacher', 'correct' => false, 'match_key' => 'wrong1', 'order' => 5],
            ]
        ];
    }

    private function getImageWordQuestion($level, $index)
    {
        return [
            'question_text' => "Look at the image and arrange letters to form the correct word (Question $index)",
            'answers' => [
                ['text' => 'c', 'correct' => true, 'match_key' => 'letter1', 'order' => 1],
                ['text' => 'a', 'correct' => true, 'match_key' => 'letter2', 'order' => 2],
                ['text' => 't', 'correct' => true, 'match_key' => 'letter3', 'order' => 3],
                ['text' => 'x', 'correct' => false, 'match_key' => 'wrong_letter1', 'order' => 4],
                ['text' => 'z', 'correct' => false, 'match_key' => 'wrong_letter2', 'order' => 5],
            ]
        ];
    }
}
