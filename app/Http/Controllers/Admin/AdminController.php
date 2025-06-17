<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Http\Requests\AddStudentRequest;
use App\Enum\personStatus;

class AdminController extends Controller
{
    //Lấy ra danh sách sinh viên có kết hợp tìm kiếm
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

        $students = $query->paginate(2)->appends($request->all());

        // Nếu trang hiện tại lớn hơn trang cuối, chuyển về trang cuối
        if ($students->currentPage() > $students->lastPage()) {
            return redirect()->route('admin.studentlist', ['page' => $students->lastPage()]);
        }

        return view('admin.StudentList')->with('students', $students);
    }
    //Thêm sinh viên
    public function AddStudents(AddStudentRequest $request)
    {
        $data = $request->validated();
        $data['password'] = bcrypt($data['username']);
        // Xử lý file avatar
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');

            // Lấy thông tin người dùng
            $name = $data['fullname'];
            $DOB = $data['date_of_birth'];

            // Làm sạch tên file (loại bỏ dấu cách, ký tự đặc biệt nếu cần)
            $cleanUsername = preg_replace('/[^a-zA-Z0-9]/', '', $name);
            $cleanDob = str_replace('-', '', $DOB); // thành 20250614

            // Tạo tên file
            $filename = $cleanUsername . '_' . $cleanDob . '.' . $file->getClientOriginalExtension();

            // Di chuyển file
            $file->move(public_path('uploads/avatars'), $filename);

            // Lưu đường dẫn vào DB
            $data['avatar'] = 'uploads/avatars/' . $filename;
        }

        Student::create($data);

        return redirect()->back()->with('success', 'Thêm sinh viên thành công!');
    }
    //Thay đổi trạng thái
    public function AjaxToggleStatus(int $id)
    {
        $student = Student::find($id);
        if (!$student) {
            return response()->json(['success' => false], 404);
        }

        $student->is_status = $student->is_status === personStatus::ACTIVE
            ? personStatus::INACTIVE
            : personStatus::ACTIVE;
        $student->save();
        return response()->json([
            'success' => true,
            'new_status_text' => $student->is_status->getStatus(),
            'badge_class' => $student->is_status->badgeClass(),
        ]);
    }
}
