<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\StudentRequest;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;
use function Psy\debug;
use App\Models\CourseEnrollment;
use App\Models\Lesson;
use App\Models\LessonPart;
use App\Models\LessonPartScore;
use App\Models\Question;
use App\Models\LessonPartContent;
use App\Models\Answer;
use App\Models\Notification;
use App\Models\StudentAnswer;
use App\Models\StudentProgress;
use Illuminate\Support\Facades\Log;
use App\Models\ExamResult;
use App\Models\ClassPost;
use App\Models\ClassPostComment;

class StudentController extends Controller
{
    //xử lý đăng nhập của học sinh
    public function Studentlogin(StudentRequest $request)
    {
        $credentials = $request->only('username', 'password');

        $student = Student::where('username', $credentials['username'])->first();

        if ($student) {
            if ($student->is_status->value == 0) {
                return redirect()->back()->withErrors(['StudentLoginFail' => 'Tài khoản đã bị khóa.']);
            }
            // Nếu tài khoản hoạt động, đăng nhập
            if (Auth::guard('student')->attempt($credentials)) {


                return redirect()->route('student.home');
            }
        }

        // Sai username hoặc password
        return redirect()->back()->withErrors(['StudentLoginFail' => 'Sai tên đăng nhập hoặc mật khẩu']);
    }

    // Hiển thị thông tin trang chủ của học sinh
    public function home()
    {
        $student = Auth::guard('student')->user();

        return view('student.home')
            ->with('student', $student);
    }

    // Hiển thị danh sách trình độ
    public function ShowListCourses()
    {
        $lessons = Lesson::orderBy('order_index')->get();
        return view('student.CourseList')
            ->with('lessons', $lessons);
    }

    // Hiển thị chi tiết các khóa học đang mở theo level 
    public function ShowDetailCourses(string $level)
    {
        $courses = Course::where('level', $level)->where('status', 'Đang mở lớp')->get();
        return view('student.CourseDetail')
            ->with('courses', $courses)
            ->with('level', $level);
    }

    // Đăng ký khóa học
    public function CourseRegister(int $id)
    {
        $student = Auth::guard('student')->user();

        //Kiểm tra xem sinh viên đã đăng ký khóa học này chưa
        $exists = CourseEnrollment::where('student_id', $student->student_id)
            ->where('assigned_course_id', $id)
            ->exists();
        if ($exists) {
            return redirect()->back()->with('LoiDangKy', 'Bạn đã đăng ký khóa học này rồi!');
        }

        //Kiểm tra xem trình độ khóa học sinh viên đăng ký đã có hay chưa
        $courseLevel = Course::find($id)?->level;
        $hasSameLevelStudying = CourseEnrollment::where('student_id', $student->student_id)
            ->where('status', 1) // Trạng thái đang học
            ->whereHas('course', function ($query) use ($courseLevel) {
                $query->where('level', $courseLevel);
            })
            ->exists();


        if ($hasSameLevelStudying) {
            return redirect()->back()->with('LoiDangKy', 'Bạn đã đăng ký trình độ "' . $courseLevel . '" này rồi!');
        }


        CourseEnrollment::create([
            'student_id' => $student->student_id,
            'assigned_course_id' => $id,
            'registration_date' => now(),
            'status' => '1',
        ]);
        return redirect()->back()->with('DangKyThanhCong', 'Đăng ký khóa học thành công!');
    }

    //Danh sách các khóa học đang theo học
    public function ListMyCourses()
    {
        $student = Auth::guard('student')->user();
        $MyCourses = CourseEnrollment::with('course')->where('student_id', $student->student_id)->where('status', 1)->get();
        return view('student.MyCourses')->with('enrollment', $MyCourses);
    }

    //Danh sách các khóa học đã hoàn thành
    public function CoursesCompleted()
    {
        $student = Auth::guard('student')->user();
        $ExamResults = ExamResult::with('course')
            ->where('student_id', $student->student_id)
            ->orderByDesc('exam_date')
            ->paginate(10);

        return view('student.MyCoursesCompleted')->with('examResults', $ExamResults);
    }

    //danh sách bài học thuộc lesson
    public function ShowListLesson(int $course_id)
    {

        $studentId = Auth::guard('student')->user()->student_id;

        // B1: Lấy level của khóa học
        $course = Course::findOrFail($course_id);
        $level = $course->level;

        // B2: Lấy danh sách lesson_part theo level đó
        $lessonParts = LessonPart::with([
            // nếu muốn hiển thị tên bài học
            'myScore' => function ($query) use ($studentId, $course_id) {
                $query->where('student_id', $studentId)
                    ->where('course_id', $course_id)
                    ->with('StudentProgcess');
            }
        ])
            ->where('level', $level)
            ->orderBy('order_index')
            ->get();


        return view('student.Studying')
            ->with('lessonParts', $lessonParts)
            ->with('courseId', $course_id);
    }

