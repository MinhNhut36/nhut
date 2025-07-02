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
            $table->date('registration_date');
            $table->integer('status')->default(0); // 0: chờ xác nhận, 1: đang học, 2: đạt, 3: không đạt
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
