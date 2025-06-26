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
        Schema::create('class_post_comments', function (Blueprint $table) {
            $table->id('comment_id');
            $table->foreignId('post_id')
                  ->constrained('class_posts', 'post_id')
                  ->onDelete('cascade');
            $table->unsignedBigInteger('student_id')->nullable(); // ID của student (nếu student comment)
            $table->unsignedBigInteger('teacher_id')->nullable(); // ID của teacher (nếu teacher comment)
            $table->longText('content'); // Nội dung comment
            $table->integer('status')->default(1); // 1: hoạt động, 0: ẩn
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('student_id')->references('student_id')->on('students')->onDelete('cascade');
            $table->foreign('teacher_id')->references('teacher_id')->on('teachers')->onDelete('cascade');

            // Index cho hiệu suất truy vấn
            $table->index(['post_id', 'created_at']);
            $table->index(['student_id', 'teacher_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_post_comments');
    }
};
