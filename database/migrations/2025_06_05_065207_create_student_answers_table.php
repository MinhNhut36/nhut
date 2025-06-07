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
        Schema::create('student_answers', function (Blueprint $table) {
            $table->id('student_answers_id');
            $table->foreignId('student_id')
                ->constrained('students', 'student_id')
                ->onDelete('cascade');
            $table->foreignId('questions_id')
                ->constrained('questions', 'questions_id')
                ->onDelete('cascade');
            $table->foreignId('course_id')
                ->constrained('courses', 'course_id')
                ->onDelete('cascade');
            $table->string('answer_text'); // Nội dung câu trả lời của học sinh
            $table->datetime('answered_at'); // Ngày giờ học sinh trả lời câu hỏi
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_answers');
    }
};
