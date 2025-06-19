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
            $table->enum('question_type', [
                'single_choice',      // Dạng 1: Trắc nghiệm 4 đáp án (1 đáp án đúng)
                'matching',           // Dạng 2: Nối từ với hình ảnh hoặc nghĩa
                'classification',     // Dạng 3: Phân loại từ (danh từ, động từ, tính từ)
                'fill_blank',         // Dạng 4: Điền vào chỗ trống
                'arrangement',        // Dạng 5: Sắp xếp thành câu đúng
                'image_word'          // Dạng 6: Nhìn ảnh sắp xếp thành từ đúng
            ])->comment('Loại câu hỏi: 6 dạng bài tập tiếng Anh');
            $table->string('question_text')->nullable(); // Nội dung câu hỏi
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
