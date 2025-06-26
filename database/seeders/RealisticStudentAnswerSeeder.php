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

class RealisticStudentAnswerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Xóa dữ liệu cũ
        StudentAnswer::truncate();

        $enrollments = CourseEnrollment::with(['student', 'course'])->get();

        echo "Found {$enrollments->count()} enrollments\n";

        foreach ($enrollments as $enrollment) {
            echo "Processing enrollment {$enrollment->enrollment_id} with status {$enrollment->status->value}\n";
            $this->createAnswersForEnrollment($enrollment);
        }
    }

    private function createAnswersForEnrollment($enrollment)
    {
        // Logic dựa trên status của enrollment
        $statusValue = $enrollment->status->value;
        switch ($statusValue) {
            case 1: // Pending - không có answers
                echo "  Skipping pending enrollment\n";
                break;
            case 2: // Studying - có một số answers, chưa hoàn thành
                echo "  Creating studying answers\n";
                $this->createStudyingAnswers($enrollment);
                break;
            case 3: // Passed - có đầy đủ answers với tỷ lệ đúng cao
                echo "  Creating passed answers\n";
                $this->createPassedAnswers($enrollment);
                break;
            case 4: // Failed - có đầy đủ answers nhưng tỷ lệ đúng thấp
                echo "  Creating failed answers\n";
                $this->createFailedAnswers($enrollment);
                break;
        }
    }

    private function createStudyingAnswers($enrollment)
    {
        $lessonParts = LessonPart::where('level', $enrollment->course->level)->get();

        // Giảm số lesson parts: chỉ làm 30-60% lesson parts
        $lessonPartsToAnswer = $lessonParts->take(ceil($lessonParts->count() * rand(30, 60) / 100));

        foreach ($lessonPartsToAnswer as $lessonPart) {
            $questions = Question::where('lesson_part_id', $lessonPart->lesson_part_id)->get();

            // Studying students: 30-60% câu hỏi đã trả lời trong lesson part
            $answerPercentage = rand(30, 60) / 100;
            $questionsToAnswer = $questions->take(ceil($questions->count() * $answerPercentage));

            foreach ($questionsToAnswer as $question) {
                $this->createStudentAnswer($enrollment, $question, 0.65); // 65% correct rate
            }
        }
    }

    private function createPassedAnswers($enrollment)
    {
        $lessonParts = LessonPart::where('level', $enrollment->course->level)->get();

        // Passed students: làm 80-100% lesson parts
        $lessonPartsToAnswer = $lessonParts->take(ceil($lessonParts->count() * rand(80, 100) / 100));

        foreach ($lessonPartsToAnswer as $lessonPart) {
            $questions = Question::where('lesson_part_id', $lessonPart->lesson_part_id)->get();

            // Passed students: 70-90% câu hỏi đã trả lời trong lesson part
            $answerPercentage = rand(70, 90) / 100;
            $questionsToAnswer = $questions->take(ceil($questions->count() * $answerPercentage));

            foreach ($questionsToAnswer as $question) {
                $this->createStudentAnswer($enrollment, $question, 0.85); // 85% correct rate
            }
        }
    }

    private function createFailedAnswers($enrollment)
    {
        $lessonParts = LessonPart::where('level', $enrollment->course->level)->get();

        // Failed students: làm 60-90% lesson parts
        $lessonPartsToAnswer = $lessonParts->take(ceil($lessonParts->count() * rand(60, 90) / 100));

        foreach ($lessonPartsToAnswer as $lessonPart) {
            $questions = Question::where('lesson_part_id', $lessonPart->lesson_part_id)->get();

            // Failed students: 50-80% câu hỏi đã trả lời nhưng tỷ lệ đúng thấp
            $answerPercentage = rand(50, 80) / 100;
            $questionsToAnswer = $questions->take(ceil($questions->count() * $answerPercentage));

            foreach ($questionsToAnswer as $question) {
                $this->createStudentAnswer($enrollment, $question, 0.45); // 45% correct rate
            }
        }
    }

    private function createStudentAnswer($enrollment, $question, $correctRate)
    {
        $answers = Answer::where('questions_id', $question->questions_id)->get();
        $correctAnswers = $answers->where('is_correct', 1);
        $incorrectAnswers = $answers->where('is_correct', 0);

        // Determine if answer should be correct based on correctRate
        $shouldBeCorrect = (rand(1, 100) / 100) <= $correctRate;

        // Create answer text based on question type
        $answerText = $this->generateAnswerText($question, $answers, $shouldBeCorrect);

        // Giảm số attempts: chỉ 30% câu hỏi có multiple attempts
        $attempts = (rand(1, 100) <= 30) ? 2 : 1;
        for ($i = 0; $i < $attempts; $i++) {
            $answeredAt = $this->getAnsweredAtTime($enrollment, $i);

            StudentAnswer::create([
                'student_id' => $enrollment->student_id,
                'questions_id' => $question->questions_id,
                'course_id' => $enrollment->assigned_course_id,
                'answer_text' => $answerText,
                'answered_at' => $answeredAt,
            ]);

            // If first attempt was wrong, make subsequent attempts more likely to be correct
            if ($i == 0 && !$shouldBeCorrect) {
                $shouldBeCorrect = rand(1, 100) <= 70; // 70% chance to improve
                $answerText = $this->generateAnswerText($question, $answers, $shouldBeCorrect);
            }
        }
    }

    private function generateAnswerText($question, $answers, $shouldBeCorrect)
    {
        $questionType = $question->question_type;

        switch ($questionType) {
            case 'single_choice':
                return $this->generateSingleChoiceAnswer($answers, $shouldBeCorrect);
            case 'matching':
                return $this->generateMatchingAnswer($answers, $shouldBeCorrect);
            case 'classification':
                return $this->generateClassificationAnswer($answers, $shouldBeCorrect);
            case 'fill_blank':
                return $this->generateFillBlankAnswer($answers, $shouldBeCorrect);
            case 'arrangement':
                return $this->generateArrangementAnswer($answers, $shouldBeCorrect);
            case 'image_word':
                return $this->generateImageWordAnswer($answers, $shouldBeCorrect);
            default:
                return $this->generateSingleChoiceAnswer($answers, $shouldBeCorrect);
        }
    }

    private function generateSingleChoiceAnswer($answers, $shouldBeCorrect)
    {
        if ($shouldBeCorrect) {
            $correctAnswer = $answers->where('is_correct', 1)->first();
            return $correctAnswer ? $correctAnswer->answer_text : $answers->random()->answer_text;
        } else {
            $incorrectAnswers = $answers->where('is_correct', 0);
            return $incorrectAnswers->isNotEmpty() ? $incorrectAnswers->random()->answer_text : $answers->random()->answer_text;
        }
    }

    private function generateMatchingAnswer($answers, $shouldBeCorrect)
    {
        // Format: "apple|táo" (English word | Vietnamese meaning)
        if ($shouldBeCorrect) {
            $correctAnswer = $answers->where('is_correct', 1)->first();
            return $correctAnswer ? $correctAnswer->match_key . '|' . $correctAnswer->answer_text : 'apple|táo';
        } else {
            $incorrectAnswer = $answers->where('is_correct', 0)->random();
            return $incorrectAnswer->match_key . '|' . $incorrectAnswer->answer_text;
        }
    }

    private function generateClassificationAnswer($answers, $shouldBeCorrect)
    {
        // Format: "run|verb,beautiful|adjective" (word|category pairs)
        $result = [];
        foreach ($answers as $answer) {
            if ($shouldBeCorrect) {
                $result[] = $answer->answer_text . '|' . ($answer->group_label ?? 'unknown');
            } else {
                // Mix up categories
                $wrongCategories = ['noun', 'verb', 'adjective', 'adverb'];
                $result[] = $answer->answer_text . '|' . $wrongCategories[array_rand($wrongCategories)];
            }
        }
        return implode(',', $result);
    }

    private function generateFillBlankAnswer($answers, $shouldBeCorrect)
    {
        if ($shouldBeCorrect) {
            $correctAnswer = $answers->where('is_correct', 1)->first();
            return $correctAnswer ? $correctAnswer->answer_text : 'go';
        } else {
            $wrongAnswers = ['goes', 'going', 'went', 'gone'];
            return $wrongAnswers[array_rand($wrongAnswers)];
        }
    }

    private function generateArrangementAnswer($answers, $shouldBeCorrect)
    {
        // Format: "I,am,a,student" (ordered words)
        if ($shouldBeCorrect) {
            $correctAnswers = $answers->where('is_correct', 1)->sortBy('order');
            return $correctAnswers->pluck('answer_text')->implode(',');
        } else {
            // Shuffle the order
            $allAnswers = $answers->where('is_correct', 1)->shuffle();
            return $allAnswers->pluck('answer_text')->implode(',');
        }
    }

    private function generateImageWordAnswer($answers, $shouldBeCorrect)
    {
        // Format: "c,a,t" (ordered letters)
        if ($shouldBeCorrect) {
            $correctAnswers = $answers->where('is_correct', 1)->sortBy('order');
            return $correctAnswers->pluck('answer_text')->implode(',');
        } else {
            // Mix in wrong letters or wrong order
            $allAnswers = $answers->shuffle();
            return $allAnswers->take(3)->pluck('answer_text')->implode(',');
        }
    }

    private function getAnsweredAtTime($enrollment, $attemptNumber)
    {
        $baseTime = Carbon::parse($enrollment->registration_date)->addDays(rand(1, 30));
        return $baseTime->addHours($attemptNumber * rand(1, 24));
    }
}
