<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        $faker = \Faker\Factory::create('id_ID');

        // --- 1. ADMIN (DAOSHEN) ---
        if (!User::where('email', 'daoshenokawida@gmail.com')->exists()) {
            User::create([
                'name' => 'Daoshen',
                'username' => 'daoshen',
                'email' => 'daoshenokawida@gmail.com',
                'password' => Hash::make('daoshen'),
                'role' => 'admin',
                'phone_number' => '+6282110768015',
                'address' => 'Jl. Achmad Jais Kec. Genteng, Surabaya',
            ]);
        }

        // --- 2. IMPORT MEMBER DARI CSV ---
        // Pastikan nama file ini sesuai dengan file CSV yang sudah FIX (bukan Excel rename)
        $csvPath = database_path('seeders/csv/DATA PENJAHIT KSMB.csv');

        if (!File::exists($csvPath)) {
            $this->command->error("File CSV tidak ditemukan di: $csvPath");
            return;
        }

        $file = fopen($csvPath, 'r');

        // SKIP 9 BARIS PERTAMA (Header)
        for ($i = 0; $i < 9; $i++) {
            fgetcsv($file);
        }

        $count = 0;

        while (($row = fgetcsv($file)) !== false) {

            // Cek Nama (Index 2)
            $fullName = isset($row[2]) ? trim($row[2]) : null;

            // Pengaman tambahan untuk karakter aneh (jika file masih belum bersih)
            if (empty($fullName) || (preg_match('/[^\x20-\x7E]/', $fullName) && strlen($fullName) > 50)) {
                continue;
            }

            // Cek Alamat & Kecamatan
            $addressRaw = isset($row[4]) ? trim($row[4]) : '';
            $district = isset($row[5]) ? trim($row[5]) : '';

            // --- LOGIC USERNAME ---
            $username = $this->generateUsername($fullName);
            $emailDummy = $username . '@koperasisb.com';

            if (User::where('username', $username)->exists()) {
                $username = $username . rand(1, 99);
                $emailDummy = $username . '@koperasisb.com';
            }

            // Gabungkan Alamat
            $fullAddress = "$addressRaw, Kec. $district, Surabaya";

            // Generate No HP +62
            // Format: +62 8xx xxxx xxxx (Total 10-12 digit angka acak setelah +62)
            $phoneNumber = '+62' . $faker->numerify('8##########');

            User::create([
                'name'         => $fullName,
                'username'     => $username,
                'email'        => $emailDummy,
                'password'     => Hash::make('123'),
                'role'         => 'member',
                'phone_number' => $phoneNumber, // <--- SUDAH DIGANTI +62
                'address'      => $fullAddress,
            ]);

            $count++;
        }

        fclose($file);
        $this->command->info("Berhasil import $count member dari CSV.");
    }

    private function generateUsername($name)
    {
        $cleanName = str_replace('.', '', $name);
        $cleanName = preg_replace('/\s+/', ' ', $cleanName);

        $parts = explode(' ', trim($cleanName));

        if (count($parts) > 1 && strlen($parts[0]) == 1) {
            array_shift($parts);
        }

        if (count($parts) >= 2) {
            $finalUsername = $parts[0] . '_' . $parts[1];
        } else {
            $finalUsername = $parts[0];
        }

        return strtolower(preg_replace('/[^a-zA-Z0-9_]/', '', $finalUsername));
    }
}
