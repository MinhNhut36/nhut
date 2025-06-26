<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Client\StudentController;

Route::prefix('student')->name('student.')->middleware(['web', 'student'])->group(function () {
        Route::get('/home', [StudentController::class, 'home'])->name('home');

        Route::get('/courses', [StudentController::class, 'ShowListCourses'])->name('courses');

        Route::get('/coursesDetail/{Coursename}', [StudentController::class, 'ShowDetailCourses'])->name('DetailCourse');

        Route::get('/coursesRegister/{id}', [StudentController::class, 'CourseRegister'])->name('CourseRegister');

        Route::get('/myCourses', [StudentController::class, 'ListMyCourses'])->name('myCourses');

        Route::get('/myCoursesCompleted', [StudentController::class, 'CoursesCompleted'])->name('MyCoursesCompleted');

        Route::get('/lesson/{level}', [StudentController::class, 'ShowListLesson'])->where('level', '.*')->name('lesson');


        // Học sinh vào làm bài tập 
        
        // Bắt đầu bài tập
        Route::get('/exercise/{lessonPartId}/single-choice/start', [StudentController::class, 'startExercise'])->name('quiz.start');

        // Nộp đáp án
        Route::post('/exercise/{lessonPartId}/single-choice/answer', [StudentController::class, 'submitAnswer'])->name('quiz.submit');

        // Kết thúc bài
        Route::get('/exercise/{scoreId}/complete', [StudentController::class, 'completeExercise'])->name('quiz.complete');
});

Route::POST('/StudentAuthLogin', [StudentController::class, 'Studentlogin'])->name('student.authlogin');
