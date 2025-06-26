<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ApiStudentController;
use App\Http\Controllers\Api\ApiTeacherController;
use App\Http\Controllers\Api\ProgressController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\LessonController;
use App\Http\Controllers\Api\LessonPartController;
use App\Http\Controllers\Api\AssignmentController;
use App\Http\Controllers\Api\QuestionController;
use App\Http\Controllers\Api\ClassPostController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\ExamController;

use App\Http\Controllers\Api\EnrollmentController;
use App\Http\Controllers\Api\StatisticsController;

// ==================== AUTHENTICATION ====================
Route::get('StudentDN/{taikhoan}/{matkhau}', [AuthController::class, 'kiemTraDangNhap']);
Route::get('TeacherDN/{taikhoan}/{matkhau}', [AuthController::class, 'kiemTraDangNhapTeacher']);

// ==================== STUDENT MANAGEMENT ====================
Route::put('students/{studentId}/change-password', [ApiStudentController::class, 'changePassword']);
Route::get('students/{studentId}', [ApiStudentController::class, 'getStudentById']);
Route::get('students', [ApiStudentController::class, 'getAllStudents']);
Route::put('students/{studentId}', [ApiStudentController::class, 'updateStudent']);

// ==================== COURSE MANAGEMENT ====================
Route::get('courses/{courseId}/students/count', [CourseController::class, 'getCourseStudentCount']);
Route::get('courses', [CourseController::class, 'getAllCourses']);
Route::get('courses/{courseId}', [CourseController::class, 'getCourseById']);
Route::get('courses/student/{studentId}', [CourseController::class, 'getCoursesByStudentId']);
Route::get('courses/level/{level}', [CourseController::class, 'getCoursesByLevel']);

// ==================== COURSE ENROLLMENT ====================
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
Route::get('lesson-part-questions/{lessonPartId}', [LessonController::class, 'getLessonPartQuestions']);
Route::get('lesson-parts/course/{courseId}', [LessonPartController::class, 'getLessonPartsByCourse']);
Route::get('lesson-parts/{lessonPartId}/details', [LessonPartController::class, 'getLessonPartDetails']);
Route::get('lesson-parts/{lessonPartId}/student/{studentId}/progress', [LessonPartController::class, 'getLessonPartProgress']);

// ==================== SCORES & PROGRESS ====================
Route::get('scores/student/{studentId}', [ApiStudentController::class, 'getScoresByStudentId']);
Route::get('scores/lesson-part/{lessonPartId}/student/{studentId}', [ApiStudentController::class, 'getScoreByLessonPartAndStudent']);
Route::post('scores', [ApiStudentController::class, 'submitScore']);

// ==================== PROGRESS TRACKING ====================

// Course-based Progress APIs (using CORRECT formula)
Route::get('progress/course/{courseId}/student/{studentId}', [ProgressController::class, 'getCourseProgress']);
Route::get('progress/student/{studentId}/overview', [ProgressController::class, 'getStudentOverallProgress']);

// Lesson Part Progress APIs (with course context)
Route::get('progress/lesson-part/{lessonPartId}/student/{studentId}', [ProgressController::class, 'getLessonPartProgress']);
Route::get('progress/lesson-part/{lessonPartId}/student/{studentId}/course/{courseId}', [ProgressController::class, 'getLessonPartProgress']);

// Lesson Progress APIs (with course context)
Route::get('progress/lesson/{lessonLevel}/student/{studentId}', [ProgressController::class, 'getLessonProgress']);
Route::get('progress/lesson/{lessonLevel}/student/{studentId}/course/{courseId}', [ProgressController::class, 'getLessonProgress']);

// Student Progress APIs (comprehensive progress tracking)
Route::post('student-progress', [ProgressController::class, 'updateStudentProgress']);
Route::get('progress/course/{courseId}/student/{studentId}/detailed', [ProgressController::class, 'getCourseProgress']);
Route::get('progress/student/{studentId}/overview/detailed', [ProgressController::class, 'getStudentProgressOverview']);

// Enrollment Management APIs
Route::get('enrollments/student/{studentId}', [EnrollmentController::class, 'getStudentEnrollments']);
Route::put('enrollments/{enrollmentId}/status', [EnrollmentController::class, 'updateEnrollmentStatus']);



// ==================== ASSIGNMENTS & QUESTIONS ====================
Route::get('assignments/course/{courseId}', [AssignmentController::class, 'getAssignmentsByCourseId']);
Route::get('assignments/{assignmentId}', [AssignmentController::class, 'getAssignmentById']);
Route::get('questions/assignment/{assignmentId}', [AssignmentController::class, 'getQuestionsByAssignmentId']);
Route::get('questions/{questionId}', [AssignmentController::class, 'getQuestionById']);
Route::get('questions/lesson-part/{lessonPartId}', [QuestionController::class, 'getQuestionsByLessonPart']);
Route::get('answers/question/{questionId}', [QuestionController::class, 'getAnswersForQuestion']);
Route::post('student-answers', [QuestionController::class, 'submitStudentAnswer']);
Route::post('lesson-part-scores', [QuestionController::class, 'submitLessonPartScore']);
Route::get('student-answers/student/{studentId}', [AssignmentController::class, 'getAnswersByStudentId']);

// ==================== CLASS POSTS & COMMUNICATION ====================
Route::get('class-posts/course/{courseId}', [ClassPostController::class, 'getClassPostsByCourseId']);
Route::get('class-posts/course/{courseId}/comments', [ClassPostController::class, 'getClassPostCommentsByCourse']);
Route::get('class-posts/{postId}', [ClassPostController::class, 'getClassPostById']);
Route::post('class-posts', [ClassPostController::class, 'createClassPost']);
Route::put('class-posts/{postId}', [ClassPostController::class, 'updateClassPost']);
Route::delete('class-posts/{postId}', [ClassPostController::class, 'deleteClassPost']);
Route::post('answers', [AssignmentController::class, 'createAnswer']);
Route::get('class-post-replies/post/{postId}', [ClassPostController::class, 'getClassPostReplies']);
Route::post('class-post-replies', [ClassPostController::class, 'createClassPostReply']);
Route::put('class-post-replies/{commentId}', [ClassPostController::class, 'updateClassPostReply']);
Route::delete('class-post-replies/{commentId}', [ClassPostController::class, 'deleteClassPostReply']);

// ==================== NOTIFICATIONS ====================
Route::get('notifications/student/{studentId}', [NotificationController::class, 'getNotificationsByStudentId']);
Route::get('notifications/student/{studentId}/unread-count', [NotificationController::class, 'getUnreadNotificationCount']);
Route::get('notifications/{notificationId}', [NotificationController::class, 'getNotificationById']);
Route::put('notifications/{notificationId}/read', [NotificationController::class, 'markNotificationAsRead']);

// ==================== EXAM RESULTS & EVALUATION ====================
Route::get('exam-results/student/{studentId}', [ExamController::class, 'getExamResultsByStudentId']);
Route::get('exam-results/course/{courseId}', [ExamController::class, 'getExamResultsByCourseId']);
Route::post('exam-results', [ExamController::class, 'submitExamResult']);
Route::get('evaluations/student/{studentId}', [ExamController::class, 'getStudentEvaluations']);
Route::post('evaluations', [ExamController::class, 'createStudentEvaluation']);

// ==================== STATISTICS & ANALYTICS ====================
Route::get('statistics/overview', [StatisticsController::class, 'getOverviewStatistics']);
Route::get('statistics/courses', [StatisticsController::class, 'getCourseStatistics']);
Route::get('statistics/students/performance', [StatisticsController::class, 'getStudentPerformanceStatistics']);
