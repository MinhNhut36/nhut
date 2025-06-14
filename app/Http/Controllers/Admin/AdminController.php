<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function GetStudentList(Request $request)
{
    $query = Student::query();

    // Tìm kiếm theo tên
    if ($request->filled('search')) {
        $query->where('fullname', 'like', '%' . $request->search . '%');
    }

    // Lọc theo trạng thái (1: Hoạt động, 0: Không hoạt động)
    if ($request->filled('status')) {
        $query->where('is_status', $request->status);
    }

    // Lọc theo giới tính (1: Nam, 0: Nữ)
    if ($request->filled('gender')) {
        $query->where('gender', $request->gender);
    }

    $students = $query->paginate(1)->appends($request->all());

    // Nếu trang hiện tại lớn hơn trang cuối, chuyển về trang cuối
    if ($students->currentPage() > $students->lastPage()) {
        return redirect()->route('admin.studentlist', ['page' => $students->lastPage()]);
    }

    return view('admin.StudentList')->with('students', $students);
}
    
}
