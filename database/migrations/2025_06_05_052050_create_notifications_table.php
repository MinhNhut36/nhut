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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id('notification_id');
            $table->foreignId('admin')
                  ->constrained('users', 'admin_id')
                  ->onDelete('cascade');
            $table->integer('target'); 
            $table->longText('title');
            $table->longText('message');
            $table->date('notification_date');
            $table->integer('status')->default(0); // 0: chưa gửi, 1: đã gửi
            $table->timestamps();
        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
