<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\TeacherRequest;
use App\Models\Teacher;


class TeacherController extends Controller
{
    public function Teacherlogin(TeacherRequest $request)
    {
    
    $credentials = $request->only('username', 'password');
    // Kiểm tra tài khoản có tồn tại ở bảng teachers không
    $isTeacher = Teacher::where('username', $credentials['username'])->exists();
    if ($isTeacher) {
        $CheckStatus = Teacher::where('username', $credentials['username'])->first();
          if ($CheckStatus->is_status == 0) {
                return redirect()->back()->withErrors(['TeacherLoginFail' => 'Tài khoản đã bị khóa.']);
            }    
            
        // Thử đăng nhập bằng guard teachers
        if (Auth::guard('teacher')->attempt($credentials)) {          
            return redirect()->route('teacher.home');
        }
    } else {
        // Thử đăng nhập bằng guard web (admin)
        if (Auth::guard('web')->attempt($credentials)) {
            return redirect()->route('admin.home');
        }
        else{
            return redirect()->back()->withErrors(['TeacherLoginFail' => 'Thông tin đăng nhập không chính xác']);
        }
    }
    // Nếu không đăng nhập được
    return redirect()->back()->withErrors(['TeacherLoginFail' => 'Thông tin đăng nhập không chính xác']);
    }
    // hiển thị thông tin của giáo viên
    public function home()
    {
        $teacher = Auth::guard('teacher')->user();
        return view('teacher.home')->with('teacher', $teacher);
    }
    // hiển thị thông tin của admin
     public function AdminHome()
    {
        $admin = Auth::user();
        return view('admin.home')->with('admin', $admin);
    }
    
}
