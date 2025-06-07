<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Client\StudentController;

Route::prefix('student')->name('student.')->group(function () {
        
});
Route::get('/home', [StudentController::class, 'home'])->name('home');
Route::POST('/StudentLogin', [StudentController::class, 'Login'])->name('student.login');