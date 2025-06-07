<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('course_enrollments', function (Blueprint $table) {
            $table->id('enrollment_id');
            $table->foreignId('student_id')
                  ->constrained('students', 'student_id')
                  ->onDelete('cascade');
            $table->foreignId('assigned_course_id')
                  ->constrained('courses', 'course_id') 
                    ->onDelete('cascade');  
            $table->string('level'); // Trình độ của học viên trong khóa học
            $table->integer('status')->default(0); // 0: chưa bắt đầu, 1: đang học, 2: đã hoàn thành
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_enrollments');
    }
};
