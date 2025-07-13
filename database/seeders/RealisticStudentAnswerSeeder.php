<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StudentAnswer;
use App\Models\Student;
use App\Models\Question;
use App\Models\Answer;
use App\Models\CourseEnrollment;
use App\Models\LessonPart;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class RealisticStudentAnswerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        echo "🚀 Starting Realistic Student Answer Seeder...\n";

        // Clear existing data
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        StudentAnswer::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $enrollments = CourseEnrollment::with(['student'])->get();
        echo "📊 Found {$enrollments->count()} enrollments to process\n";

        $processed = 0;
        foreach ($enrollments as $enrollment) {
            $this->processEnrollment($enrollment);
            $processed++;
            if ($processed % 10 === 0) {
                echo "✅ Processed {$processed}/{$enrollments->count()} enrollments\n";
            }
        }

        echo "🎉 Completed! Generated realistic student answers for all enrollments.\n";
    }

    protected function processEnrollment($enrollment)
    {
        $status = $enrollment->status->value;

        // Skip pending enrollments (status 1)
        if ($status === 1) {
            return;
        }

        // Different simulation parameters based on enrollment status
        switch ($status) {
            case 2: // Studying - moderate progress, decent accuracy
                $this->simulate($enrollment, 0.65, 0.3, 0.6);
                break;
            case 3: // Passed - high progress, high accuracy
                $this->simulate($enrollment, 0.85, 0.7, 0.9);
                break;
            case 4: // Failed - moderate progress, low accuracy
                $this->simulate($enrollment, 0.45, 0.5, 0.8);
                break;
        }
    }

    protected function simulate($enrollment, $correctRate, $partMin, $partMax)
    {
        // Get all lesson parts (since we removed level filtering)
        $allParts = LessonPart::all();

        // Randomly select a subset of lesson parts based on student progress
        $partCount = ceil($allParts->count() * rand($partMin * 100, $partMax * 100) / 100);
        $selectedParts = $allParts->random($partCount);

        foreach ($selectedParts as $part) {
            $questions = Question::where('lesson_part_id', $part->lesson_part_id)->get();

            // Answer a subset of questions in this lesson part
            $questionCount = ceil($questions->count() * rand($partMin * 100, $partMax * 100) / 100);
            $selectedQuestions = $questions->random($questionCount);

            foreach ($selectedQuestions as $question) {
                $this->answerQuestion($enrollment, $question, $correctRate);
            }
        }
    }

    protected function answerQuestion($enrollment, Question $question, $correctRate)
    {
        $answers = Answer::where('questions_id', $question->questions_id)->get();

        if ($answers->isEmpty()) {
            return; // Skip if no answers available
        }

        $shouldCorrect = (rand(1, 100) / 100) <= $correctRate;
        $answerText = $this->generateText($question->question_type, $answers, $shouldCorrect);

        // 30% chance of multiple attempts (realistic retry behavior)
        $attempts = (rand(1, 100) <= 30) ? 2 : 1;

        for ($i = 0; $i < $attempts; $i++) {
            $answeredAt = $this->getAnsweredAt($enrollment, $i);

            try {
                StudentAnswer::create([
                    'student_id'   => $enrollment->student_id,
                    'questions_id' => $question->questions_id,
                    'course_id'    => $enrollment->assigned_course_id,
                    'answer_text'  => $answerText,
                    'answered_at'  => $answeredAt
                ]);
            } catch (\Exception $e) {
                // Skip if duplicate or other error
                continue;
            }

            // If first attempt was wrong, improve chance for second attempt
            if ($i === 0 && !$shouldCorrect && $attempts > 1) {
                $shouldCorrect = (rand(1, 100) <= 70); // 70% chance to improve
                $answerText = $this->generateText($question->question_type, $answers, $shouldCorrect);
            }
        }
    }

    protected function generateText($type, $answers, $correct)
    {
        try {
            switch ($type) {
                case 'single_choice':
                    return $this->genSingle($answers, $correct);
                case 'matching':
                    return $this->genMatching($answers, $correct);
                case 'classification':
                    return $this->genClassification($answers, $correct);
                case 'fill_blank':
                    return $this->genFill($answers, $correct);
                case 'arrangement':
                    return $this->genArrangement($answers, $correct);
                case 'image_word':
                    return $this->genImageWord($answers, $correct);
                default:
                    return $this->genSingle($answers, $correct);
            }
        } catch (\Exception $e) {
            // Fallback to simple answer if generation fails
            return $answers->isNotEmpty() ? $answers->random()->answer_text : 'fallback_answer';
        }
    }

    protected function genSingle($answers, $correct)
    {
        if ($correct) {
            $correctAnswer = $answers->where('is_correct', 1)->first();
            return $correctAnswer ? $correctAnswer->answer_text : $answers->random()->answer_text;
        } else {
            $incorrectAnswers = $answers->where('is_correct', 0);
            return $incorrectAnswers->isNotEmpty() ? $incorrectAnswers->random()->answer_text : $answers->random()->answer_text;
        }
    }

    protected function genMatching($answers, $correct)
    {
        if ($correct) {
            $correctAnswer = $answers->where('is_correct', 1)->first();
            if ($correctAnswer) {
                return $correctAnswer->match_key . ':' . $correctAnswer->answer_text;
            }
        } else {
            $incorrectAnswers = $answers->where('is_correct', 0);
            if ($incorrectAnswers->isNotEmpty()) {
                $wrongAnswer = $incorrectAnswers->random();
                return $wrongAnswer->match_key . ':' . $wrongAnswer->answer_text;
            }
        }

        // Fallback
        $fallback = $answers->random();
        return $fallback->match_key . ':' . $fallback->answer_text;
    }

    protected function genClassification($answers, $correct)
    {
        $result = [];
        $wrongCategories = ['noun', 'verb', 'adjective', 'adverb'];

        foreach ($answers as $answer) {
            if ($correct) {
                $category = $answer->match_key ?: 'unknown';
            } else {
                $category = $wrongCategories[array_rand($wrongCategories)];
            }
            $result[] = "{$answer->answer_text}:{$category}";
        }

        return implode(',', $result);
    }

    protected function genFill($answers, $correct)
    {
        if ($correct) {
            $correctAnswer = $answers->where('is_correct', 1)->first();
            return $correctAnswer ? $correctAnswer->answer_text : 'go';
        } else {
            $wrongAnswers = ['wrong', 'xxxx', 'yy', 'goes', 'going', 'went'];
            return $wrongAnswers[array_rand($wrongAnswers)];
        }
    }

    protected function genArrangement($answers, $correct)
    {
        $correctAnswers = $answers->where('is_correct', 1);

        if ($correctAnswers->isEmpty()) {
            return 'I,am,a,student'; // Fallback
        }

        if ($correct) {
            return $correctAnswers->sortBy('order_index')->pluck('answer_text')->implode(',');
        } else {
            // Shuffle the correct order to make it wrong
            return $correctAnswers->shuffle()->pluck('answer_text')->implode(',');
        }
    }

    protected function genImageWord($answers, $correct)
    {
        $correctAnswers = $answers->where('is_correct', 1);

        if ($correctAnswers->isEmpty()) {
            return 'c,a,t'; // Fallback
        }

        if ($correct) {
            return $correctAnswers->sortBy('order_index')->pluck('answer_text')->implode(',');
        } else {
            // Shuffle letters or take partial word
            $shuffled = $correctAnswers->shuffle();
            $count = max(1, min(3, $shuffled->count()));
            return $shuffled->take($count)->pluck('answer_text')->implode(',');
        }
    }

    protected function getAnsweredAt($enrollment, $attemptNumber)
    {
        // Use registration_date if available, otherwise use created_at
        $baseDate = $enrollment->registration_date ?? $enrollment->created_at ?? now();
        $baseTime = Carbon::parse($baseDate)->addDays(rand(1, 30));

        // Add hours for multiple attempts (spread them out)
        return $baseTime->addHours($attemptNumber * rand(1, 24));
    }
}
