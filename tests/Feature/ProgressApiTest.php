<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Student;
use App\Models\Lesson;
use App\Models\LessonPart;
use App\Models\Question;
use App\Models\StudentAnswer;
use App\Models\LessonPartScore;
use App\Models\LessonPartContent;

class ProgressApiTest extends TestCase
{
    use RefreshDatabase;

    protected $student;
    protected $lesson;
    protected $lessonPart1;
    protected $lessonPart2;
    protected $questions1;
    protected $questions2;

    protected function setUp(): void
    {
        parent::setUp();

        // Tạo test data
        $this->createTestData();
    }

    private function createTestData()
    {
        // Tạo student
        $this->student = Student::create([
            'student_id' => 1,
            'fullname' => 'Test Student',
            'username' => 'teststudent',
            'password' => bcrypt('password'),
            'email' => 'test@example.com',
            'date_of_birth' => '2000-01-01',
            'gender' => 1,
            'is_status' => 1
        ]);

        // Tạo lesson
        $this->lesson = Lesson::create([
            'level' => 'A1',
            'title' => 'Basic English',
            'description' => 'Test lesson',
            'order_index' => 1
        ]);

        // Tạo lesson parts
        $this->lessonPart1 = LessonPart::create([
            'lesson_part_id' => 1,
            'level' => 'A1',
            'part_type' => 'Vocabulary',
            'content' => 'Test content',
            'order_index' => 1
        ]);

        $this->lessonPart2 = LessonPart::create([
            'lesson_part_id' => 2,
            'level' => 'A1',
            'part_type' => 'Grammar',
            'content' => 'Test content',
            'order_index' => 2
        ]);

        // Tạo lesson part contents
        LessonPartContent::create([
            'contents_id' => 1,
            'lesson_part_id' => 1,
            'content_type' => 'text',
            'content_data' => 'Test content data'
        ]);

        LessonPartContent::create([
            'contents_id' => 2,
            'lesson_part_id' => 2,
            'content_type' => 'text',
            'content_data' => 'Test content data'
        ]);

        // Tạo questions cho lesson part 1
        $this->questions1 = collect();
        for ($i = 1; $i <= 10; $i++) {
            $question = Question::create([
                'contents_id' => 1,
                'question_text' => "Test question {$i}",
                'question_type' => 'single_choice',
                'order_index' => $i
            ]);
            $this->questions1->push($question);
        }

        // Tạo questions cho lesson part 2
        $this->questions2 = collect();
        for ($i = 11; $i <= 18; $i++) {
            $question = Question::create([
                'contents_id' => 2,
                'question_text' => "Test question {$i}",
                'question_type' => 'single_choice',
                'order_index' => $i - 10
            ]);
            $this->questions2->push($question);
        }

        // Tạo student answers cho lesson part 1 (hoàn thành)
        foreach ($this->questions1 as $question) {
            StudentAnswer::create([
                'student_id' => 1,
                'questions_id' => $question->question_id,
                'selected_answer' => 'A',
                'is_correct' => 1,
                'submit_time' => now()
            ]);
        }

        // Tạo student answers cho lesson part 2 (chưa hoàn thành)
        foreach ($this->questions2->take(6) as $question) {
            StudentAnswer::create([
                'student_id' => 1,
                'questions_id' => $question->question_id,
                'selected_answer' => 'A',
                'is_correct' => 1,
                'submit_time' => now()
            ]);
        }

        // Tạo scores
        LessonPartScore::create([
            'student_id' => 1,
            'lesson_part_id' => 1,
            'course_id' => 1,
            'attempt_no' => 1,
            'score' => 80,
            'total_questions' => 10,
            'correct_answers' => 8, // 80% đúng (>= 70%)
            'submit_time' => now()
        ]);

        LessonPartScore::create([
            'student_id' => 1,
            'lesson_part_id' => 2,
            'course_id' => 1,
            'attempt_no' => 1,
            'score' => 50,
            'total_questions' => 8,
            'correct_answers' => 4, // 50% đúng (< 70%)
            'submit_time' => now()
        ]);
    }

