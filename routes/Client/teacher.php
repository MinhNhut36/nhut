<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Client\TeacherController;

Route::prefix('teacher')->name('teacher.')->middleware(['web', 'teacher'])->group(function () {
    Route::get('/home', [TeacherController::class, 'home'])->name('home');

    Route::get('/CoursesOpening', [TeacherController::class, 'CoursesOpening'])->name('coursesopening');

    Route::get('/home/AssignedCoursesList/{courseId}', [TeacherController::class, 'CourseDetails'])->name('coursedetails');

    // Course Management Routes
    Route::prefix('/home/AssignedCoursesList/{courseId}')->group(function () {

        // Course Members Management
        Route::get('/members', [TeacherController::class, 'CourseMembers'])->name('coursemembers');
        Route::get('/members/{studentId}', [TeacherController::class, 'CourseStudentDetails'])->name('coursestudentdetails');

        // Course Bulletin(Board)
        Route::get('/board', [TeacherController::class, 'CourseBulletin'])->name('boards');
        Route::post('/posts', [TeacherController::class, 'StorePost'])->name('post'); // Đăng bài viết mới
        Route::post('/comment', [TeacherController::class, 'StoreComment'])->name('comment'); // Gửi bình luận cho một bài viết
        Route::delete('/posts/{postId}/delete', [TeacherController::class, 'deletePost'])
            ->name('posts.delete');
        Route::delete('/comment/{commentId}/delete', [TeacherController::class, 'deleteComment'])
            ->name('comment.delete');



        // Course Grade
        Route::get('/grade', [TeacherController::class, 'CourseGrade'])->name('grade');
        Route::get('/grade/export', [TeacherController::class, 'exportCourseGrade'])->name('exportCourseGrade');
        Route::post('/grade/update', [TeacherController::class, 'updateGrade'])->name('updategrade');

        // Course Assignments
        Route::get('/assignments', [TeacherController::class, 'assignments'])->name('assignments');
        Route::post('/assignments/create', [TeacherController::class, 'createAssignment'])->name('assignments.create');
        Route::get('/assignments/{assignmentId}', [TeacherController::class, 'viewAssignment'])->name('assignments.view');
        Route::put('/assignments/{assignmentId}', [TeacherController::class, 'updateAssignment'])->name('assignments.update');
        Route::delete('/assignments/{assignmentId}', [TeacherController::class, 'deleteAssignment'])->name('assignments.delete');
    });
});


Route::POST('/AuthTeacherLogin', [TeacherController::class, 'Teacherlogin'])->name('teacher.authlogin');
