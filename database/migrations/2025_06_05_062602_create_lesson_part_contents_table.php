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
        Schema::create('lesson_part_contents', function (Blueprint $table) {
            $table->id('contents_id');
            $table->foreignId('lesson_part_id')
                  ->constrained('lesson_parts', 'lesson_part_id')
                  ->onDelete('cascade');
            $table->string('content_type'); // Loại nội dung: video, audio, text, quiz, etc.
            $table->longText('content'); // Nội dung của phần học, có thể là video URL, audio file, text, etc.
            $table->string('mini_game_type'); // Loại mini game nếu có, ví dụ: "word_search", "crossword", etc.
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lesson_part_contents');
    }
};
