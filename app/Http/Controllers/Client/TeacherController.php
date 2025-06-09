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
    
    public function home()
    {
        $teacher = Auth::guard('teacher')->user();
        $safeData = [
        'teacher_id' => $teacher->teacher_id,
        'fullname' => $teacher->fullname,
        'email' => $teacher->email,
        'gender' => $teacher->gender,
        'date_of_birth' => $teacher->date_of_birth,
        'is_status' => $teacher->is_status,
        'created_at' => $teacher->created_at,
        'updated_at' => $teacher->updated_at,  
        ];
        return view('teacher.home')->with('teacher', $safeData);
    }
     public function AdminHome()
    {
        $admin = Auth::user();
        return view('admin.home')->with('admin', $admin);
    }
}
