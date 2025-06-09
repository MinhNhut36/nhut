<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Client\StudentController;

Route::prefix('student')->name('student.')->middleware(['web','student'])->group(function () {
        Route::get('/home', [StudentController::class, 'home'])->name('home');
        Route::get('/courses', [StudentController::class, 'ShowListCourses'])->name('courses');
        Route::get('/coursesDetail/{id?}', [StudentController::class, 'ShowDetailCourses'])->name('DetailCourse');
});

Route::POST('/StudentAuthLogin', [StudentController::class, 'Studentlogin'])->name('student.authlogin');