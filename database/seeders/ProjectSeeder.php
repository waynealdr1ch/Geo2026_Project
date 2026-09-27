<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Project;
use Illuminate\Support\Facades\File;
use Faker\Factory as Faker;
use Carbon\Carbon;

class ProjectSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('id_ID');

        // Pastikan path ini sesuai lokasi file CSV kamu
        $csvPath = database_path('seeders/csv/df_forecast.csv');

        if (!File::exists($csvPath)) {
            $this->command->error("File tidak ditemukan di: $csvPath");
            return;
        }

        $file = fopen($csvPath, 'r');
        $header = fgetcsv($file); // Skip baris pertama (Header)

        $dataProjects = [];
        $items = ['Seragam Pramuka', 'Kemeja Batik', 'Kaos Olahraga', 'Jaket Almamater', 'Topi Dinas', 'Rompi Safety', 'Celana Training'];

        $rowCount = 0;

        while (($row = fgetcsv($file)) !== false) {
            // Mapping Kolom dari CSV (Sesuai urutan yang kamu sebutkan)
            // 0: order_date, 1: total_price, 2: budget, 3: profit, 4: total_qty

            $rawDate = $row[0];

            // --- FIX TANGGAL ---
            // Menggunakan format 'j/n/Y' untuk menangani tanggal tanpa nol di depan (misal: 1/1/2023)
            try {
                $orderDate = Carbon::createFromFormat('j/n/Y', $rawDate)->format('Y-m-d');
            } catch (\Exception $e) {
                // Fallback jika format agak beda, coba format standar d/m/Y
                try {
                    $orderDate = Carbon::createFromFormat('d/m/Y', $rawDate)->format('Y-m-d');
                } catch (\Exception $ex) {
                    $this->command->warn("Gagal baca tanggal baris ke-" . ($rowCount+1) . ": $rawDate. Skip.");
                    continue;
                }
            }

            $totalPrice = (float) $row[1];
            $budget     = (float) $row[2];
            $profit     = (float) $row[3];
            $totalQty   = (int) $row[4];

            // Hitung harga satuan
            $pricePerQty = $totalQty > 0 ? $totalPrice / $totalQty : 0;

            // Data Dummy untuk field yang tidak ada di CSV
            $customerName = $this->getRandomCustomer($faker);
            $itemNameRaw  = $faker->randomElement($items);
            $uniqueCode   = $this->generateCode($customerName);
            $finalName    = "$itemNameRaw - $uniqueCode";

            $dataProjects[] = [
                'order_date'    => $orderDate,
                'customer_name' => $customerName,
                'name'          => $finalName,
                'total_qty'     => $totalQty,
                'price_per_qty' => $pricePerQty,
                'total_price'   => $totalPrice,
                'budget'        => $budget,
                'profit'        => $profit,
                'remaining_qty' => 0,          // Sisa 0 karena status completed
                'status'        => 'completed', // Sesuai request
                'created_at'    => now(),
                'updated_at'    => now(),
            ];

            $rowCount++;
        }

        fclose($file);

        // Insert sekaligus (Batch Insert)
        if (count($dataProjects) > 0) {
            Project::insert($dataProjects);
            $this->command->info("Berhasil import $rowCount data (Jan 2023 - Nov 2025) ke tabel projects!");
        } else {
            $this->command->warn("Tidak ada data yang diimport.");
        }
    }

    private function getRandomCustomer($faker)
    {
        $prefixes = ['SD', 'SMP', 'SMA', 'SMK', 'Universitas', 'Dinas', 'PT', 'CV'];
        // Menggunakan city agar nama instansi terlihat lebih variatif
        return $faker->randomElement($prefixes) . ' ' . $faker->company . ' ' . $faker->city;
    }

    private function generateCode($name)
    {
        $words = explode(' ', trim($name));
        $code = '';
        if (count($words) > 1) {
            foreach ($words as $word) $code .= substr($word, 0, 1);
        } else {
            $code = substr($name, 0, 1) . substr($name, -1);
        }
        return strtoupper(substr($code, 0, 5));
    }
}
