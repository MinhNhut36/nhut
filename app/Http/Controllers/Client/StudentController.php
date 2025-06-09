<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\StudentRequest;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use function Psy\debug;

class StudentController extends Controller
{
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

    public function home()
    {
        $student = Auth::guard('student')->user();
        $safeData = [
        'student_id' => $student->student_id,
        'avatar' => $student->avatar,
        'fullname' => $student->fullname,
        'email' => $student->email,
        'gender' => $student->gender,
        'date_of_birth' => $student->date_of_birth,
        'is_status' => $student->is_status,
        'created_at' => $student->created_at,
        'updated_at' => $student->updated_at,       
        ];
        return view('student.home')
            ->with('student', $safeData);
    }
}
