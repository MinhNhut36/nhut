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
        Schema::create('lesson_part_scores', function (Blueprint $table) {
            $table->id('score_id');
            $table->foreignId('lesson_part_id')
                ->constrained('lesson_parts', 'lesson_part_id')
                ->onDelete('cascade');
            $table->foreignId('course_id')
                ->constrained('courses', 'course_id')
                ->onDelete('cascade');
            $table->integer('attempt_no'); // Điểm số của học sinh cho phần học
            $table->double('score'); // Điểm số của học sinh cho phần học
            $table->integer('total_questions'); // Tổng số câu hỏi trong phần học
            $table->integer('correct_answers'); // Số câu trả lời đúng của học sinh
            $table->datetime('submit_time'); // Ngày nộp bài của
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lesson_part_scores');
    }
};
