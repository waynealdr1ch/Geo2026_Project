<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Tambah kolom username setelah nama
            $table->string('username')->unique()->after('name');

            // Tambah kolom google_id buat simpan ID dari Google
            $table->string('google_id')->nullable()->after('email');

            // Password jadi nullable (jaga-jaga)
            $table->string('password')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['username', 'google_id']);
        });
    }
};
