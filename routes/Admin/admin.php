<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;

Route::middleware(['web', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/home', [AdminController::class, 'AdminHome'])->name('home');
    Route::post('/notifications/store', [AdminController::class, 'AddNotification'])->name('notifications.store');
    Route::delete('/notifications/{id}', [AdminController::class, 'DeleteNotification'])->name('admin.notifications.destroy');

    //Route dành cho quản lý sinh viên
    Route::get('/studentlist', [AdminController::class, 'GetStudentList'])->name('studentlist');

    Route::post('/students/add', [AdminController::class, 'AddStudents'])->name('students.add');

    Route::post('/students/{id}/toggle-status', [AdminController::class, 'AjaxToggleStatus']);



    //Route dành cho quản lý giảng viên
    Route::get('/Teacherlist', [AdminController::class, 'GetTeacherList'])->name('teacherlist');

    Route::post('/teachers/{id}/toggle-status', [AdminController::class, 'AjaxToggleStatusTeacher']);

    Route::post('/teachers/add', [AdminController::class, 'Addteachers'])->name('teachers.add');



    //Route dành cho quản lý khóa học
    Route::get('/Courses', [AdminController::class, 'GetCourseList'])->name('courses');

    Route::post('/Courses/create', [AdminController::class, 'CreateCourse'])->name('courses.create');

    Route::get('/courses/{id}/edit', [AdminController::class, 'CourseEdit'])->name('course.edit');

    Route::post('/courses/{id}/update', [AdminController::class, 'CourseUpdate'])->name('course.update');

    Route::delete('/courses/{id}/delete', [AdminController::class, 'CourseDelete'])->name('course.delete');

    Route::get('/AssignTeachers', [AdminController::class, 'showUnassignedCourses'])->name('assign.index');

    Route::post('/assign-teacher', [AdminController::class, 'assignTeacher'])->name('course.assign-teacher');

    Route::delete('/course/remove-teacher', [AdminController::class, 'removeTeacher'])->name('course.remove-teacher');

    Route::get('/lessons', [AdminController::class, 'ShowListLesson'])->name('lesson');

    Route::post('/lessons/create', [AdminController::class, 'store'])->name('lesson.create');

    Route::put('/lessons/EditLesson/{level}', [AdminController::class, 'EditLesson'])->where('level', '.*')->name('lesson.EditLesson');

    Route::put('/lessons/EditLessonPart/{lesson_part_id}', [AdminController::class, 'EditLessonPart'])->name('lesson.EditLessonPart');

    Route::post('/questions/add', [AdminController::class, 'AddQuestion'])->name('questions.store');
    //Route dành cho quản lý câu hỏi
    Route::get('/level/lessons', [AdminController::class, 'showLessonsWithLevels'])->name('level.lessons');

    Route::get('/api/lessons/by-level/{level}', [AdminController::class, 'getLessonsByLevel'])->where('level', '.*');
});
