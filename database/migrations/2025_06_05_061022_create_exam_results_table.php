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
        Schema::create('exam_results', function (Blueprint $table) {
            $table->id('exam_result_id');
            $table->foreignId('student_id')
                  ->constrained('students', 'student_id')
                  ->onDelete('cascade');
            $table->foreignId('course_id')
                  ->constrained('courses', 'course_id')
                  ->onDelete('cascade'); 
            $table->date('exam_date'); // Ngày thi
            $table->double('lisstening_score')->nullable(); // Điểm nghe
            $table->double('reading_score')->nullable(); // Điểm đọc
            $table->double('speaking_score')->nullable(); // Điểm nói   
            $table->double('writing_score')->nullable(); // Điểm viết
            $table->integer('overall_status'); // Điểm tổng kết 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_results');
    }
};
