<?php
use Illuminate\Support\Facades\Route;


Route::group(['middleware' => 'web'], function () {
    Route::prefix('admin')->name('admin.')->group(function () {

    });
});

