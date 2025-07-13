<?php

namespace App\Http\Controllers\Client;

// Thêm các thư viện cần thiết xuất điểm khóa học bằng file Excel
use Spatie\SimpleExcel\SimpleExcelWriter;
use Illuminate\Support\Str;

use App\Enum\courseStatus;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\TeacherRequest;
use App\Http\Requests\UpdateScoreRequest;
use App\Models\Teacher;
use App\Models\Course;
use App\Models\TeacherCourseAssignment;
use App\Models\CourseEnrollment;
use App\Models\ClassPost;
use App\Models\ClassPostComment;
use App\Models\Student;
use App\Models\ExamResult;
use App\Models\Lesson;
use App\Models\LessonPart;

class TeacherController extends Controller
{
    public function Teacherlogin(TeacherRequest $request)
    {

        $credentials = $request->only('username', 'password');
        // Kiểm tra tài khoản có tồn tại ở bảng teachers không
        $isTeacher = Teacher::where('username', $credentials['username'])->exists();
        if ($isTeacher) {
            $CheckStatus = Teacher::where('username', $credentials['username'])->first();
            if ($CheckStatus->is_status->value == 0) {
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
            } else {
                return redirect()->back()->withErrors(['TeacherLoginFail' => 'Thông tin đăng nhập không chính xác']);
            }
        }
        // Nếu không đăng nhập được
        return redirect()->back()->withErrors(['TeacherLoginFail' => 'Thông tin đăng nhập không chính xác']);
    }
    // hiển thị thông tin của giảng viên và thông báo
    public function home()
    {
        $teacher = Auth::guard('teacher')->user();
        return view('teacher.home')->with('teacher', $teacher);
    }


    // hiển thị danh sách các khóa học đã được phân công cho giảng viên
    //Danh sách các khóa học đang mở lớp
    public function CoursesOpening()
    {
        $teacher = Auth::guard('teacher')->user();
        $MyCourses = TeacherCourseAssignment::with('course')
            ->where('teacher_id', $teacher->teacher_id)
            ->whereHas('course', function ($query) {
                $query->where('status', courseStatus::IsOpening->value);
            })
            ->get();

        // Tạo mảng chứa số sinh viên theo từng khóa
        $countStudents = [];

        foreach ($MyCourses as $assignment) {
            $assignedCourseId = $assignment->course_id;
            $count = CourseEnrollment::with('student')
                ->where('assigned_course_id', $assignedCourseId)
                ->count();
            $countStudents[$assignedCourseId] = $count;
        }
        return view('teacher.CoursesOpening')->with('enrollment', $MyCourses)->with('countStudents', $countStudents);
    }

    //Danh sách các khóa học đã hoàn thành
    public function CoursesCompleted()
    {
        $teacher = Auth::guard('teacher')->user();
        $MyCourses = TeacherCourseAssignment::with('course')
            ->where('teacher_id', $teacher->teacher_id)
            ->whereHas('course', function ($query) {
                $query->where('status', courseStatus::Complete->value);
            })
            ->get();

        // Tạo mảng chứa số sinh viên theo từng khóa
        $countStudents = [];

        foreach ($MyCourses as $assignment) {
            $assignedCourseId = $assignment->course_id;
            $count = CourseEnrollment::with('student')
                ->where('assigned_course_id', $assignedCourseId)
                ->count();
            $countStudents[$assignedCourseId] = $count;
        }
        return view('teacher.CoursesCompleted')->with('enrollment', $MyCourses)->with('countStudents', $countStudents);
    }


    // hiển thị các thông tin của 1 khóa học
    public function CourseDetails($courseId)
    {
        $teacher = Auth::guard('teacher')->user();
        $course = Course::find($courseId);

        return view('teacher.AssignedCourseDetail')
            ->with('course', $course);
    }


    public function CourseMembers(Request $request, $courseId)
    {
        $teacher = Auth::guard('teacher')->user();

        // Lấy thông tin khóa học
        $course = Course::find($courseId);

        // Đếm số sinh viên và sinh viên hoạt động
        $countstudent = CourseEnrollment::with('student')
            ->where('assigned_course_id', $courseId)
            ->count();

        $countstudentactive = CourseEnrollment::with('student')
            ->where('assigned_course_id', $courseId)
            ->whereHas('student', function ($query) {
                $query->where('is_status', 1);
            })
            ->count();

        // Lấy danh sách giảng viên
        $countteacheractive = TeacherCourseAssignment::with('teacher')
            ->where('course_id', $courseId)
            ->count();

        // Lấy danh sách sinh viên theo khóa học
        $query = CourseEnrollment::with('student')
            ->where('assigned_course_id', $courseId);

        // Tìm kiếm theo tên, mã SV, email
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->whereHas('student', function ($q) use ($searchTerm) {
                $q->where('fullname', 'like', '%' . $searchTerm . '%')
                    ->orWhere('student_id', 'like', '%' . $searchTerm . '%')
                    ->orWhere('email', 'like', '%' . $searchTerm . '%');
            });
        }

