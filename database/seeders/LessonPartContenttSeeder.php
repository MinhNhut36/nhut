<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\LessonPartContent;

class LessonPartContenttSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // A1 - Vocabulary (lesson_part_id: 1)
        LessonPartContent::create([
            'lesson_part_id' => 1,
            'content_type' => 'text',
            'content_data' => 'Hello, Goodbye, Please, Thank you, Yes, No',
            'mini_game_type' => null,
        ]);
        LessonPartContent::create([
            'lesson_part_id' => 1,
            'content_type' => 'quiz',
            'content_data' => 'Choose the correct greeting for the morning.',
            'mini_game_type' => 'quiz',
        ]);

        // A1 - Grammar (lesson_part_id: 2)
        LessonPartContent::create([
            'lesson_part_id' => 2,
            'content_type' => 'text',
            'content_data' => 'Simple Present Tense: I am, You are, He/She is',
            'mini_game_type' => null,
        ]);
        LessonPartContent::create([
            'lesson_part_id' => 2,
            'content_type' => 'video',
            'content_data' => 'https://www.youtube.com/watch?v=grammar_a1',
            'mini_game_type' => null,
        ]);

        // A1 - Listening (lesson_part_id: 3)
        LessonPartContent::create([
            'lesson_part_id' => 3,
            'content_type' => 'audio',
            'content_data' => 'https://audio.example.com/greetings.mp3',
            'mini_game_type' => null,
        ]);
        LessonPartContent::create([
            'lesson_part_id' => 3,
            'content_type' => 'quiz',
            'content_data' => 'Listen and choose the correct answer.',
            'mini_game_type' => 'quiz',
        ]);

        // A1 - Speaking (lesson_part_id: 4)
        LessonPartContent::create([
            'lesson_part_id' => 4,
            'content_type' => 'text',
            'content_data' => 'Practice introducing yourself: "My name is..."',
            'mini_game_type' => null,
        ]);
        LessonPartContent::create([
            'lesson_part_id' => 4,
            'content_type' => 'mini_game',
            'content_data' => 'Match the phrases to the correct responses.',
            'mini_game_type' => 'matching',
        ]);

        // A2 - Vocabulary (lesson_part_id: 5)
        LessonPartContent::create([
            'lesson_part_id' => 5,
            'content_type' => 'text',
            'content_data' => 'Daily routines: wake up, brush teeth, go to school',
            'mini_game_type' => null,
        ]);
        LessonPartContent::create([
            'lesson_part_id' => 5,
            'content_type' => 'quiz',
            'content_data' => 'Select the correct day of the week.',
            'mini_game_type' => 'quiz',
        ]);

        // A2 - Grammar (lesson_part_id: 6)
        LessonPartContent::create([
            'lesson_part_id' => 6,
            'content_type' => 'text',
            'content_data' => 'Present Continuous: I am eating, She is reading',
            'mini_game_type' => null,
        ]);
        LessonPartContent::create([
            'lesson_part_id' => 6,
            'content_type' => 'video',
            'content_data' => 'https://www.youtube.com/watch?v=present_continuous',
            'mini_game_type' => null,
        ]);

        // A2 - Listening (lesson_part_id: 7)
        LessonPartContent::create([
            'lesson_part_id' => 7,
            'content_type' => 'audio',
            'content_data' => 'https://audio.example.com/daily_routine.mp3',
            'mini_game_type' => null,
        ]);
        LessonPartContent::create([
            'lesson_part_id' => 7,
            'content_type' => 'quiz',
            'content_data' => 'Listen and fill in the blanks.',
            'mini_game_type' => 'quiz',
        ]);

        // A2 - Speaking (lesson_part_id: 8)
        LessonPartContent::create([
            'lesson_part_id' => 8,
            'content_type' => 'text',
            'content_data' => 'Describe your daily routine to your partner.',
            'mini_game_type' => null,
        ]);
        LessonPartContent::create([
            'lesson_part_id' => 8,
            'content_type' => 'mini_game',
            'content_data' => 'Practice asking and answering about routines.',
            'mini_game_type' => 'matching',
        ]);

        // A3 - Vocabulary (lesson_part_id: 9)
        LessonPartContent::create([
            'lesson_part_id' => 9,
            'content_type' => 'text',
            'content_data' => 'Travel: airport, ticket, passport, suitcase',
            'mini_game_type' => null,
        ]);
        LessonPartContent::create([
            'lesson_part_id' => 9,
            'content_type' => 'quiz',
            'content_data' => 'Match the travel words to their meanings.',
            'mini_game_type' => 'matching',
        ]);

        // A3 - Grammar (lesson_part_id: 10)
        LessonPartContent::create([
            'lesson_part_id' => 10,
            'content_type' => 'text',
            'content_data' => 'Imperatives: Go straight, Turn left',
            'mini_game_type' => null,
        ]);
        LessonPartContent::create([
            'lesson_part_id' => 10,
            'content_type' => 'video',
            'content_data' => 'https://www.youtube.com/watch?v=imperatives',
            'mini_game_type' => null,
        ]);

        // A3 - Listening (lesson_part_id: 11)
        LessonPartContent::create([
            'lesson_part_id' => 11,
            'content_type' => 'audio',
            'content_data' => 'https://audio.example.com/airport.mp3',
            'mini_game_type' => null,
        ]);
        LessonPartContent::create([
            'lesson_part_id' => 11,
            'content_type' => 'quiz',
            'content_data' => 'Listen and follow directions on a map.',
            'mini_game_type' => 'quiz',
        ]);

        // A3 - Speaking (lesson_part_id: 12)
        LessonPartContent::create([
            'lesson_part_id' => 12,
            'content_type' => 'text',
            'content_data' => 'Practice asking for and giving directions.',
            'mini_game_type' => null,
        ]);
        LessonPartContent::create([
            'lesson_part_id' => 12,
            'content_type' => 'mini_game',
            'content_data' => 'Role-play: At the airport check-in counter.',
            'mini_game_type' => 'puzzle',
        ]);

        // A2/6 - Vocabulary (lesson_part_id: 13)
        LessonPartContent::create([
            'lesson_part_id' => 13,
            'content_type' => 'text',
            'content_data' => 'Food: bread, rice, chicken, vegetables',
            'mini_game_type' => null,
        ]);
        LessonPartContent::create([
            'lesson_part_id' => 13,
            'content_type' => 'quiz',
            'content_data' => 'Choose the correct food for breakfast.',
            'mini_game_type' => 'quiz',
        ]);

        // A2/6 - Grammar (lesson_part_id: 14)
        LessonPartContent::create([
            'lesson_part_id' => 14,
            'content_type' => 'text',
            'content_data' => 'Countable and uncountable nouns: some, any, much, many',
            'mini_game_type' => null,
        ]);
        LessonPartContent::create([
            'lesson_part_id' => 14,
            'content_type' => 'video',
            'content_data' => 'https://www.youtube.com/watch?v=countable_uncountable',
            'mini_game_type' => null,
        ]);

        // A2/6 - Listening (lesson_part_id: 15)
        LessonPartContent::create([
            'lesson_part_id' => 15,
            'content_type' => 'audio',
            'content_data' => 'https://audio.example.com/restaurant.mp3',
            'mini_game_type' => null,
        ]);
        LessonPartContent::create([
            'lesson_part_id' => 15,
            'content_type' => 'quiz',
            'content_data' => 'Listen and choose the correct food items.',
            'mini_game_type' => 'quiz',
        ]);

        // A2/6 - Speaking (lesson_part_id: 16)
        LessonPartContent::create([
            'lesson_part_id' => 16,
            'content_type' => 'text',
            'content_data' => 'Order food and drinks in English.',
            'mini_game_type' => null,
        ]);
        LessonPartContent::create([
            'lesson_part_id' => 16,
            'content_type' => 'mini_game',
            'content_data' => 'Role-play: At a café with a friend.',
            'mini_game_type' => 'matching',
        ]);
    }
}
