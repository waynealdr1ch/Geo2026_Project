<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();

            // Relasi ke tabel projects (Jika project dihapus, task ikut terhapus)
            $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');

            // Kode unik untuk Barcode (ex: PRJ01-001)
            $table->string('task_code')->unique();

            // Relasi ke tabel users (Penjahit). Nullable karena awal dibuat belum ada yang ambil.
            // Asumsi tabel bawaan laravel namanya 'users'
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();

            // Status per baju
            $table->enum('status', ['available', 'taken', 'done'])->default('available');

            // Tracking Waktu
            $table->timestamp('taken_at')->nullable(); // Waktu diambil
            $table->timestamp('finished_at')->nullable(); // Waktu disetor

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