    //bảng tin
    public function board(int $course_id)
    {
        $student = Auth::guard('student')->user();
        // Lấy danh sách bài viết của giáo viên đó trong khóa học này
        $posts = ClassPost::with([
            'teacher',                  // người tạo bài viết
            'comments',      // người tạo bình luận (student/teacher)
        ])
            ->where('course_id', $course_id)
            ->where('status', 1)
            ->orderByDesc('created_at')
            ->get();
        return view('student.board')
            ->with('posts', $posts)
            ->with('courseId', $course_id)
            ->with('student_id', $student->student_id);
    }

    // Học sinh viết phản hồi
    public function UpPostComment(Request $request, int $course_id)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $student = Auth::guard('student')->user();
        ClassPostComment::create([
            'post_id' => $request->postId,
            'student_id' => $student->student_id,
            'content' => $request->content,
            'status' => 1, // Trạng thái bình luận
        ]);

        return redirect()->back()->with('courseId', $course_id);
    }

    // Xóa bình luận của học sinh
    public function DeleteComment(ClassPostComment $comment)
    {
        $student = Auth::guard('student')->user();

        // Kiểm tra xem bình luận có thuộc về học sinh này không
        if ($comment->student_id !== $student->student_id) {
            return redirect()->back()->withErrors(['error' => 'Bạn không có quyền xóa bình luận này.']);
        }

        // Xóa bình luận
        $comment->delete();

        return redirect()->back()->with('success', 'Bình luận đã được xóa thành công.');
    }

    //HỌC SINH VÀO LÀM BÀI TẬP
    public function startExercise(int $lessonPartId)
    {
        $studentId = Auth::guard('student')->user()->student_id;

        // 1. Tạo phiên làm bài mới
        $lastAttempt = LessonPartScore::where('lesson_part_id', $lessonPartId)
            ->where('student_id', $studentId)
            ->max('attempt_no') ?? 0;

        // 2. Đếm số câu hỏi 
        $totalQuestions = Question::where('lesson_part_id', $lessonPartId)->count();

        // 3. tìm khóa học học sinh đang học 
        $level = LessonPart::with('lesson')->findOrFail($lessonPartId);
        $courseIds = Course::where('level', $level->level)
            ->pluck('course_id')    // chỉ lấy cột course_id
            ->toArray();

        $courseId = CourseEnrollment::with('course')->where('student_id', $studentId)->whereIn('assigned_course_id', $courseIds)->where('status', 1)->value('assigned_course_id');


        $score = LessonPartScore::create([
            'lesson_part_id'  => $lessonPartId,
            'student_id'      => $studentId,
            'course_id'       => $courseId,
            'attempt_no'      => $lastAttempt + 1,
            'total_questions' => $totalQuestions,
            'correct_answers' => 0,
            'score'           => 0,
            'submit_time'     => null,
        ]);

        // 5. Lấy danh sách câu hỏi dạng single_choice
        $questions = Question::with('answers')->where('lesson_part_id', $lessonPartId)
            ->whereIn('question_type', ['single_choice', 'fill_blank', 'matching', 'arrangement'])
            ->orderBy('order_index')
            ->get();

        foreach ($questions as $question) {
            if ($question->question_type === 'single_choice') {
                // Trắc nghiệm: đảo toàn bộ đáp án
                $question->shuffled_answers = $question->answers->shuffle();
            } elseif ($question->question_type === 'matching') {
                // Matching: chỉ đảo phần answer_text (bên trái)
                $texts = $question->answers->where('answer_text', '!=', '')->unique('answer_text')->shuffle();
                $images = $question->answers->where('media_url', '!=', '')->unique('match_key')->shuffle();

                // Gán riêng ra 2 nhóm cho view xử lý
                $question->shuffled_texts = $texts->values();   // danh sách text đã shuffle
                $question->shuffled_images = $images->values(); // giữ nguyên hình ảnh
            } else {
                // Các loại câu hỏi khác (fill_blank, ...)
                $question->shuffled_answers = $question->answers;
            }
        }

        // 3. Trả về view chỉ với danh sách câu hỏi và score_id
        return view('student.practice.exercise', [
            'questions' => $questions,
            'scoreId'   => $score->score_id,
            'lessonPartId' => $lessonPartId,
            'totalQuestions' => $totalQuestions,
            'courseId' => $courseId,
            'level' => $level->level,
        ]);
    }

    // sinh viên nộp câu hỏi
    public function submitAnswer(Request $request, int $lessonPartId)
    {
        $studentId = Auth::guard('student')->user()->student_id;
        $answers = $request->input('answers', []);
        $courseId = $request->input('course_id');
        $scoreId = $request->score_id;

        $results = [];

        // Tổng số câu hỏi trong lesson part và điểm cho mỗi câu
        $totalQuestions = Question::where('lesson_part_id', $lessonPartId)->count();
        $scorePerQuestion = $totalQuestions > 0 ? 10 / $totalQuestions : 0;

        $correctCount = 0;

        foreach ($answers as $questionId => $answerValue) {
            $question = Question::with('answers')->find($questionId);
            if (!$question) continue;

            $questionType = $question->question_type instanceof \BackedEnum
                ? $question->question_type->value
                : $question->question_type;

            $isCorrect = false;
            $correctAnswer = null;
            $answerText = '';
            $feedback = '';
            $wordResults = [];

            if ($questionType === 'single_choice') {
                $correctAnswer = $question->answers->where('is_correct', 1)->first();
                $isCorrect = $answerValue == $correctAnswer?->answers_id;
                $userAnswer = Answer::find($answerValue);
                $answerText = $userAnswer?->answer_text ?? '';

                $feedback = $isCorrect
                    ? ($userAnswer->feedback ?? '✅ Chính xác! Làm tốt lắm 👏')
                    : ($correctAnswer->feedback ?? '❌ Hãy xem lại, đây là một gợi ý 📘');
            } elseif ($questionType === 'fill_blank') {
                $correctAnswer = $question->answers->first();
                $studentText = trim(mb_strtolower($answerValue));
                $correctText = trim(mb_strtolower($correctAnswer->answer_text));

                $isCorrect = $studentText === $correctText;
                $answerText = $answerValue;

                $feedback = $isCorrect
                    ? ($correctAnswer->feedback ?? '✅ Điền chính xác!')
                    : ($correctAnswer->feedback ?? '❌ Gợi ý: hãy kiểm tra lại chính tả hoặc ngữ nghĩa.');
            } elseif ($questionType === 'matching' && is_array($answerValue)) {
                $pairs = $question->answers;

                $totalPairs = count($answerValue);
                $correctPairs = 0;

                foreach ($answerValue as $textKey => $imageKey) {
                    $expected = $pairs->first(function ($item) use ($textKey) {
                        return mb_strtolower($item->answer_text) === mb_strtolower($textKey);
                    });

                    if ($expected && $expected->match_key === $imageKey) {
                        $correctPairs++;
                    }
                }

                $isCorrect = ($correctPairs === $totalPairs);
                $feedback = $isCorrect
                    ? '✅ Bạn đã ghép đúng tất cả các cặp!'
                    : "❌ Bạn ghép đúng $correctPairs / $totalPairs cặp.";

                $answerText = json_encode($answerValue);
            } elseif ($questionType === 'arrangement' && is_array($answerValue)) {
                $normalize = fn($w) => trim(mb_strtolower(str_replace('’', "'", $w)));

                $correctAnswers = $question->answers->sortBy('order_index')->values();
                $correctWords = $correctAnswers->pluck('answer_text')->map($normalize)->values();
                $studentWords = collect($answerValue)->map($normalize)->values();

                $correctMap = [];
                $correctWordCount = 0;

                for ($i = 0; $i < count($correctWords); $i++) {
                    $expectedWord = $correctWords[$i];
                    $studentWord = $studentWords[$i] ?? null;
                    $answerId = $correctAnswers[$i]->answers_id;

                    $isWordCorrect = $studentWord === $expectedWord;

                    $wordResults[$answerId] = ['is_correct' => $isWordCorrect];
                    $correctMap[] = $isWordCorrect;

                    if ($isWordCorrect) {
                        $correctWordCount++;
                    }
                }

                $isCorrect = $correctWordCount === count($correctWords);
                $feedback = $isCorrect
                    ? '✅ Bạn đã sắp xếp chính xác câu!'
                    : "❌ Bạn đã sắp đúng $correctWordCount / " . count($correctWords) . " từ.";

                $answerText = implode(' ', $answerValue);
            }

            // Lưu câu trả lời của học sinh
            StudentAnswer::create([
                'student_id'   => $studentId,
                'questions_id' => $questionId,
                'course_id'    => $courseId,
                'answer_text'  => $answerText === '' ? null : $answerText,
                'answered_at'  => now(),
            ]);

            if ($isCorrect) {
                $correctCount++;
            }

            $results[$questionId] = [
                'your_answer'    => $answerValue,
                'correct_answer' => $questionType === 'single_choice'
                    ? $correctAnswer?->answers_id
                    : ($questionType === 'fill_blank'
                        ? $correctAnswer?->answer_text
                        : null),
                'is_correct'     => $isCorrect,
                'feedback'       => $feedback,
            ];

            // Gửi word_results nếu là arrangement
            if ($questionType === 'arrangement') {
                $results[$questionId]['word_results'] = $wordResults;
            }
        }

        // Tính tổng điểm
        $finalScore = round($correctCount * $scorePerQuestion, 1);

        // Cập nhật bảng điểm
        LessonPartScore::where('score_id', $scoreId)->update([
            'correct_answers' => $correctCount,
            'score' => $finalScore,
            'submit_time' => now(),
        ]);

        // Cập nhật tiến độ học
        $isCompleted = $finalScore >= 7;
        StudentProgress::updateOrCreate(
            ['score_id' => $scoreId],
            [
                'completion_status' => $isCompleted,
                'last_updated' => now(),
            ]
        );

        return response()->json([
            'success' => true,
            'results' => $results,
            'correct_count'   => $correctCount,
            'total_questions' => $totalQuestions,
            'final_score'     => $finalScore,
        ]);
    }
}
