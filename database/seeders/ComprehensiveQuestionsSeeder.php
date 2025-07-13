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
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Answer::truncate();
        Question::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $lessonParts = LessonPart::all();
        $types = ['single_choice','matching','classification','fill_blank','arrangement','image_word'];

        foreach ($lessonParts as $lessonPart) {
            // Tạo 10 câu hỏi cho mỗi lesson part (giảm từ 30 xuống 10)
            for ($i = 0; $i < 10; $i++) {
                $type  = $types[$i % count($types)];
                $order = $i + 1;
                $data  = $this->getQuestionData($type, $lessonPart->level, $order);

                $question = Question::create([
                    'lesson_part_id' => $lessonPart->lesson_part_id,
                    'question_type'  => $type,
                    'question_text'  => $data['question_text'],
                    'order_index'    => $order,
                    'media_url'      => $data['media_url'] ?? null,
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ]);

                foreach ($data['answers'] as $idx => $ans) {
                    Answer::create([
                        'questions_id' => $question->questions_id,
                        'match_key'    => $ans['match_key'] ?? 'key_'.$question->questions_id.'_'.($idx+1),
                        'answer_text'  => $ans['text'],
                        'is_correct'   => $ans['correct'] ? 1 : 0,
                        'order_index'  => $ans['order'] ?? ($idx+1),
                        'media_url'    => $ans['media_url'] ?? null,
                        'created_at'   => now(),
                        'updated_at'   => now(),
                    ]);
                }
            }
        }
    }

    private function getQuestionData($type, $level, $i)
    {
        return match ($type) {
            'single_choice'  => $this->singleChoice($level, $i),
            'matching'       => $this->matching($level, $i),
            'classification' => $this->classification($level, $i),
            'fill_blank'     => $this->fillBlank($level, $i),
            'arrangement'    => $this->arrangement($level, $i),
            'image_word'     => $this->imageWord($level, $i),
            default          => $this->singleChoice($level, $i),
        };
    }

    private function singleChoice($level, $i)
    {
        $questions = [
            'A1' => [
                "What is the correct translation of 'Hello'?",
                "Which word means 'book'?",
                "What is the past tense of 'go'?",
                "How do you say 'thank you'?",
                "What color is the sun?",
                "Choose the correct article: '___ apple'",
                "What does 'cat' mean?",
                "How do you spell 'water'?",
                "What is the opposite of 'big'?",
                "Which is correct: 'I am' or 'I is'?",
            ],
            'A2' => [
                "Which sentence is grammatically correct?",
                "What is the comparative form of 'good'?",
                "Choose the correct preposition: 'I go ___ school'",
                "What does 'beautiful' mean?",
                "Which is the correct question form?",
                "What is the past tense of 'eat'?",
                "Choose the correct modal: 'You ___ study hard'",
                "What is the plural of 'child'?",
                "Which preposition fits: 'I live ___ Vietnam'?",
                "What does 'excited' mean?",
            ],
            'A3' => [
                "What is the correct passive voice form?",
                "Choose the appropriate modal verb",
                "What is the meaning of this idiom?",
                "Which conditional sentence is correct?",
                "What is the correct reported speech?",
                "Choose the correct phrasal verb",
                "What is the subjunctive form?",
                "Which relative pronoun is correct?",
                "What is the correct gerund form?",
                "Choose the appropriate linking word",
            ]
        ];

        $answers = [
            'A1' => [
                [['text' => 'Xin chào', 'correct' => true], ['text' => 'Tạm biệt', 'correct' => false], ['text' => 'Cảm ơn', 'correct' => false], ['text' => 'Xin lỗi', 'correct' => false]],
                [['text' => 'Sách', 'correct' => true], ['text' => 'Bút', 'correct' => false], ['text' => 'Bàn', 'correct' => false], ['text' => 'Ghế', 'correct' => false]],
                [['text' => 'went', 'correct' => true], ['text' => 'goed', 'correct' => false], ['text' => 'goes', 'correct' => false], ['text' => 'going', 'correct' => false]],
                [['text' => 'Thank you', 'correct' => true], ['text' => 'Hello', 'correct' => false], ['text' => 'Goodbye', 'correct' => false], ['text' => 'Sorry', 'correct' => false]],
                [['text' => 'Yellow', 'correct' => true], ['text' => 'Blue', 'correct' => false], ['text' => 'Green', 'correct' => false], ['text' => 'Red', 'correct' => false]],
            ],
            'A2' => [
                [['text' => 'I am happy', 'correct' => true], ['text' => 'I is happy', 'correct' => false], ['text' => 'I are happy', 'correct' => false], ['text' => 'I be happy', 'correct' => false]],
                [['text' => 'better', 'correct' => true], ['text' => 'gooder', 'correct' => false], ['text' => 'more good', 'correct' => false], ['text' => 'best', 'correct' => false]],
                [['text' => 'to', 'correct' => true], ['text' => 'at', 'correct' => false], ['text' => 'in', 'correct' => false], ['text' => 'on', 'correct' => false]],
                [['text' => 'Đẹp', 'correct' => true], ['text' => 'Xấu', 'correct' => false], ['text' => 'Lớn', 'correct' => false], ['text' => 'Nhỏ', 'correct' => false]],
                [['text' => 'What is your name?', 'correct' => true], ['text' => 'What your name is?', 'correct' => false], ['text' => 'Your name what is?', 'correct' => false], ['text' => 'Is what your name?', 'correct' => false]],
            ]
        ];

        $levelQuestions = $questions[$level] ?? $questions['A1'];
        $levelAnswers = $answers[$level] ?? $answers['A1'];

        $qIndex = ($i - 1) % count($levelQuestions);
        $aIndex = ($i - 1) % count($levelAnswers);

        return [
            'question_text' => "[$level] " . $levelQuestions[$qIndex] . " (Q{$i})",
            'answers' => $levelAnswers[$aIndex]
        ];
    }

    private function matching($level, $i)
    {
        $sets = [
            ['dog','cat','cow'],
            ['car','bike','bus'],
            ['apple','orange','banana'],
            ['chair','table','bed'],
            ['book','pen','paper'],
            ['red','blue','green'],
            ['happy','sad','angry'],
            ['big','small','tall'],
        ];

        $links = [
            'dog'    => 'https://upload.wikimedia.org/wikipedia/commons/6/6e/Golde33443.jpg',
            'cat'    => 'https://upload.wikimedia.org/wikipedia/commons/3/3a/Cat03.jpg',
            'cow'    => 'https://upload.wikimedia.org/wikipedia/commons/0/0c/Cow_female_black_white.jpg',
            'car'    => 'https://upload.wikimedia.org/wikipedia/commons/thumb/e/e5/Fiat_500_in_Emilia-Romagna.jpg/640px-Fiat_500_in_Emilia-Romagna.jpg',
            'bike'   => 'https://upload.wikimedia.org/wikipedia/commons/thumb/b/bc/Dutch_bicycle.jpg/640px-Dutch_bicycle.jpg',
            'bus'    => 'https://upload.wikimedia.org/wikipedia/commons/thumb/2/26/LiAZ-5292.20_in_Seversk.jpg/640px-LiAZ-5292.20_in_Seversk.jpg',
            'apple'  => 'https://upload.wikimedia.org/wikipedia/commons/1/15/Red_Apple.jpg',
            'orange' => 'https://upload.wikimedia.org/wikipedia/commons/c/c4/Orange-Fruit-Pieces.jpg',
            'banana' => 'https://upload.wikimedia.org/wikipedia/commons/8/8a/Banana-Single.jpg',
            'chair'  => 'https://upload.wikimedia.org/wikipedia/commons/thumb/8/86/Fauteuil_Riviera_Chaise_Bleue_Neptune_SBR.jpg/640px-Fauteuil_Riviera_Chaise_Bleue_Neptune_SBR.jpg',
            'table'  => 'https://upload.wikimedia.org/wikipedia/commons/d/dc/Table_MET_DP112858.jpg',
            'bed'    => 'https://upload.wikimedia.org/wikipedia/commons/thumb/f/ff/Bed_in_hotel_room_2.jpg/640px-Bed_in_hotel_room_2.jpg',
             'red'    => 'https://upload.wikimedia.org/wikipedia/commons/thumb/f/fd/Color_icon_red.svg/640px-Color_icon_red.svg.png',
            'blue'    => 'https://upload.wikimedia.org/wikipedia/commons/thumb/b/bd/Color_icon_blue.svg/640px-Color_icon_blue.svg.png',
            'green'    => 'https://upload.wikimedia.org/wikipedia/commons/thumb/7/7d/Color_icon_green.svg/640px-Color_icon_green.svg.png',
            'happy'    => 'https://upload.wikimedia.org/wikipedia/commons/thumb/0/07/Felicidade_A_very_happy_boy.jpg/640px-Felicidade_A_very_happy_boy.jpg',
            'sad'   => 'https://upload.wikimedia.org/wikipedia/commons/thumb/a/a8/Sad_Icon.png/640px-Sad_Icon.png',
            'angry'    => 'https://upload.wikimedia.org/wikipedia/commons/thumb/6/64/Angry_Icon.png/640px-Angry_Icon.png',
            'big'  => 'https://upload.wikimedia.org/wikipedia/commons/thumb/3/3b/African_elephant_%28Loxodonta_africana%29_3.jpg/640px-African_elephant_%28Loxodonta_africana%29_3.jpg',
            'small' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/6/61/Mouse_in_snow.jpg/640px-Mouse_in_snow.jpg',
            'tall' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/c/c7/Worcester_Stands_Tall_-_A_Tall_Order_-_geograph.org.uk_-_6926596.jpg/640px-Worcester_Stands_Tall_-_A_Tall_Order_-_geograph.org.uk_-_6926596.jpg',
        ];

        $idx = ($i - 1) % count($sets);
        $items = $sets[$idx];
        $answers = [];

        foreach ($items as $j => $txt) {    
            $answers[] = [
            'text'      => ucfirst($txt),
            'match_key' => $txt,
            'correct'   => true, // Tất cả đều đúng
            'media_url' => $links[$txt] ?? null,
        ];
        }

        return [
            'question_text' => "[$level] Match image to word (Q{$i})",
            'answers'       => $answers,
        ];
    }

    private function classification($level, $i)
    {
        $wordSets = [
            [
                ['run', 'verb'], ['walk', 'verb'], ['jump', 'verb'],
                ['beautiful', 'adjective'], ['happy', 'adjective'], ['big', 'adjective'],
                ['quickly', 'adverb'], ['slowly', 'adverb'], ['carefully', 'adverb'],
                ['house', 'noun'], ['book', 'noun'], ['car', 'noun']
            ],
            [
                ['eat', 'verb'], ['drink', 'verb'], ['sleep', 'verb'],
                ['red', 'adjective'], ['small', 'adjective'], ['good', 'adjective'],
                ['very', 'adverb'], ['well', 'adverb'], ['often', 'adverb'],
                ['dog', 'noun'], ['tree', 'noun'], ['water', 'noun']
            ],
            [
                ['study', 'verb'], ['work', 'verb'], ['play', 'verb'],
                ['smart', 'adjective'], ['funny', 'adjective'], ['kind', 'adjective'],
                ['always', 'adverb'], ['never', 'adverb'], ['sometimes', 'adverb'],
                ['school', 'noun'], ['friend', 'noun'], ['family', 'noun']
            ]
        ];

        $setIndex = ($i - 1) % count($wordSets);
        $words = $wordSets[$setIndex];
        $selectedWords = array_slice($words, 0, 6); // Take 6 words

        $answers = [];
        foreach ($selectedWords as $wordData) {
            $answers[] = [
                'text' => $wordData[0],
                'correct' => true,
                'match_key' => $wordData[1],
            ];
        }

        return [
            'question_text' => "[$level] Classify these words into correct categories (Q{$i})",
            'answers' => $answers,
        ];
    }

    private function fillBlank($level, $i)
    {
        $sentences = [
            "I ___ to school every day",
            "She ___ a book yesterday",
            "They ___ playing football",
            "We ___ happy today",
            "He ___ from Vietnam",
            "You ___ very smart",
            "It ___ raining outside",
            "The cat ___ sleeping",
        ];

        $answers = [
            'go', 'read', 'are', 'are', 'is', 'are', 'is', 'is'
        ];

        $idx = ($i - 1) % count($sentences);

        return [
            'question_text' => "[$level] Fill in the blank: '{$sentences[$idx]}' (Q{$i})",
            'answers' => [
                ['text' => $answers[$idx], 'correct' => true, 'match_key' => 'blank1'],
            ]
        ];
    }

    private function arrangement($level, $i)
    {
        $sentences = [
            ['I', 'am', 'a', 'student'],
            ['She', 'likes', 'to', 'read'],
            ['We', 'are', 'learning', 'English'],
            ['They', 'play', 'football', 'well'],
            ['He', 'goes', 'to', 'work'],
            ['You', 'speak', 'very', 'clearly'],
            ['The', 'cat', 'is', 'sleeping'],
            ['My', 'family', 'loves', 'me'],
        ];

        $idx = ($i - 1) % count($sentences);
        $words = $sentences[$idx];
        $answers = [];

        foreach ($words as $j => $word) {
            $answers[] = [
                'text' => $word,
                'correct' => true,
                'match_key' => 'word' . ($j + 1),
                'order' => $j + 1
            ];
        }

        return [
            'question_text' => "[$level] Arrange these words to make a correct sentence (Q{$i})",
            'answers' => $answers,
        ];
    }

    private function imageWord($level, $i)
    {
        $words = ['cat','dog','bird','fish','book','car','tree','house'];
        $links = [
            'cat'  => 'https://upload.wikimedia.org/wikipedia/commons/3/3a/Cat03.jpg',
            'dog'  => 'https://upload.wikimedia.org/wikipedia/commons/6/6e/Golde33443.jpg',
            'bird' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/c/c8/Asian_pied_starlings_%28Gracupica_contra%29.jpg/640px-Asian_pied_starlings_%28Gracupica_contra%29.jpg',
            'fish' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/f/f1/Astronotus_ocellatus_-_Karlsruhe_Zoo_01.jpg/640px-Astronotus_ocellatus_-_Karlsruhe_Zoo_01.jpg',
            'book' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/4/48/Dresden_Edition_overall.JPG/640px-Dresden_Edition_overall.JPG',
            'car'  => 'https://upload.wikimedia.org/wikipedia/commons/thumb/e/e5/Fiat_500_in_Emilia-Romagna.jpg/640px-Fiat_500_in_Emilia-Romagna.jpg',
            'tree' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/e/eb/Ash_Tree_-_geograph.org.uk_-_590710.jpg/640px-Ash_Tree_-_geograph.org.uk_-_590710.jpg',
            'house'=> 'https://upload.wikimedia.org/wikipedia/commons/thumb/f/f9/Cottage_at_Stoke_Tye%2C_Suffolk_-_geograph.org.uk_-_230113.jpg/640px-Cottage_at_Stoke_Tye%2C_Suffolk_-_geograph.org.uk_-_230113.jpg    ',
        ];

        $idx = ($i - 1) % count($words);
        $w = $words[$idx];
        $letters = str_split($w);
        $answers = [];

        foreach ($letters as $j => $l) {
            $answers[] = ['text'=>$l,'correct'=>true,'order'=>$j+1];
        }

        return [
            'question_text' => "[$level] What word is shown? (Q{$i})",
            'media_url'     => $links[$w],
            'answers'       => $answers,
        ];
    }
}
