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
        Schema::create('teachers', function (Blueprint $table) {
            $table->bigIncrements('teacher_id');
            $table->longText('avatar')->nullable();
            $table->string('fullname');
            $table->string('username');
            $table->string('password');
            $table->date('date_of_birth');
            $table->integer('gender')->nullable(); // 1: Nam, 2: Nữ, 3: Khác
            $table->string('email');
            $table->integer('is_status')->default(1); // 1: hoạt động, 0: không hoạt động
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};
