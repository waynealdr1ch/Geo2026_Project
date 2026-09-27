<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('assignments', function (Blueprint $table) {
            $table->id();

            // 1. Relasi ke Tabel Users (Penjahit)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // 2. Relasi ke Tabel Projects (Project Induk)
            $table->foreignId('project_id')->constrained()->onDelete('cascade');

            // 3. Kolom Jumlah Tugas
            $table->integer('qty_assigned'); // Berapa baju yang dibawa penjahit

            $table->integer('qty_completed')->default(0); // Berapa yang sudah jadi

            // --- BAGIAN INI SUDAH DIPERBAIKI ---
            // Menambahkan 'checking' ke dalam daftar status yang diperbolehkan
            $table->enum('status', ['in_progress', 'checking', 'completed'])->default('in_progress');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assignments');
    }
};
