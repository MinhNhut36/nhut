<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;

Route::middleware(['web', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/home','App\Http\Controllers\Client\TeacherController@AdminHome')->name('home');
    
    Route::get('/students/search', [AdminController::class, 'SearchStudents'])->name('students.search');

    Route::get('/studentlist',[AdminController::class,'GetStudentList'])->name('studentlist');
});
