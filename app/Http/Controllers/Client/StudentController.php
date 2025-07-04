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
        return view('student.Courselist')
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
        $MyCourses = CourseEnrollment::with('course')->where('student_id', $student->student_id)->whereIn('status', [2, 3])->paginate(10);
        
        return view('student.MyCoursesCompleted')->with('enrollment', $MyCourses);
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
        return view('student.Studying', compact('lessonParts'));
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
            'course_id'       => $courseId,              // hoặc lấy đúng course_id từ lesson_part quan hệ
            'attempt_no'      => $lastAttempt + 1,
            'total_questions' => $totalQuestions,
            'correct_answers' => 0,
            'score'           => 0,
            'submit_time'     => null,
        ]);

        // 5. Lấy danh sách câu hỏi dạng single_choice
        $questions = Question::with('answers')->where('lesson_part_id', $lessonPartId)
            ->whereIn('question_type', ['single_choice', 'fill_blank'])
            ->orderBy('order_index')
            ->get();

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
        log::debug("đây nè");
        $studentId = Auth::guard('student')->user()->student_id;
        $answers = $request->input('answers', []);
        $courseId = $request->input('course_id');
        $scoreId = $request->score_id;

        $results = [];

        // Tổng số câu hỏi trong lesson part và tổng điểm cho từng câu 
        $totalQuestions = Question::where('lesson_part_id', $lessonPartId)->count();
        $scorePerQuestion = $totalQuestions > 0 ? 10 / $totalQuestions : 0;

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

            if ($questionType === 'single_choice') {
                $correctAnswer = $question->answers->where('is_correct', 1)->first();
                $isCorrect = $answerValue == $correctAnswer?->answers_id;
                $userAnswer = Answer::find($answerValue);
                $answerText = $userAnswer?->answer_text ?? '';

                $feedback = $isCorrect
                    ? ($userAnswer->feedback ?? '✅ Chính xác! Làm tốt lắm 👏')
                    : ($correctAnswer->feedback ?? '❌ Hãy xem lại, đây là một gợi ý 📘');
            } elseif ($questionType === 'fill_blank') {
                $correctAnswer = $question->answers->first(); // chứa đáp án đúng
                $studentText = trim(mb_strtolower($answerValue));
                $correctText = trim(mb_strtolower($correctAnswer->answer_text));

                $isCorrect = $studentText === $correctText;
                $answerText = $answerValue;

                $feedback = $isCorrect
                    ? ($correctAnswer->feedback ?? '✅ Điền chính xác!')
                    : ($correctAnswer->feedback ?? '❌ Gợi ý: hãy kiểm tra lại chính tả hoặc ngữ nghĩa.');
            }

            // Lưu kết quả
            StudentAnswer::create([
                'student_id' => $studentId,
                'questions_id' => $questionId,
                'course_id' => $courseId,
                'answer_text' => $answerText === '' ? null : $answerText,
                'answered_at' => now(),
            ]);

            if ($isCorrect) {
                LessonPartScore::where('score_id', $scoreId)->increment('correct_answers');
                LessonPartScore::where('score_id', $scoreId)->increment('score', $scorePerQuestion);
            }

            $results[$questionId] = [
                'your_answer'     => $answerValue,
                'correct_answer'  => $questionType === 'single_choice'
                    ? $correctAnswer?->answers_id
                    : $correctAnswer?->answer_text,
                'is_correct'      => $isCorrect,
                'feedback'        => $feedback,
            ];
        }

        LessonPartScore::find($scoreId)?->update(['submit_time' => now()]);

        // Cập nhật tiến độ học
        $lessonScore = LessonPartScore::find($scoreId);
        $isCompleted = $lessonScore && $lessonScore->score >= 7;

        StudentProgress::updateOrCreate(
            ['score_id' => $scoreId],
            [
                'completion_status' => $isCompleted,
                'last_updated' => now(),
            ]
        );

        return response()->json([
            'success' => true,
            'results' => $results
        ]);
    }
}
