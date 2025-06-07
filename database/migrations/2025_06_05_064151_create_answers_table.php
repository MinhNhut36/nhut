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
        Schema::create('answers', function (Blueprint $table) {
            $table->id('answers_id');
            $table->foreignId('questions_id')
                    ->constrained('questions', 'questions_id')
                    ->onDelete('cascade');
            $table->string('match_key');
            $table->string('answer_text'); // Nội dung câu trả lời
            $table->integer('is_correct'); // 1 nếu là câu trả lời đúng, 0 nếu không
            $table->longText('feedback')->nullable(); // Phản hồi cho câu trả lời, có thể là giải thích hoặc gợi ý
            $table->longText('media_url')->nullable(); // URL của media liên quan đến câu trả lời (nếu có)
            $table->integer('order_index'); // Thứ tự của câu trả lời trong câu hỏi
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('answers');
    }
};
