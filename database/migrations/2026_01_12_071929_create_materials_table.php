<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama bahan (Kain, Benang)
            $table->string('unit'); // Satuan (Meter, Pcs, Roll)
            $table->integer('stock')->default(0); // Jumlah stok
            $table->integer('minimum_stock')->default(10); // Batas aman stok
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('materials');
    }
};
