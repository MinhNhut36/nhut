<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ApiStudentController;
use App\Http\Controllers\Api\ApiTeacherController;
use App\Http\Controllers\Api\ProgressController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\LessonController;
use App\Http\Controllers\Api\AssignmentController;
use App\Http\Controllers\Api\ClassPostController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\ExamController;

// ==================== AUTHENTICATION ====================
Route::get('StudentDN/{taikhoan}/{matkhau}', [AuthController::class, 'kiemTraDangNhap']);
Route::get('TeacherDN/{taikhoan}/{matkhau}', [AuthController::class, 'kiemTraDangNhapTeacher']);

// ==================== STUDENT MANAGEMENT ====================
Route::get('students/{studentId}', [ApiStudentController::class, 'getStudentById']);
Route::get('students', [ApiStudentController::class, 'getAllStudents']);
Route::put('students/{studentId}', [ApiStudentController::class, 'updateStudent']);

// ==================== COURSE MANAGEMENT ====================
Route::get('courses', [CourseController::class, 'getAllCourses']);
Route::get('courses/{courseId}', [CourseController::class, 'getCourseById']);
Route::get('courses/student/{studentId}', [CourseController::class, 'getCoursesByStudentId']);
Route::get('courses/level/{level}', [CourseController::class, 'getCoursesByLevel']);

// ==================== COURSE ENROLLMENT ====================
Route::get('enrollments/student/{studentId}', [CourseController::class, 'getEnrollmentsByStudentId']);
Route::post('enrollments', [CourseController::class, 'enrollStudent']);
Route::get('enrollments/course/{courseId}', [CourseController::class, 'getEnrollmentsByCourseId']);

// ==================== TEACHER MANAGEMENT ====================
Route::get('teachers', [ApiTeacherController::class, 'getAllTeachers']);
Route::get('teachers/{teacherId}', [ApiTeacherController::class, 'getTeacherById']);
Route::get('teachers/course/{courseId}', [ApiTeacherController::class, 'getTeachersByCourseId']);

// ==================== LESSON MANAGEMENT ====================
Route::get('lessons/course/{courseId}', [LessonController::class, 'getLessonsByCourseId']);
Route::get('lessons/{lessonId}', [LessonController::class, 'getLessonById']);
Route::get('lessons/level/{level}', [LessonController::class, 'getLessonsByLevel']);

// ==================== LESSON PARTS ====================
Route::get('lesson-parts/lesson/{lessonId}', [LessonController::class, 'getLessonPartsByLessonId']);
Route::get('lesson-parts/{lessonPartId}', [LessonController::class, 'getLessonPartById']);
Route::get('lesson-part-contents/{lessonPartId}', [LessonController::class, 'getLessonPartContents']);

// ==================== SCORES & PROGRESS ====================
Route::get('scores/student/{studentId}', [ApiStudentController::class, 'getScoresByStudentId']);
Route::get('scores/lesson-part/{lessonPartId}/student/{studentId}', [ApiStudentController::class, 'getScoreByLessonPartAndStudent']);
Route::post('scores', [ApiStudentController::class, 'submitScore']);

// ==================== PROGRESS TRACKING ====================
// Tiến độ lesson part với học sinh
Route::get('progress/lesson-part/{lessonPartId}/student/{studentId}', [ProgressController::class, 'getLessonPartProgress']);
// Tiến độ lesson với học sinh
Route::get('progress/lesson/{lessonLevel}/student/{studentId}', [ProgressController::class, 'getLessonProgress']);
// Tiến độ tổng quan của học sinh
Route::get('progress/student/{studentId}/overall', [ProgressController::class, 'getStudentOverallProgress']);



// ==================== ASSIGNMENTS & QUESTIONS ====================
Route::get('assignments/course/{courseId}', [AssignmentController::class, 'getAssignmentsByCourseId']);
Route::get('assignments/{assignmentId}', [AssignmentController::class, 'getAssignmentById']);
Route::get('questions/assignment/{assignmentId}', [AssignmentController::class, 'getQuestionsByAssignmentId']);
Route::get('questions/{questionId}', [AssignmentController::class, 'getQuestionById']);
Route::post('student-answers', [AssignmentController::class, 'submitAnswer']);
Route::get('student-answers/student/{studentId}', [AssignmentController::class, 'getAnswersByStudentId']);

// ==================== CLASS POSTS & COMMUNICATION ====================
Route::get('class-posts/course/{courseId}', [ClassPostController::class, 'getClassPostsByCourseId']);
Route::get('class-posts/{postId}', [ClassPostController::class, 'getClassPostById']);
Route::post('class-posts', [ClassPostController::class, 'createClassPost']);
Route::get('answers/question/{questionId}', [AssignmentController::class, 'getAnswersByQuestionId']);
Route::post('answers', [AssignmentController::class, 'createAnswer']);
Route::get('class-post-replies/post/{postId}', [ClassPostController::class, 'getClassPostReplies']);
Route::post('class-post-replies', [ClassPostController::class, 'createClassPostReply']);

// ==================== NOTIFICATIONS ====================
Route::get('notifications/student/{studentId}', [NotificationController::class, 'getNotificationsByStudentId']);
Route::get('notifications/{notificationId}', [NotificationController::class, 'getNotificationById']);
Route::put('notifications/{notificationId}/read', [NotificationController::class, 'markNotificationAsRead']);

// ==================== EXAM RESULTS & EVALUATION ====================
Route::get('exam-results/student/{studentId}', [ExamController::class, 'getExamResultsByStudentId']);
Route::get('exam-results/course/{courseId}', [ExamController::class, 'getExamResultsByCourseId']);
Route::post('exam-results', [ExamController::class, 'submitExamResult']);
Route::get('evaluations/student/{studentId}', [ExamController::class, 'getStudentEvaluations']);
Route::post('evaluations', [ExamController::class, 'createStudentEvaluation']);
