<?php
use Illuminate\Support\Facades\Route;


Route::group(['middleware' => 'web'], function () {
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/home', function () {
            return view('admin.home');
        })->name('home');
        
    });
});

