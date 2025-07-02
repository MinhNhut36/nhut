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
        Schema::create('students', function (Blueprint $table) {
            $table->id('student_id');
            $table->longText('avatar')->nullable(); // Hình đại diện
            $table->string('fullname');
            $table->string('username');
            $table->string('password');
            $table->date('date_of_birth');
            $table->integer('gender'); // 1: Nam, 2: Nữ, 3: Khác
            $table->string('email');
            $table->integer('is_status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
