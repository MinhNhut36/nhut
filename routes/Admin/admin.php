<?php
use Illuminate\Support\Facades\Route;


Route::middleware(['web', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/home','App\Http\Controllers\Client\TeacherController@AdminHome')->name('home');

});
