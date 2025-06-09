<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\StudentRequest;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;
use function Psy\debug;

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
}
