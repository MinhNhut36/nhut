<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ApiStudentController;
use App\Http\Controllers\Api\ApiTeacherController;
use App\Http\Controllers\Api\ProgressController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\LessonController;
use App\Http\Controllers\Api\LessonPartController;
use App\Http\Controllers\Api\TeacherAssignmentController;
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
Route::get('enrollments/course/{courseId}', [CourseController::class, 'getEnrollmentsByCourseId']);

// ==================== NEW SMART ENROLLMENT ====================
Route::post('enrollments/smart-register/student/{studentId}', [EnrollmentController::class, 'smartCourseRegistration']);

// ==================== TEACHER MANAGEMENT ====================
Route::get('teachers', [ApiTeacherController::class, 'getAllTeachers']);
Route::get('teachers/{teacherId}', [ApiTeacherController::class, 'getTeacherById']);
Route::get('teachers/course/{courseId}', [ApiTeacherController::class, 'getTeachersByCourseId']);

// ==================== LESSON MANAGEMENT ====================
Route::get('lessons/course/{courseId}', [LessonController::class, 'getLessonsByCourseId']);
Route::get('lessons/{level}', [LessonController::class, 'getLessonByLevel'])->where('level', '.*');

// ==================== LESSON PARTS ====================
Route::get('lesson-parts/lesson/{level}', [LessonController::class, 'getLessonPartsByLevel'])->where('level', '.*');
Route::get('lesson-parts/{lessonPartId}', [LessonController::class, 'getLessonPartById']);
Route::get('lesson-part-questions/{lessonPartId}', [LessonController::class, 'getLessonPartQuestions']);
Route::get('lesson-parts/course/{courseId}', [LessonPartController::class, 'getLessonPartsByCourse']);
Route::get('lesson-parts/{lessonPartId}/details', [LessonPartController::class, 'getLessonPartDetails']);
Route::get('lesson-parts/{lessonPartId}/student/{studentId}/progress', [LessonPartController::class, 'getLessonPartProgress']);

// ==================== SCORES & PROGRESS ====================
Route::get('scores/student/{studentId}', [ApiStudentController::class, 'getScoresByStudentId']);
Route::get('scores/lesson-part/{lessonPartId}/student/{studentId}', [ApiStudentController::class, 'getScoreByLessonPartAndStudent']);

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
Route::post('student-progress', [ProgressController::class, 'createOrUpdateStudentProgress']);
Route::get('progress/course/{courseId}/student/{studentId}/detailed', [ProgressController::class, 'getCourseProgress']);
Route::get('progress/student/{studentId}/overview/detailed', [ProgressController::class, 'getStudentProgressOverview']);

// Enrollment Management APIs
Route::get('enrollments/student/{studentId}', [EnrollmentController::class, 'getStudentEnrollments']);
Route::put('enrollments/{enrollmentId}/status', [EnrollmentController::class, 'updateEnrollmentStatus']);



// ==================== TEACHER COURSE ASSIGNMENTS & QUESTIONS ====================
Route::get('teacher-assignments/course/{courseId}', [TeacherAssignmentController::class, 'getTeacherAssignmentsByCourseId']);
Route::get('teacher-assignments/{assignmentId}', [TeacherAssignmentController::class, 'getTeacherAssignmentById']);
Route::get('teacher-assignments/teacher/{teacherId}', [TeacherAssignmentController::class, 'getAssignmentsByTeacherId']);
Route::post('teacher-assignments', [TeacherAssignmentController::class, 'createTeacherAssignment']);
Route::put('teacher-assignments/{assignmentId}', [TeacherAssignmentController::class, 'updateTeacherAssignment']);
Route::delete('teacher-assignments/{assignmentId}', [TeacherAssignmentController::class, 'deleteTeacherAssignment']);
Route::get('questions/{questionId}', [QuestionController::class, 'getQuestionById']);
Route::get('questions/lesson-part/{lessonPartId}', [QuestionController::class, 'getQuestionsByLessonPart']);
Route::get('answers/question/{questionId}', [QuestionController::class, 'getAnswersForQuestion']);
Route::post('lesson-part-scores', [QuestionController::class, 'submitLessonPartScore']);
Route::get('student-answers/student/{studentId}/course/{courseId}/lesson-part/{lessonPartId}', [QuestionController::class, 'getAnswersByStudentCourseAndLessonPart']);
Route::get('student-answers/student/{studentId}/course/{courseId}/lesson-part/{lessonPartId}/answered-at/{answeredAt}', [QuestionController::class, 'getAnswersByStudentCourseAndLessonPartAndDate']);

