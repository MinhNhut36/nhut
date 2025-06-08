<?php
use Illuminate\Support\Facades\Route;


Route::middleware(['web', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/home', function () {
        return view('admin.home');
    })->name('home');

});
