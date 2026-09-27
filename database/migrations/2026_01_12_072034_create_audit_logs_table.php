<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();

            // Admin yang melakukan aksi
            $table->foreignId('user_id')->constrained('users');

            $table->string('action'); // ex: CREATE_PROJECT, ASSIGN_TASK
            $table->text('description'); // Detail kejadian
            $table->timestamps(); // Mencatat kapan kejadiannya
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
