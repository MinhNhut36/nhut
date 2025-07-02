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
use App\Models\TeacherCourseAssignment;
use App\Enum\courseStatus;
use App\Models\Lesson;
use App\Http\Requests\AddLevelRequest;
use App\Models\LessonPart;
use App\Http\Requests\UpdateLessonRequest;
use App\Http\Requests\UpdateLessonPartRequest;

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

        $courses = $query->orderByRaw("FIELD(status, 'Chờ xác thực', 'Đang mở lớp', 'Đã hoàn thành')")->paginate(4)->appends($request->all());

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
    // Phân công giảng dạy
    public function showUnassignedCourses()
    {
        // Lấy các khóa học có trạng thái là "Chờ xác thực" hoặc "Đang mở lớp"
        $courses = Course::whereIn('status', [
            courseStatus::verifying->value,
            courseStatus::IsOpening->value,
        ])
            ->orderByRaw("
        CASE 
            WHEN status = ? THEN 1 
            WHEN status = ? THEN 2 
            ELSE 3 
        END
    ", [
                courseStatus::verifying->value,
                courseStatus::IsOpening->value,
            ])
            ->get();

        // Lấy tất cả giáo viên đang hoạt động
        $teachers = Teacher::where('is_status', 1)->get();

        // Lấy danh sách giáo viên đã được phân công cho các khóa học này
        $assignments = TeacherCourseAssignment::with('teacher')
            ->whereIn('course_id', $courses->pluck('course_id'))
            ->get();

        // Gom theo course_id để hiển thị trong view
        $courseAssignments = [];
        foreach ($assignments as $assignment) {
            $courseAssignments[$assignment->course_id][] = [
                'teacher' => $assignment->teacher,
                'position' => $assignment->role, // 'Main Teacher' hoặc 'Assistant Teacher'
            ];
        }
        return view('admin.TeachingAssignments', [
            'unassignedCourses' => $courses,
            'teachers' => $teachers,
            'courseAssignments' => $courseAssignments,
        ]);
    }
    // Phân công giáo viên
    public function assignTeacher(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,course_id',
            'teacher_id' => 'required|exists:teachers,teacher_id',
            'role' => 'required|in:Main Teacher,Assistant Teacher',
        ]);

        // Kiểm tra nếu giáo viên đã được phân công vào khóa học này
        $exists = TeacherCourseAssignment::where('course_id', $request->course_id)
            ->where('teacher_id', $request->teacher_id)
            ->exists();

        if ($exists) {
            return back()->with('error', 'Giáo viên này đã được phân công cho khóa học.');
        }

        TeacherCourseAssignment::create([
            'course_id' => $request->course_id,
            'teacher_id' => $request->teacher_id,
            'role' => $request->role,
            'assigned_at' => now(),
        ]);

        return back()->with('success', 'Phân công giáo viên thành công.');
    }

    //Xóa phân công 
    public function removeTeacher(Request $request)
    {
        $courseId = $request->input('course_id');
        $teacherId = $request->input('teacher_id');
        TeacherCourseAssignment::where('course_id', $courseId)
            ->where('teacher_id', $teacherId)
            ->delete();
        return back()->with('success', 'Đã xoá giáo viên khỏi khóa học.');
    }
    //Danh sách các trình độ
    public function ShowListLesson()
    {
        $lessons = Lesson::with('lessonParts')->orderBy('order_index')->get();
        return view('admin.showlesson', compact('lessons'));
    }
    // thêm trình độ mới 
    public function store(AddLevelRequest $request)
    {
        Lesson::create($request->validated());

        return redirect()->back()->with('success', 'Thêm trình độ thành công!');
    }
    //danh sách tên trình độ
    public function showLessonsWithLevels()
    {
        // Lấy danh sách các level duy nhất từ lessons
        $levels = Lesson::select('level')->distinct()->orderBy('level')->pluck('level');

        // Truyền xuống view
        return view('admin.AddQuestion', compact('levels'));
    }
    //Danh sách các bài học
    public function getLessonsByLevel($level)
    {
        $lessonPart = LessonPart::where('level', $level)
            ->get();

        return response()->json($lessonPart);
    }
    //Cập nhật lesson
    public function EditLesson(UpdateLessonRequest $request, string $level)
    {
        // Tìm bài học theo level, nếu không tìm thấy sẽ 404
        $lesson = Lesson::where('level', $level)->firstOrFail();

        // Cập nhật
        $lesson->update([
            'level'       => $request->input('level'),
            'title'       => $request->input('title'),
            'description' => $request->input('description'),
            'order_index' => $request->input('order_index'),
        ]);

        return redirect()->back()->with('success', 'cập nhật trình độ thành công!');
    }
    //Cập nhật lesson_part 
    public function EditLessonPart(UpdateLessonPartRequest  $request, $lesson_part_id)
    {
        $part = LessonPart::findOrFail($lesson_part_id);

        $part->update($request->validated());

        return back()->with('success', 'Đã cập nhật phần học.');
    }
}