        // Lọc theo trạng thái (1: Hoạt động, 0: Không hoạt động)
        if ($request->filled('status')) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('is_status', $request->status);
            });
        }

        // Lọc theo giới tính (1: Nam, 0: Nữ)
        if ($request->filled('gender')) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('gender', $request->gender);
            });
        }

        $members = $query->paginate(1)->appends($request->all());
        if ($members->currentPage() > $members->lastPage()) {
            return redirect()->route('admin.teacherlist', ['page' => $members->lastPage()]);
        }



        return view('teacher.CourseMembers')
            ->with('members', $members)
            ->with('course', $course)
            ->with('countstudent', $countstudent);
    }


    public function CourseStudentDetails($courseId, $studentId)
    {
        $student = Student::find($studentId);

        $course = CourseEnrollment::with('course')
            ->where('assigned_course_id', $courseId)
            ->where('student_id', $student->student_id)
            ->first();

        $level = $course->course->level;

        // Lấy toàn bộ lesson parts thuộc lesson
        $lessonParts = LessonPart::where('level', $level)
            ->with(['scores' => function ($query) use ($studentId, $courseId) {
                $query->where('student_id', $studentId)
                    ->where('course_id', $courseId)
                    ->orderBy('attempt_no');
            }])
            ->orderBy('order_index')
            ->get();


        return view('teacher.CourseStudentDetails')
            ->with('student', $student)
            ->with('course', $course)
            ->with('lessonParts', $lessonParts);
    }


    //Hiển thị bảng tin của giảng viên
    public function CourseBulletin($courseId)
    {

        $teacher = Auth::guard('teacher')->user(); // giảng viên đang đăng nhập

        // Lấy thông tin khóa học
        $course = Course::find($courseId);

        // Lấy danh sách bài viết của giảng viên đó trong khóa học này
        $posts = ClassPost::with([
            'teacher',                  // người tạo bài viết
            'comments',      // người tạo bình luận (student/teacher)
        ])
            ->where('course_id', $courseId)
            ->where('status', 1)
            ->orderByDesc('created_at')
            ->get();


        // Trả về view
        return view('teacher.CourseBulletin')
            ->with('posts', $posts)
            ->with('course', $course)
            ->with('teacher', $teacher);
    }

    public function StorePost(Request $request, $courseId)
    {
        $request->validate([
            'title'   => 'required|string|max:255',
            'content' => 'required|string|max:1000',
        ]);

        $teacher = Auth::guard('teacher')->user();

        ClassPost::create([
            'course_id'   => $courseId,
            'teacher_id'  => $teacher->teacher_id,
            'title'       => $request->title,
            'content'     => $request->content,
            'status'      => 1,
        ]);

        return redirect()->back()->with('success', 'Đăng bài viết thành công.');
    }

    public function deletePost(int $courseId, int $postId)
    {
        $teacher = Auth::guard('teacher')->user();
        // Tìm bài post theo ID và kiểm tra có thuộc giảng viên hiện tại không
        $post = ClassPost::where('post_id', $postId)
            ->where('teacher_id', $teacher->teacher_id)
            ->where('course_id', $courseId)
            ->first();

        if (!$post) {
            return redirect()
                ->route('teacher.boards', ['courseId' => $courseId])
                ->with('error', 'Đây không phải là bài viết của bạn, bạn không thể xóa!!.');
        }

        $post->delete();

        return redirect()
            ->route('teacher.boards', ['courseId' => $courseId])
            ->with('success', 'Bài viết đã được xóa thành công.');
    }
    /**
     * Gửi phản hồi cho bài viết
     */
    public function StoreComment(Request $request, $postId)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $teacher = Auth::guard('teacher')->user();

        ClassPostComment::create([
            'post_id'    => $postId,
            'teacher_id' => $teacher->teacher_id,  // ✅ dùng đúng cột trong DB
            'content'    => $request->content,
            'status'     => 1,
        ]);

        return redirect()->back()->with('success', 'Phản hồi đã được gửi.');
    }
    // Xóa bình luận của bài viết dựa trên ID bình luận
    public function deleteComment(int $courseId, ClassPostComment $commentId)
    {
        $teacher = Auth::guard('teacher')->user();

        $commentId->delete();

        return redirect()
            ->route('teacher.boards', ['courseId' => $courseId])
            ->with('success', 'Đã xóa bình luận thành công.');
    }


    // Quản lý điểm của sinh viên cho một khóa học
    public function CourseGrade(Request $request, $courseId)
    {

        $teacher = Auth::guard('teacher')->user();

        // Kiểm tra và lấy khóa học
        $course = Course::findOrFail($courseId);

        // Lấy danh sách sinh viên đã đăng ký khóa học này kèm thông tin điểm (nếu có)
        $query = CourseEnrollment::with([
            'student',
            'examResult' => fn($q) => $q->where('course_id', $courseId)
        ])
            ->where('assigned_course_id', $courseId)
            ->whereHas('student', function ($q) use ($request) {
                // Tìm kiếm theo tên, email hoặc mã SV
                if ($request->filled('search')) {
                    $q->where(function ($sub) use ($request) {
                        $sub->where('fullname', 'like', '%' . $request->search . '%')
                            ->orWhere('email', 'like', '%' . $request->search . '%')
                            ->orWhere('student_id', 'like', '%' . $request->search . '%');
                    });
                }

                // Lọc theo trạng thái
                if ($request->filled('status')) {
                    $q->where('is_status', $request->status);
                }

                // Lọc theo giới tính
                if ($request->filled('gender')) {
                    $q->where('gender', $request->gender);
                }
            });

        // Lấy danh sách sinh viên (gồm điểm nếu có)
        $studentgrades = $query->get();

        return view('teacher.CourseGrade')
            ->with('studentgrades', $studentgrades)
            ->with('course', $course);
    }

    // Cập nhật điểm của các sinh viên cùng 1 lúc
    public function updateGrade(UpdateScoreRequest $request, $courseId)
    {

        $grades = $request->input('grades', []);

        foreach ($grades as $studentId => $data) {
            if (!isset($data['student_id'])) continue;

            $exam = ExamResult::firstOrNew([
                'student_id' => $data['student_id'],
                'course_id' => $courseId,
            ]);

            $exam->listening_score = $data['listening_score'] ?? null;
            $exam->speaking_score  = $data['speaking_score'] ?? null;
            $exam->writing_score   = $data['writing_score'] ?? null;
            $exam->reading_score   = $data['reading_score'] ?? null;
            $exam->exam_date       = now();

            // Tính trạng thái tổng thể
            $scores = [
                $exam->listening_score,
                $exam->speaking_score,
                $exam->writing_score,
                $exam->reading_score,
            ];

            if (!in_array(null, $scores, true)) {
                $exam->overall_status = collect($scores)->every(fn($s) => $s >= 5) ? 1 : 0;
            } else {
                $exam->overall_status = 0;
            }

            $exam->save();
        }

        return redirect()->route('teacher.grade', $courseId)->with('success', 'Cập nhật điểm thành công!');
    }

    // Xuất điểm của khóa học bằng file Excel
    public function exportCourseGrade(Request $request, $courseId)
    {
        $teacher = Auth::guard('teacher')->user();

        $enrollments = CourseEnrollment::with([
            'student',
            'examResult' => fn($q) => $q->where('course_id', $courseId)
        ])
            ->where('assigned_course_id', $courseId)
            ->get();
        if ($enrollments->isEmpty()) {
            return back()->with('error', 'Không có dữ liệu điểm để xuất.');
        }

        // Lấy đường dẫn Desktop (Windows)
        $desktopPath = getenv("HOMEDRIVE") . getenv("HOMEPATH") . '\Desktop';

        // Tạo đường dẫn file CSV
        $filePath = $desktopPath . '\bang_diem_khoa_' . $courseId . '.csv';

        // Tạo dữ liệu xuất
        $rows = $enrollments->map(function ($enrollment) {
            return [
                'Mã SV' => $enrollment->student->student_id,
                'Họ tên' => $enrollment->student->fullname,
                'Email' => $enrollment->student->email,
                'Nghe' => optional($enrollment->examResult)->listening_score ?? 0,
                'Nói' => optional($enrollment->examResult)->speaking_score ?? 0,
                'Viết' => optional($enrollment->examResult)->writing_score ?? 0,
                'Đọc' => optional($enrollment->examResult)->reading_score ?? 0,
                'Ngày thi' => optional($enrollment->examResult)->exam_date ?? '',
                'Kết quả' => optional($enrollment->examResult)->overall_status == 1 ? 'Đạt' : 'Không đạt',
            ];
        });

        // Ghi file
        SimpleExcelWriter::create($filePath)
            ->addRows($rows);

        return back()->with('success', 'Đã xuất bảng điểm ra Desktop: ' . $filePath);
    }
}
