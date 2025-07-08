<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
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
use App\Models\Question;
use App\Models\Answer;
use App\Models\Notification;
use App\Http\Requests\NotificationRequest;

class AdminController extends Controller
{
    // hiển thị thông tin của admin
    public function AdminHome(Request $request)
    {
        $admin = Auth::user();
        $notifications = Notification::orderBy('created_at', 'desc')->paginate(12); 
        $CoutNotifications = Notification::count();
        // Nếu trang yêu cầu lớn hơn trang tối đa, chuyển hướng về trang cuối
        if ($request->page > $notifications->lastPage()) {
            return redirect()->route('admin.home', ['page' => $notifications->lastPage()]);
        }

        return view('admin.home')->with('admin', $admin)->with('notifications', $notifications)->with('CoutNotifications', $CoutNotifications);
    }
    //THêm thông báo
    public function AddNotification(NotificationRequest $request)
    {
        $admin_id = Auth::user()->admin_id;
        Notification::create([
            'admin_id' => $admin_id,
            'title' => $request->title,
            'message' => $request->message,
            'notification_date' => now(),
        ]);

        return redirect()->back()->with('success', 'Thông báo đã được thêm thành công!');
    }
    //Xóa thông báo
    public function DeleteNotification($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->delete();
        return redirect()->back()->with('success', 'Thông báo đã được xóa thành công!');
    }
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

        $courses = $query->orderByRaw("FIELD(status, 'Chờ xác thực', 'Đang mở lớp', 'Đã hoàn thành')")->paginate(10)->appends($request->all());

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



    //Thêm câu hỏi
    public function AddQuestion(Request $request)
    {

        $type = $request->input('question_type');

        switch ($type) {
            case 'single_choice':
                return $this->handleSingleChoice($request);
            case 'matching':
                return $this->handleMatching($request);
            case 'classification':
                return $this->handleClassification($request);
            case 'fill_blank':
                return $this->handleFillBlank($request);
            case 'arrangement':
                return $this->handleArrangement($request);
            case 'image_word':
                return $this->handleImageWord($request);
            default:
                return back()->withErrors(['message' => 'Loại câu hỏi không hợp lệ']);
        }
    }

    private function handleSingleChoice(Request $request)
    {
        // 1. Tạo câu hỏi
        $question = Question::create([
            'lesson_part_id' => $request->lesson_part_id,
            'question_type' => 'single_choice',
            'media_url' => null,
            'question_text' => $request->question_text,
            'order_index' => $request->order_index,
        ]);

        // 2. Tạo các đáp án liên kết
        foreach ($request->answers as $index => $answerText) {
            Answer::create([
                'questions_id' => $question->questions_id,
                'match_ket' => null,
                'answer_text' => $answerText,
                'is_correct' => $index == $request->correct_answer ? 1 : 0,
                'feedback' => $index == $request->correct_answer
                    ? $request->correct_feedback
                    : $request->wrong_feedback,
                'order_index' => $index + 1,
                'media_url' => null,
            ]);
        }

        return back()->with('success', 'Tạo câu hỏi trắc nghiệm thành công!');
    }

    private function handleMatching(Request $request)
    {
        dd($request);
        $pairs = [];
        foreach ($request->words as $i => $word) {
            $image = $request->file('images')[$i] ?? null;
            $imagePath = $image ? $image->store('matching_images', 'public') : null;
            $pairs[] = ['word' => $word, 'image' => $imagePath];
        }

        Question::create([
            'lesson_part_id' => $request->lesson_part_id,
            'question_type' => 'matching',
            'order_index' => $request->order_index,
            'question_text' => $request->question_text,
            'matching_pairs' => json_encode($pairs),
        ]);

        return back()->with('success', 'Tạo câu hỏi nối từ thành công!');
    }

    private function handleClassification(Request $request)
    {
        dd($request);
        $classificationData = [
            'nouns' => preg_split('/\r\n|\r|\n/', $request->nouns ?? ''),
            'verbs' => preg_split('/\r\n|\r|\n/', $request->verbs ?? ''),
            'adjectives' => preg_split('/\r\n|\r|\n/', $request->adjectives ?? ''),
        ];

        Question::create([
            'lesson_part_id' => $request->lesson_part_id,
            'question_type' => 'classification',
            'order_index' => $request->order_index,
            'question_text' => $request->question_text,
            'classification_data' => json_encode($classificationData),
        ]);

        return back()->with('success', 'Tạo câu hỏi phân loại từ thành công!');
    }

    private function handleFillBlank(Request $request)
    {
        // 1. Tạo câu hỏi
        $question = Question::create([
            'lesson_part_id' => $request->lesson_part_id,
            'question_type' => 'fill_blank',
            'question_text' => $request->question_text, // Ví dụ: I ___ to school every day.
            'order_index' => $request->order_index,
            'media_url' => null,
        ]);

        // 2. Tạo đáp án đúng duy nhất
        Answer::create([
            'questions_id' => $question->questions_id,
            'answer_text' => $request->correct_word, // ví dụ: "go"
            'is_correct' => 1,
            'match_ket' => null,
            'order_index' => 1,
            'feedback' => $request->wrong_feedback,
            'media_url' => null,
        ]);

        return back()->with('success', 'Tạo câu hỏi điền chỗ trống thành công!');
    }

    private function handleArrangement(Request $request)
    {
        dd($request);
        $words = explode(' ', $request->correct_sentence);
        shuffle($words);

        Question::create([
            'lesson_part_id' => $request->lesson_part_id,
            'question_type' => 'arrangement',
            'order_index' => $request->order_index,
            'question_text' => $request->question_text,
            'correct_answer' => $request->correct_sentence,
            'options' => json_encode($words),
        ]);

        return back()->with('success', 'Tạo câu hỏi sắp xếp câu thành công!');
    }

    private function handleImageWord(Request $request)
    {
        dd($request);
        $mediaPath = $request->file('media_url')->store('image_word', 'public');
        $letters = str_split($request->correct_word);
        shuffle($letters);

        Question::create([
            'lesson_part_id' => $request->lesson_part_id,
            'question_type' => 'image_word',
            'order_index' => $request->order_index,
            'media_url' => $mediaPath,
            'correct_answer' => $request->correct_word,
            'hint' => $request->hint,
            'options' => json_encode($letters),
        ]);

        return back()->with('success', 'Tạo câu hỏi nhìn ảnh ghép từ thành công!');
    }
}
