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
        Schema::create('student_progress', function (Blueprint $table) {
            $table->id('progress_id');
            $table->foreignId('student_id')
                ->constrained('students', 'student_id')
                ->onDelete('cascade');
            $table->foreignId('score_id')
                ->constrained('lesson_part_scores', 'score_id')
                ->onDelete('cascade');
            $table->integer('completion_status')->default(0); // Trạng thái hoàn thành: 0 - chưa hoàn thành, 1 - đã hoàn thành
            $table->datetime('last_updated')->nullable(); // Ngày cập nhật cuối cùng
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_progress');
    }
};
