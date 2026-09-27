<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Panggil UserSeeder yang sudah kamu buat tadi
        $this->call([
            UserSeeder::class,
            ProjectSeeder::class,
        ]);
    }
}
