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

class StudentController extends Controller
{
   //xử lý đăng nhập của học sinh
   public function Studentlogin(StudentRequest $request)
    {      
        $credentials = $request->only('username', 'password');

        $student = Student::where('username', $credentials['username'])->first();

        if ($student) {
            if ($student->is_status == 0) {
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
        $courses = Course::where('status','Đang mở lớp')->get();
        return view('student.Courselist')
            ->with('courses', $courses);
    }
    // Hiển thị chi tiết khóa học
    public function ShowDetailCourses(int $id)
    {
        $course = Course::with('lesson')->find($id);   
        return view('student.CourseDetail')
            ->with('course', $course);
    }
    // Đăng ký khóa học
    public function CourseRegister(int $id)
    {
        $student = Auth::guard('student')->user();
        $exists = CourseEnrollment::where('student_id', $student->student_id)
        ->where('assigned_course_id', $id)
        ->exists();     
        if ($exists) {
            return redirect()->back()->with('LoiDangKy', 'Bạn đã đăng ký khóa học này rồi!');       
        }
        CourseEnrollment::create([
            'student_id' => $student->student_id,
            'assigned_course_id' => $id,
            'registration_date' => now(),
            'status' => '0',
        ]);
        return redirect()->back()->with('DangKyThanhCong', 'Đăng ký khóa học thành công!');
    }
    //Danh sách các khóa học đang theo học
    public function ListMyCourses()
    {
        $student = Auth::guard('student')->user();
        $MyCourses = CourseEnrollment::with('course')->where('student_id', $student->student_id)->where('status', 0)->get();
        
        return view('student.MyCourses')->with('enrollment', $MyCourses);
    }
    //Danh sách các khóa học đã hoàn thành
    public function CoursesCompleted(){
        $student = Auth::guard('student')->user();
        $MyCourses = CourseEnrollment::with('course')->where('student_id', $student->student_id)->where('status', 1)->get();
        
        return view('student.MyCoursesCompleted')->with('enrollment', $MyCourses);
    }
}
