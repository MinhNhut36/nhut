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
        Schema::create('teacher_course_assignments', function (Blueprint $table) {
            $table->id('assignment_id');
                     $table->foreignId('teacher_id')
                  ->constrained('teachers', 'teacher_id')
                  ->onDelete('cascade');
            $table->foreignId('course_id')
                  ->constrained('courses', 'course_id')
                  ->onDelete('cascade');
            $table->string('role'); // Giảng viên, Trợ giảng,...
            $table->date('assigned_at'); // Ngày được phân công
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teacher_course_assignments');
    }
};
