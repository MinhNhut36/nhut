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
use App\Models\StudentAnswer;

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

    // Hiển thị danh sách khóa học
    public function ShowListCourses()
    {
        $courses = Course::where('status', 'Đang mở lớp')->get();
        $uniqueCourses = $courses->unique('course_name')->values();
        return view('student.Courselist')
            ->with('courses', $uniqueCourses);
    }

    // Hiển thị chi tiết khóa học
    public function ShowDetailCourses(string $Coursename)
    {
        $course = Course::with('lesson')
            ->where('course_name', $Coursename)
            ->join('lessons', 'courses.level', '=', 'lessons.level')
            ->orderBy('lessons.order_index')
            ->select('courses.*')
            ->get();


        $Coursename = Course::with('lesson')->where('course_name', $Coursename)->first();

        return view('student.CourseDetail')
            ->with('courses', $course)
            ->with('CourseName', $Coursename);
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
        $MyCourses = CourseEnrollment::with('course')->where('student_id', $student->student_id)->whereIn('status', [2, 3])->get();
        return view('student.MyCoursesCompleted')->with('enrollment', $MyCourses);
    }

    //danh sách bài học thuộc lesson
    public function ShowListLesson(string $level)
    {
        $studentId = Auth::guard('student')->user()->student_id;
        $lessonParts = LessonPart::with(['myScore']) // chỉ điểm của học sinh hiện tại
            ->where('level', $level)
            ->orderBy('order_index')
            ->get();
        return view('student.Studying', compact('lessonParts'));
    }


    //HỌC SINH VÀO LÀM BÀI TẬP
    public function startExercise(int $lessonPartId)
    {
        $studentId = Auth::guard('student')->user()->student_id;


        // Tạo hoặc lấy phiên làm bài mới
        $attemptNo = LessonPartScore::where('lesson_part_id', $lessonPartId)
            ->where('student_id', $studentId)
            ->max('attempt_no') + 1;    
        

        dd($attemptNo);
        

        $score = LessonPartScore::create([
            'lesson_part_id'  => $lessonPartId,
            'student_id'      => $studentId,
            'course_id'       => 1,              // hoặc lấy từ LessonPart quan hệ
            'attempt_no'      => $attemptNo,
            'total_questions' => 0,
            'correct_answers' => 0,
            'score'           => 0,
            'submit_time'     => null,
        ]);



        dd($score);
        // Lấy câu hỏi single_choice chưa làm
        $contentIds = LessonPartContent::where('lesson_part_id', $lessonPartId)
            ->pluck('contents_id');
        $answeredIds = StudentAnswer::where('lesson_part_score_id', $score->score_id)
            ->pluck('questions_id');
        $question = Question::whereIn('contents_id', $contentIds)
            ->where('question_type', 'single_choice')
            ->whereNotIn('questions_id', $answeredIds)
            ->inRandomOrder()
            ->first();

        return view('student.practice.single_choice', compact('question', 'score'));
    }
}
