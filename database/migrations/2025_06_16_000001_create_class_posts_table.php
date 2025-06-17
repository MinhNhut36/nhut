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
            $table->unsignedBigInteger('author_id'); // ID của tác giả (student hoặc teacher)
            $table->enum('author_type', ['student', 'teacher']); // Loại tác giả
            $table->string('title'); // Tiêu đề bài viết
            $table->longText('content'); // Nội dung bài viết
            $table->integer('status')->default(1); // 1: hoạt động, 0: ẩn
            $table->timestamps();

            // Index cho hiệu suất truy vấn
            $table->index(['course_id', 'created_at']);
            $table->index(['author_id', 'author_type']);
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
