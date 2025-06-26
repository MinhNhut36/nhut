<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Question;
use App\Models\Answer;
use App\Models\StudentAnswer;
use Illuminate\Support\Facades\DB;

class EnhancedQuestionsSeeder extends Seeder
{
    public function run(): void
    {
        // Xóa dữ liệu cũ
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        StudentAnswer::truncate();
        Answer::truncate();
        Question::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Dạng 1: Trắc nghiệm 4 đáp án (nhiều câu hỏi)
        $questions_type1 = [
            [
                'question_text' => 'What is the correct translation of "Hello"?',
                'answers' => [
                    ['text' => 'Xin chào', 'correct' => true],
                    ['text' => 'Tạm biệt', 'correct' => false],
                    ['text' => 'Cảm ơn', 'correct' => false],
                    ['text' => 'Xin lỗi', 'correct' => false]
                ]
            ],
            [
                'question_text' => 'Which word means "book" in Vietnamese?',
                'answers' => [
                    ['text' => 'Bút', 'correct' => false],
                    ['text' => 'Sách', 'correct' => true],
                    ['text' => 'Bàn', 'correct' => false],
                    ['text' => 'Ghế', 'correct' => false]
                ]
            ],
            [
                'question_text' => 'What is the past tense of "go"?',
                'answers' => [
                    ['text' => 'goes', 'correct' => false],
                    ['text' => 'going', 'correct' => false],
                    ['text' => 'went', 'correct' => true],
                    ['text' => 'gone', 'correct' => false]
                ]
            ],
            [
                'question_text' => 'Choose the correct article: ___ apple',
                'answers' => [
                    ['text' => 'a', 'correct' => false],
                    ['text' => 'an', 'correct' => true],
                    ['text' => 'the', 'correct' => false],
                    ['text' => 'no article', 'correct' => false]
                ]
            ],
            [
                'question_text' => 'What does "cat" mean in Vietnamese?',
                'answers' => [
                    ['text' => 'Chó', 'correct' => false],
                    ['text' => 'Mèo', 'correct' => true],
                    ['text' => 'Gà', 'correct' => false],
                    ['text' => 'Vịt', 'correct' => false]
                ]
            ],
            [
                'question_text' => 'Which is the correct plural form of "child"?',
                'answers' => [
                    ['text' => 'childs', 'correct' => false],
                    ['text' => 'childes', 'correct' => false],
                    ['text' => 'children', 'correct' => true],
                    ['text' => 'child', 'correct' => false]
                ]
            ],
            [
                'question_text' => 'What is "good morning" in Vietnamese?',
                'answers' => [
                    ['text' => 'Chào buổi sáng', 'correct' => true],
                    ['text' => 'Chào buổi chiều', 'correct' => false],
                    ['text' => 'Chào buổi tối', 'correct' => false],
                    ['text' => 'Chúc ngủ ngon', 'correct' => false]
                ]
            ],
            [
                'question_text' => 'Choose the correct pronoun: ___ is a teacher.',
                'answers' => [
                    ['text' => 'Him', 'correct' => false],
                    ['text' => 'Her', 'correct' => false],
                    ['text' => 'She', 'correct' => true],
                    ['text' => 'Hers', 'correct' => false]
                ]
            ]
        ];

        // Dạng 2: Nối từ với hình ảnh hoặc nghĩa (nhiều câu hỏi)
        $questions_type2 = [
            [
                'question_text' => 'Match the word "panda" with its correct image:',
                'question_type' => 'matching',
                'answers' => [
                    ['text' => 'Image: Bear', 'match_key' => 'panda', 'correct' => true],
                    ['text' => 'Image: Cat', 'match_key' => 'cat', 'correct' => false],
                    ['text' => 'Image: Dog', 'match_key' => 'dog', 'correct' => false],
                    ['text' => 'Image: Lion', 'match_key' => 'lion', 'correct' => false]
                ]
            ],
            [
                'question_text' => 'Match "apple" with its Vietnamese meaning:',
                'question_type' => 'matching',
                'answers' => [
                    ['text' => 'Táo', 'match_key' => 'apple', 'correct' => true],
                    ['text' => 'Cam', 'match_key' => 'orange', 'correct' => false],
                    ['text' => 'Chuối', 'match_key' => 'banana', 'correct' => false],
                    ['text' => 'Nho', 'match_key' => 'grape', 'correct' => false]
                ]
            ],
            [
                'question_text' => 'Match "house" with its Vietnamese meaning:',
                'question_type' => 'matching',
                'answers' => [
                    ['text' => 'Nhà', 'match_key' => 'house', 'correct' => true],
                    ['text' => 'Trường', 'match_key' => 'school', 'correct' => false],
                    ['text' => 'Bệnh viện', 'match_key' => 'hospital', 'correct' => false],
                    ['text' => 'Cửa hàng', 'match_key' => 'shop', 'correct' => false]
                ]
            ],
            [
                'question_text' => 'Match "water" with its image:',
                'question_type' => 'matching',
                'answers' => [
                    ['text' => 'Image: Water bottle', 'match_key' => 'water', 'correct' => true],
                    ['text' => 'Image: Fire', 'match_key' => 'fire', 'correct' => false],
                    ['text' => 'Image: Earth', 'match_key' => 'earth', 'correct' => false],
                    ['text' => 'Image: Air', 'match_key' => 'air', 'correct' => false]
                ]
            ]
        ];

        // Dạng 3: Phân loại từ (nhiều câu hỏi)
        $questions_type3 = [
            [
                'question_text' => 'Classify these words: apple, run, happy, book, jump, beautiful',
                'question_type' => 'classification',
                'answers' => [
                    ['text' => 'apple', 'match_key' => 'noun', 'correct' => true],
                    ['text' => 'run', 'match_key' => 'verb', 'correct' => true],
                    ['text' => 'happy', 'match_key' => 'adjective', 'correct' => true],
                    ['text' => 'book', 'match_key' => 'noun', 'correct' => true],
                    ['text' => 'jump', 'match_key' => 'verb', 'correct' => true],
                    ['text' => 'beautiful', 'match_key' => 'adjective', 'correct' => true]
                ]
            ],
            [
                'question_text' => 'Classify: cat, sing, tall, pen, dance, small',
                'question_type' => 'classification',
                'answers' => [
                    ['text' => 'cat', 'match_key' => 'noun', 'correct' => true],
                    ['text' => 'sing', 'match_key' => 'verb', 'correct' => true],
                    ['text' => 'tall', 'match_key' => 'adjective', 'correct' => true],
                    ['text' => 'pen', 'match_key' => 'noun', 'correct' => true],
                    ['text' => 'dance', 'match_key' => 'verb', 'correct' => true],
                    ['text' => 'small', 'match_key' => 'adjective', 'correct' => true]
                ]
            ]
        ];

        // Dạng 4: Điền vào chỗ trống (nhiều câu hỏi)
        $questions_type4 = [
            [
                'question_text' => 'This __ my ______.',
                'question_type' => 'fill_blank',
                'answers' => [
                    ['text' => 'is', 'match_key' => 'blank1', 'correct' => true],
                    ['text' => 'pen', 'match_key' => 'blank2', 'correct' => true],
                    ['text' => 'are', 'match_key' => 'blank1', 'correct' => false],
                    ['text' => 'book', 'match_key' => 'blank2', 'correct' => false]
                ]
            ],
            [
                'question_text' => 'I __ to school every day.',
                'question_type' => 'fill_blank',
                'answers' => [
                    ['text' => 'go', 'match_key' => 'blank1', 'correct' => true],
                    ['text' => 'goes', 'match_key' => 'blank1', 'correct' => false],
                    ['text' => 'going', 'match_key' => 'blank1', 'correct' => false],
                    ['text' => 'went', 'match_key' => 'blank1', 'correct' => false]
                ]
            ],
            [
                'question_text' => 'She __ a teacher.',
                'question_type' => 'fill_blank',
                'answers' => [
                    ['text' => 'is', 'match_key' => 'blank1', 'correct' => true],
                    ['text' => 'are', 'match_key' => 'blank1', 'correct' => false],
                    ['text' => 'am', 'match_key' => 'blank1', 'correct' => false],
                    ['text' => 'be', 'match_key' => 'blank1', 'correct' => false]
                ]
            ]
        ];

        // Dạng 5: Sắp xếp thành câu đúng (nhiều câu hỏi)
        $questions_type5 = [
            [
                'question_text' => 'Arrange: weren\'t, No, they',
                'question_type' => 'arrangement',
                'answers' => [
                    ['text' => 'No, they weren\'t.', 'match_key' => 'correct_order', 'correct' => true],
                    ['text' => 'They weren\'t no.', 'match_key' => 'wrong_order1', 'correct' => false],
                    ['text' => 'Weren\'t they no.', 'match_key' => 'wrong_order2', 'correct' => false],
                    ['text' => 'No weren\'t they.', 'match_key' => 'wrong_order3', 'correct' => false]
                ]
            ],
            [
                'question_text' => 'Arrange: is, This, book, my, a',
                'question_type' => 'arrangement',
                'answers' => [
                    ['text' => 'This is my book.', 'match_key' => 'correct_order', 'correct' => true],
                    ['text' => 'Book my is this.', 'match_key' => 'wrong_order1', 'correct' => false],
                    ['text' => 'My this is book.', 'match_key' => 'wrong_order2', 'correct' => false],
                    ['text' => 'Is this my book.', 'match_key' => 'wrong_order3', 'correct' => false]
                ]
            ],
            [
                'question_text' => 'Arrange: am, I, student, a',
                'question_type' => 'arrangement',
                'answers' => [
                    ['text' => 'I am a student.', 'match_key' => 'correct_order', 'correct' => true],
                    ['text' => 'Student I am a.', 'match_key' => 'wrong_order1', 'correct' => false],
                    ['text' => 'Am I a student.', 'match_key' => 'wrong_order2', 'correct' => false],
                    ['text' => 'A student I am.', 'match_key' => 'wrong_order3', 'correct' => false]
                ]
            ]
        ];

        // Dạng 6: Nhìn ảnh sắp xếp thành từ đúng (nhiều câu hỏi)
        $questions_type6 = [
            [
                'question_text' => 'Look at the cat image and arrange: t, a, c',
                'question_type' => 'image_word',
                'media_url' => 'https://example.com/images/cat.jpg',
                'answers' => [
                    ['text' => 'cat', 'match_key' => 'correct_word', 'correct' => true],
                    ['text' => 'act', 'match_key' => 'wrong_word1', 'correct' => false],
                    ['text' => 'tac', 'match_key' => 'wrong_word2', 'correct' => false],
                    ['text' => 'cta', 'match_key' => 'wrong_word3', 'correct' => false]
                ]
            ],
            [
                'question_text' => 'Look at the dog image and arrange: g, o, d',
                'question_type' => 'image_word',
                'media_url' => 'https://example.com/images/dog.jpg',
                'answers' => [
                    ['text' => 'dog', 'match_key' => 'correct_word', 'correct' => true],
                    ['text' => 'god', 'match_key' => 'wrong_word1', 'correct' => false],
                    ['text' => 'odg', 'match_key' => 'wrong_word2', 'correct' => false],
                    ['text' => 'gdo', 'match_key' => 'wrong_word3', 'correct' => false]
                ]
            ],
            [
                'question_text' => 'Look at the pen image and arrange: p, e, n',
                'question_type' => 'image_word',
                'media_url' => 'https://example.com/images/pen.jpg',
                'answers' => [
                    ['text' => 'pen', 'match_key' => 'correct_word', 'correct' => true],
                    ['text' => 'nep', 'match_key' => 'wrong_word1', 'correct' => false],
                    ['text' => 'enp', 'match_key' => 'wrong_word2', 'correct' => false],
                    ['text' => 'pne', 'match_key' => 'wrong_word3', 'correct' => false]
                ]
            ]
        ];

        $this->createQuestionsAndAnswers($questions_type1, 'single_choice');
        $this->createQuestionsAndAnswers($questions_type2, 'matching');
        $this->createQuestionsAndAnswers($questions_type3, 'classification');
        $this->createQuestionsAndAnswers($questions_type4, 'fill_blank');
        $this->createQuestionsAndAnswers($questions_type5, 'arrangement');
        $this->createQuestionsAndAnswers($questions_type6, 'image_word');

        // Tạo student answers mẫu
        $this->createStudentAnswers();
    }