    /** @test */
    public function test_get_lesson_part_progress_completed()
    {
        $response = $this->getJson('/api/progress/lesson-part/1/student/1');

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'data' => [
                        'student_id' => 1,
                        'lesson_part_id' => 1,
                        'total_questions' => 10,
                        'answered_questions' => 10,
                        'correct_answers' => 8,
                        'progress_percentage' => 100.0,
                        'is_completed' => true,
                        'required_correct_answers' => 7
                    ]
                ]);
    }

    /** @test */
    public function test_get_lesson_part_progress_not_completed()
    {
        $response = $this->getJson('/api/progress/lesson-part/2/student/1');

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'data' => [
                        'student_id' => 1,
                        'lesson_part_id' => 2,
                        'total_questions' => 8,
                        'answered_questions' => 6,
                        'correct_answers' => 4,
                        'progress_percentage' => 75.0, // (6/8)*100 = 75% nhưng chưa hoàn thành
                        'is_completed' => false,
                        'required_correct_answers' => 6
                    ]
                ]);
    }

    /** @test */
    public function test_get_lesson_progress()
    {
        $response = $this->getJson('/api/progress/lesson/A1/student/1');

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'data' => [
                        'student_id' => 1,
                        'lesson_level' => 'A1',
                        'lesson_title' => 'Basic English',
                        'total_parts' => 2,
                        'completed_parts' => 1, // Chỉ lesson part 1 hoàn thành
                        'progress_percentage' => 50.0, // 1/2 * 100
                        'is_completed' => false
                    ]
                ]);
    }

    /** @test */
    public function test_get_student_overall_progress()
    {
        $response = $this->getJson('/api/progress/student/1/overall');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'data' => [
                        'student_id',
                        'student_name',
                        'total_lessons',
                        'completed_lessons',
                        'overall_progress_percentage',
                        'lessons_progress' => [
                            '*' => [
                                'lesson_level',
                                'lesson_title',
                                'total_parts',
                                'completed_parts',
                                'progress_percentage',
                                'is_completed'
                            ]
                        ]
                    ]
                ]);
    }

    /** @test */
    public function test_lesson_part_progress_not_found()
    {
        $response = $this->getJson('/api/progress/lesson-part/999/student/1');

        $response->assertStatus(404)
                ->assertJson([
                    'success' => false,
                    'message' => 'Student hoặc Lesson Part không tồn tại'
                ]);
    }

    /** @test */
    public function test_student_not_found()
    {
        $response = $this->getJson('/api/progress/lesson-part/1/student/999');

        $response->assertStatus(404)
                ->assertJson([
                    'success' => false,
                    'message' => 'Student hoặc Lesson Part không tồn tại'
                ]);
    }



    /** @test */
    public function test_progress_calculation_formula()
    {
        // Test case: 10 câu hỏi, trả lời 10 câu, đúng 6 câu (60% < 70%)
        // Kết quả: chưa hoàn thành, progress = 99% (vì đã trả lời hết nhưng chưa đủ 70% đúng)
        
        // Tạo lesson part mới
        LessonPart::create([
            'lesson_part_id' => 3,
            'level' => 'A1',
            'part_type' => 'Test',
            'content' => 'Test content',
            'order_index' => 3
        ]);

        LessonPartContent::create([
            'contents_id' => 3,
            'lesson_part_id' => 3,
            'content_type' => 'text',
            'content_data' => 'Test content data'
        ]);

        $questions = collect();
        for ($i = 19; $i <= 28; $i++) {
            $question = Question::create([
                'contents_id' => 3,
                'question_text' => "Test question {$i}",
                'question_type' => 'single_choice',
                'order_index' => $i - 18
            ]);
            $questions->push($question);
        }

        // Trả lời hết 10 câu
        foreach ($questions as $index => $question) {
            StudentAnswer::create([
                'student_id' => 1,
                'questions_id' => $question->question_id,
                'selected_answer' => 'A',
                'is_correct' => $index < 6 ? 1 : 0, // 6 câu đầu đúng, 4 câu sau sai
                'submit_time' => now()
            ]);
        }

        // Chỉ đúng 6 câu (60%)
        LessonPartScore::create([
            'student_id' => 1,
            'lesson_part_id' => 3,
            'course_id' => 1,
            'attempt_no' => 1,
            'score' => 60,
            'total_questions' => 10,
            'correct_answers' => 6,
            'submit_time' => now()
        ]);

        $response = $this->getJson('/api/progress/lesson-part/3/student/1');

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'data' => [
                        'total_questions' => 10,
                        'answered_questions' => 10,
                        'correct_answers' => 6,
                        'progress_percentage' => 99.0, // Chưa hoàn thành nên tối đa 99%
                        'is_completed' => false, // Chưa đủ 70% đúng
                        'required_correct_answers' => 7
                    ]
                ]);
    }
}
