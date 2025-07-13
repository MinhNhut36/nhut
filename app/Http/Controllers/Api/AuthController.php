<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Kiểm tra đăng nhập cho học sinh
     * GET /api/StudentDN/{taikhoan}/{matkhau}
     */
    public function kiemTraDangNhap($taikhoan, $matkhau)
    {
        try {
            $student = Student::where('username', $taikhoan)->first();

            if (!$student) {
                return response()->json([], 404);
            }

            // Kiểm tra mật khẩu
            if (!Hash::check($matkhau, $student->password)) {
                return response()->json([], 401);
            }

            // Kiểm tra trạng thái tài khoản
            if ($student->is_status == 0) {
                return response()->json([], 403); // Tài khoản bị khóa
            }

            // Trả về thông tin học sinh
            return response()->json([$student], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Lỗi server',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Kiểm tra đăng nhập cho giảng viên
     * GET /api/TeacherDN/{taikhoan}/{matkhau}
     */
    public function kiemTraDangNhapTeacher($taikhoan, $matkhau)
    {
        try {
            $teacher = Teacher::where('username', $taikhoan)->first();

            if (!$teacher) {
                return response()->json([], 404);
            }

            // Kiểm tra mật khẩu
            if (!Hash::check($matkhau, $teacher->password)) {
                return response()->json([], 401);
            }

            // Kiểm tra trạng thái tài khoản
            if ($teacher->is_status == 0) {
                return response()->json([], 403); // Tài khoản bị khóa
            }

            // Trả về thông tin giảng viên
            return response()->json([$teacher], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Lỗi server',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
