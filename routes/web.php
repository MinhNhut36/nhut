<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;


require __DIR__.'/Admin/admin.php';
require __DIR__.'/Client/student.php';
require __DIR__.'/Client/teacher.php';

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', function () {
    return view('student.login');
})->name('login')->middleware('logout');

Route::get('/Teacherlogin', function () {
    return view('teacher.login');
})->name('teacher.login')->middleware('logout');