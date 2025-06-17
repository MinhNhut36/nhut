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
            $table->unsignedBigInteger('author_id'); // ID của tác giả comment (student hoặc teacher)
            $table->enum('author_type', ['student', 'teacher']); // Loại tác giả
            $table->longText('content'); // Nội dung comment
            $table->integer('status')->default(1); // 1: hoạt động, 0: ẩn
            $table->timestamps();

            // Index cho hiệu suất truy vấn
            $table->index(['post_id', 'created_at']);
            $table->index(['author_id', 'author_type']);
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
