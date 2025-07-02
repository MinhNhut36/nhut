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
        Schema::create('student_progress', function (Blueprint $table) {
            $table->id('progress_id');
            $table->unsignedBigInteger('score_id');
            $table->boolean('completion_status')->default(false);
            $table->dateTime('last_updated')->nullable();
            $table->timestamps();

            $table->foreign('score_id')->references('score_id')->on('lesson_part_scores')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_progress');
    }
};
