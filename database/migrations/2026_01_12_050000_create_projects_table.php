<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();

            // --- Field Data Utama ---
            $table->date('order_date');
            $table->string('customer_name');
            $table->string('name');

            // --- Field Angka & Keuangan ---
            $table->integer('total_qty');
            $table->decimal('price_per_qty', 15, 2);
            $table->decimal('total_price', 15, 2);
            $table->decimal('budget', 15, 2)->nullable();

            // --- [PERBAIKAN] ---
            // Hapus "->after('budget')". Cukup taruh baris ini di bawah budget.
            $table->decimal('profit', 15, 2)->nullable();

            // --- Field Sistem ---
            $table->integer('remaining_qty');
            $table->enum('status', ['not_started', 'in_progress', 'completed'])->default('not_started');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
