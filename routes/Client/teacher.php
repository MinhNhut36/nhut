<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Client\TeacherController;

Route::prefix('teacher')->name('teacher.')->middleware(['web','teacher'])->group(function ()
 {
Route::get('/home', [TeacherController::class, 'home'])->name('home');
});


Route::POST('/AuthTeacherLogin', [TeacherController::class, 'Teacherlogin'])->name('teacher.authlogin');