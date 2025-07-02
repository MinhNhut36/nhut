<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Client\TeacherController;

Route::prefix('teacher')->name('teacher.')->middleware(['web', 'teacher'])->group(function () {
    Route::get('/home', [TeacherController::class, 'home'])->name('home');

    Route::get('/home/AssignedCoursesList', [TeacherController::class, 'AssignedCourses'])->name('assignedcourseslist');

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

        Route::get('/grade', [TeacherController::class, 'CourseGrade'])->name('grade');

        Route::get('/announcements', [TeacherController::class, 'ShowBulletinBoard'])->name('showbulletinboard');
        Route::post('/announcements/create', [TeacherController::class, 'createAnnouncement'])->name('announcements.create');
        Route::put('/announcements/{announcementId}', [TeacherController::class, 'updateAnnouncement'])->name('announcements.update');
        Route::delete('/announcements/{announcementId}', [TeacherController::class, 'deleteAnnouncement'])->name('announcements.delete');

        // Course Assignments
        Route::get('/assignments', [TeacherController::class, 'assignments'])->name('assignments');
        Route::post('/assignments/create', [TeacherController::class, 'createAssignment'])->name('assignments.create');
        Route::get('/assignments/{assignmentId}', [TeacherController::class, 'viewAssignment'])->name('assignments.view');
        Route::put('/assignments/{assignmentId}', [TeacherController::class, 'updateAssignment'])->name('assignments.update');
        Route::delete('/assignments/{assignmentId}', [TeacherController::class, 'deleteAssignment'])->name('assignments.delete');
    });
});


Route::POST('/AuthTeacherLogin', [TeacherController::class, 'Teacherlogin'])->name('teacher.authlogin');
