<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Http\Requests\AddStudentRequest;
use App\Enum\personStatus;
use App\Http\Requests\AddCourseRequest;
use App\Models\Teacher;
use App\Http\Requests\AddTeacherRequest;
use App\Models\Course;
use App\Http\Requests\EditCourseResquest;

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
        $total = Student::count();
        $active = Student::where('is_status', 1)->count();
        $inactive = Student::where('is_status', 0)->count();

        return view('admin.StudentList')
            ->with('total', $total)
            ->with('active', $active)
            ->with('inactive', $inactive)
            ->with('students', $students);
    }

    //Thêm sinh viên
    public function AddStudents(AddStudentRequest $request)
    {
        $data = $request->validated();

        $DOB = $data['date_of_birth'];
        $cleanDob = date('dmY', strtotime($DOB));

        $data['username'] = $data['email'];
        $data['password'] = bcrypt($cleanDob);

        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/avatars'), $filename);
            $data['avatar'] = $filename;
        } else {
            $data['avatar'] = 'AvtMacDinh.jpg';
        }
        Student::create($data);
        return redirect()->back()->with('success', 'Thêm sinh viên thành công!');
    }

    //Thay đổi trạng thái sinh viên
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

    //Danh sách quản lý giáo viên có kết hợp tìm kiếm
    public function GetTeacherList(Request $request)
    {

        $query = Teacher::query();

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

        $Teachers = $query->paginate(2)->appends($request->all());
        // Nếu trang hiện tại lớn hơn trang cuối, chuyển về trang cuối
        if ($Teachers->currentPage() > $Teachers->lastPage()) {
            return redirect()->route('admin.teacherlist', ['page' => $Teachers->lastPage()]);
        }
        $total = Teacher::count();
        $active = Teacher::where('is_status', 1)->count();
        $inactive = Teacher::where('is_status', 0)->count();

        return view('admin.TeacherList')
            ->with('total', $total)
            ->with('active', $active)
            ->with('inactive', $inactive)
            ->with('teachers', $Teachers);
    }

    //Thêm giáo viên
    public function AddTeachers(AddTeacherRequest $request)
    {
        $data = $request->validated();

        $DOB = $data['date_of_birth'];
        $cleanDob = date('dmY', strtotime($DOB));

        $data['username'] = $data['email'];
        $data['password'] = bcrypt($cleanDob);

        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/avatars'), $filename);
            $data['avatar'] = $filename;
        } else {
            $data['avatar'] = 'AvtMacDinh.jpg';
        }

        Teacher::create($data);

        return redirect()->back()->with('success', 'Thêm giáo viên thành công!');
    }

    // Thay đổi trạng thái giáo viên 
    public function AjaxToggleStatusTeacher(int $id)
    {
        $teacher = Teacher::find($id);
        if (!$teacher) {
            return response()->json(['success' => false], 404);
        }

        $teacher->is_status = $teacher->is_status === personStatus::ACTIVE
            ? personStatus::INACTIVE
            : personStatus::ACTIVE;
        $teacher->save();
        return response()->json([
            'success' => true,
            'new_status_text' => $teacher->is_status->getStatus(),
            'badge_class' => $teacher->is_status->badgeClass(),
        ]);
    }

    //Danh sách các khóa học có kết hợp tìm kiếm
    public function GetCourseList(Request $request)
    {
        $query = Course::with(['teachers', 'lesson']);

        // Lọc theo tên khóa học
        if ($request->filled('search')) {
            $query->where('course_name', 'like', '%' . $request->search . '%');
        }

        // Lọc theo trình độ
        if ($request->filled('level')) {
            $query->where('level', $request->level);
        }

        // Lọc theo trạng thái
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Lọc theo năm
        if ($request->filled('year')) {
            $query->whereYear('created_at', $request->year);
        }

        $courses = $query->paginate(4)->appends($request->all());

        if ($courses->currentPage() > $courses->lastPage()) {
            return redirect()->route('admin.courses', ['page' => $courses->lastPage()]);
        }

        $levels = Course::select('level')->distinct()->pluck('level');
        $statuses = Course::select('status')->distinct()->pluck('status');
        $years = Course::selectRaw('YEAR(starts_date) as year')->distinct()->pluck('year');

        return view('admin.CourseManagement', compact('courses', 'levels', 'statuses', 'years'));
    }


    // Tạo khóa học mới
    public function CreateCourse(AddCourseRequest $request)
    {
        Course::create([
            'course_name' => $request->course_name,
            'level' => $request->level,
            'year' => $request->year,
            'description' => $request->description,
            'starts_date' => $request->starts_date,
            'status' => 'Chờ xác thực',
        ]);
        return redirect()->back()->with('success', 'Khóa học đã được tạo thành công!');
    }
    // cập nhật thông tin khóa học
    public function CourseUpdate(EditCourseResquest $request, $id)
    {
        $course = Course::findOrFail($id);

        $course->update($request->validated());

        return redirect()->back()->with('success', 'Cập nhật khóa học thành công!');
    }
    // xóa khóa học 
    public function CourseDelete($id)
    {
        $course = Course::findOrFail($id);
        
        $course->delete();

        return redirect()->back()->with('success', 'Xóa khóa học thành công!');
    }
}