// ==================== NEW STUDENT ANSWER MANAGEMENT ====================
Route::post('student-answers/student/{studentId}/course/{courseId}/lesson-part/{lessonPartId}', [QuestionController::class, 'submitStudentAnswerByCourseAndLessonPart']);
Route::get('student-answers/recent-submission/student/{studentId}/course/{courseId}/lesson-part/{lessonPartId}', [QuestionController::class, 'getRecentSubmissionScoreAndProgress']);

// ==================== CLASS POSTS & COMMUNICATION ====================
// Class Posts - Get posts by course
Route::get('class-posts/course/{courseId}', [ClassPostController::class, 'getClassPostsByCourseId']);
Route::get('class-posts/course/{courseId}/comments', [ClassPostController::class, 'getClassPostCommentsByCourse']);
Route::get('class-posts/course/{courseId}/posts-with-comments', [ClassPostController::class, 'getPostsWithCommentsByCourse']);

// Class Posts - Single post operations
Route::get('class-posts/{postId}', [ClassPostController::class, 'getClassPostById']);
Route::post('class-posts', [ClassPostController::class, 'createClassPost']);
Route::put('class-posts/{postId}', [ClassPostController::class, 'updateClassPost']);
Route::delete('class-posts/{postId}', [ClassPostController::class, 'deleteClassPost']);

// Class Posts - Get posts by teacher (for teacher's own posts)
Route::get('class-posts/teacher/{teacherId}', [ClassPostController::class, 'getClassPostsByTeacher']);

// Class Post Comments - Comment operations
Route::get('class-post-replies/post/{postId}', [ClassPostController::class, 'getClassPostReplies']);
Route::post('class-post-replies', [ClassPostController::class, 'createClassPostReply']);
Route::put('class-post-replies/{commentId}', [ClassPostController::class, 'updateClassPostReply']);
Route::delete('class-post-replies/{commentId}', [ClassPostController::class, 'deleteClassPostReply']);

// Class Post Comments - Get comments by user
Route::get('class-post-replies/teacher/{teacherId}', [ClassPostController::class, 'getCommentsByTeacher']);
Route::get('class-post-replies/student/{studentId}', [ClassPostController::class, 'getCommentsByStudent']);

// ==================== NOTIFICATIONS ====================
Route::get('notifications/student/{studentId}', [NotificationController::class, 'getNotificationsByStudentId']);
Route::get('notifications/{notificationId}', [NotificationController::class, 'getNotificationById']);
Route::post('notifications', [NotificationController::class, 'createNotification']);
Route::put('notifications/{notificationId}', [NotificationController::class, 'updateNotification']);
Route::delete('notifications/{notificationId}', [NotificationController::class, 'deleteNotification']);

// ==================== EXAM RESULTS & EVALUATION ====================
Route::get('exam-results/{examId}', [ExamController::class, 'getExamById']);
Route::get('exam-results/student/{studentId}', [ExamController::class, 'getExamResultsByStudentId']);
Route::get('exam-results/course/{courseId}/student/{studentId}', [ExamController::class, 'getExamResultsByCourseAndStudent']);
Route::post('exam-results', [ExamController::class, 'submitExamResult']);
Route::get('evaluations/student/{studentId}', [ExamController::class, 'getStudentEvaluations']);
Route::post('evaluations', [ExamController::class, 'createStudentEvaluation']);

// ==================== STATISTICS & ANALYTICS ====================
Route::get('statistics/overview', [StatisticsController::class, 'getOverviewStatistics']);
Route::get('statistics/courses', [StatisticsController::class, 'getCourseStatistics']);
Route::get('statistics/students/performance', [StatisticsController::class, 'getStudentPerformanceStatistics']);
