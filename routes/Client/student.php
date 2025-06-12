<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Client\StudentController;

Route::prefix('student')->name('student.')->middleware(['web','student'])->group(function () {
        Route::get('/home', [StudentController::class, 'home'])->name('home');
        Route::get('/courses', [StudentController::class, 'ShowListCourses'])->name('courses');
        Route::get('/coursesDetail/{id?}', [StudentController::class, 'ShowDetailCourses'])->name('DetailCourse');
        Route::get('/coursesRegister/{id?}', [StudentController::class, 'CourseRegister'])->name('CourseRegister');
        Route::get('/myCourses', [StudentController::class, 'ListMyCourses'])->name('myCourses');
        Route::get('/myCoursesCompleted', [StudentController::class, 'CoursesCompleted'])->name('MyCoursesCompleted');
        Route::get('/lesson', [StudentController::class, 'StudentLearning'])->name('lesson');
});

Route::POST('/StudentAuthLogin', [StudentController::class, 'Studentlogin'])->name('student.authlogin');