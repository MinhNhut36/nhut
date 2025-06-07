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
        Schema::create('lesson_parts', function (Blueprint $table) {
            $table->id('lesson_part_id');
            $table->string('level');
            $table->string('part_type'); 
            $table->longText('content'); // Nội dung của phần học
            $table->integer('order_index'); // Thứ tự của phần trong bài học
            $table->timestamps();
            $table->foreign('level')
                    ->references('level')
                    ->on('lessons')
                    ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lesson_parts');
    }
};
