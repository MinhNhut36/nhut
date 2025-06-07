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
        Schema::create('student_evaluations', function (Blueprint $table) {
            $table->id('evaluation_id');
            $table->foreignId('student_id')
                ->constrained('students', 'student_id')
                ->onDelete('cascade');
            $table->foreignId('progress_id')
                ->constrained('student_progress', 'progress_id')
                ->onDelete('cascade');
            $table->foreignId('exam_result_id')
                ->constrained('exam_results', 'exam_result_id')
                ->onDelete('cascade');
            $table->date('evaluation_date'); // Ngày đánh giá
            $table->integer('final_status')->default(0); // Trạng thái hoàn thành: 0 - chưa hoàn thành, 1 - đã hoàn thành
            $table->longText('remark')->nullable(); // Nhận xét của giáo viên về tiến độ học tập của học sinh
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_evaluations');
    }
};
