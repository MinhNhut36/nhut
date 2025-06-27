<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Client\StudentController;

Route::prefix('student')->name('student.')->middleware(['web', 'student'])->group(function () {
        Route::get('/home', [StudentController::class, 'home'])->name('home');

        Route::get('/courses', [StudentController::class, 'ShowListCourses'])->name('courses');

        Route::get('/coursesDetail/{level}', [StudentController::class, 'ShowDetailCourses'])->where('level', '.*')->name('DetailCourse');

        Route::get('/coursesRegister/{id}', [StudentController::class, 'CourseRegister'])->name('CourseRegister');

        Route::get('/myCourses', [StudentController::class, 'ListMyCourses'])->name('myCourses');

        Route::get('/myCoursesCompleted', [StudentController::class, 'CoursesCompleted'])->name('MyCoursesCompleted');

        Route::get('/lesson/{level}', [StudentController::class, 'ShowListLesson'])->where('level', '.*')->name('lesson');


        // Học sinh vào làm bài tập 
        
        // Bắt đầu bài tập
        Route::get('/exercise/{lessonPartId}/start', [StudentController::class, 'startExercise'])->name('exercise.start');

        // Nộp đáp án
        Route::post('/exercise/{lessonPartId}/submit', [StudentController::class, 'submitAnswer'])->name('exercise.submit');

        // Kết thúc bài
        Route::get('/exercise/{scoreId}/complete', [StudentController::class, 'completeExercise'])->name('exercise.complete');
});

Route::POST('/StudentAuthLogin', [StudentController::class, 'Studentlogin'])->name('student.authlogin');
