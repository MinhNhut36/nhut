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
        Schema::create('questions', function (Blueprint $table) {
            $table->id('questions_id');
            $table->foreignId('contents_id')
                    ->constrained('lesson_part_contents', 'contents_id')
                    ->onDelete('cascade');
            $table->enum('question_type', ['single_choice', 'multiple_choice', 'true_false', 'short_answer'])
                  ->comment('Loại câu hỏi: single_choice, multiple_choice, true_false, short_answer');
            $table->string('question_text'); // Nội dung câu hỏi
            $table->longText('media_url')->nullable(); // URL của media liên quan đến câu hỏi (nếu có)
            $table->integer('order_index'); // Thứ tự của câu hỏi trong phần học
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