    private function createQuestionsAndAnswers($questions, $defaultType)
    {
        static $questionId = 1;
        static $answerId = 1;

        foreach ($questions as $questionData) {
            // Tạo question
            $question = new Question();
            $question->questions_id = $questionId;
            $question->lesson_part_id = (($questionId - 1) % 8) + 1;
            $question->question_type = $questionData['question_type'] ?? $defaultType;
            $question->question_text = $questionData['question_text'];
            $question->media_url = $questionData['media_url'] ?? null;
            $question->order_index = $questionId;
            $question->save();

            // Tạo answers
            foreach ($questionData['answers'] as $answerIndex => $answerData) {
                $answer = new Answer();
                $answer->answers_id = $answerId;
                $answer->questions_id = $questionId;
                $answer->match_key = $answerData['match_key'] ?? chr(65 + $answerIndex);
                $answer->answer_text = $answerData['text'];
                $answer->is_correct = $answerData['correct'] ? 1 : 0;
                $answer->feedback = $answerData['correct'] ? 'Correct!' : 'Try again.';
                $answer->order_index = $answerIndex + 1;
                $answer->save();

                $answerId++;
            }

            $questionId++;
        }
    }

    private function createStudentAnswers()
    {
        // Tạo student answers mẫu cho 3 students
        $studentAnswers = [
            // Student 1 answers
            ['student_id' => 1, 'questions_id' => 1, 'course_id' => 1, 'answer_text' => 'Xin chào'],
            ['student_id' => 1, 'questions_id' => 2, 'course_id' => 1, 'answer_text' => 'Sách'],
            ['student_id' => 1, 'questions_id' => 3, 'course_id' => 1, 'answer_text' => 'went'],
            ['student_id' => 1, 'questions_id' => 4, 'course_id' => 1, 'answer_text' => 'an'],
            ['student_id' => 1, 'questions_id' => 5, 'course_id' => 1, 'answer_text' => 'Mèo'],

            // Student 2 answers
            ['student_id' => 2, 'questions_id' => 1, 'course_id' => 1, 'answer_text' => 'Xin chào'],
            ['student_id' => 2, 'questions_id' => 2, 'course_id' => 1, 'answer_text' => 'Bút'], // wrong
            ['student_id' => 2, 'questions_id' => 3, 'course_id' => 1, 'answer_text' => 'went'],
            ['student_id' => 2, 'questions_id' => 4, 'course_id' => 1, 'answer_text' => 'a'], // wrong
            ['student_id' => 2, 'questions_id' => 5, 'course_id' => 1, 'answer_text' => 'Mèo'],

            // Student 3 answers
            ['student_id' => 3, 'questions_id' => 1, 'course_id' => 1, 'answer_text' => 'Tạm biệt'], // wrong
            ['student_id' => 3, 'questions_id' => 2, 'course_id' => 1, 'answer_text' => 'Sách'],
            ['student_id' => 3, 'questions_id' => 3, 'course_id' => 1, 'answer_text' => 'goes'], // wrong
            ['student_id' => 3, 'questions_id' => 4, 'course_id' => 1, 'answer_text' => 'an'],
            ['student_id' => 3, 'questions_id' => 5, 'course_id' => 1, 'answer_text' => 'Chó'], // wrong
        ];

        foreach ($studentAnswers as $answerData) {
            $studentAnswer = new StudentAnswer();
            $studentAnswer->student_id = $answerData['student_id'];
            $studentAnswer->questions_id = $answerData['questions_id'];
            $studentAnswer->course_id = $answerData['course_id'];
            $studentAnswer->answer_text = $answerData['answer_text'];
            $studentAnswer->answered_at = now()->subDays(rand(1, 30));
            $studentAnswer->save();
        }
    }
}
