<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Client\TeacherController;

Route::prefix('teacher')->name('teacher.')->middleware('auth:teachers')->group(function ()
 {

});


