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
        Schema::create('class_posts', function (Blueprint $table) {
            $table->id('post_id');
            $table->foreignId('course_id')
                  ->constrained('courses', 'course_id')
                  ->onDelete('cascade');
            $table->unsignedBigInteger('teacher_id'); // ID của teacher (chỉ teacher mới tạo được post)
            $table->string('title'); // Tiêu đề bài viết
            $table->longText('content'); // Nội dung bài viết
            $table->integer('status')->default(1); // 1: hoạt động, 0: ẩn
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('teacher_id')->references('teacher_id')->on('teachers')->onDelete('cascade');

            // Index cho hiệu suất truy vấn
            $table->index(['course_id', 'created_at']);
            $table->index(['teacher_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_posts');
    }
};
