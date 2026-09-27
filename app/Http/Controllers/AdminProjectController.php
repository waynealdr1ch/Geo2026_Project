<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use Illuminate\Support\Facades\DB;

class AdminProjectController extends Controller
{
    public function index()
    {
        $projects = Project::latest()->paginate(10);
        return view('admin.projects.index', compact('projects'));
    }

    public function create()
    {
        return view('admin.projects.create');
    }

    // Proses Simpan Data
    public function store(Request $request)
    {
        // 1. Validasi Data Statis (Header)
        $request->validate([
            'order_date' => 'required|date',
            'customer_name' => 'required|string|max:255',
            'items' => 'required|array|min:1', // Harus ada minimal 1 item
        ]);

        // Gunakan Database Transaction agar jika 1 item gagal, semua batal (aman)
        DB::transaction(function () use ($request) {

            // --- [BARU] Generate Kode Unik Instansi Sekali Saja ---
            $uniqueCode = $this->generateCustomerCode($request->customer_name);

            // 2. Looping setiap item yang diinput
            foreach ($request->items as $item) {

                // Bersihkan Format Titik (Ribuan)
                $cleanQty = isset($item['total_qty']) ? str_replace('.', '', $item['total_qty']) : 0;
                $cleanPrice = isset($item['price_per_qty']) ? str_replace('.', '', $item['price_per_qty']) : 0;
                $cleanTotal = isset($item['total_price']) ? str_replace('.', '', $item['total_price']) : 0;
                $cleanBudget = isset($item['budget']) ? str_replace('.', '', $item['budget']) : null;
                $profit = $cleanTotal - $cleanBudget;

                // --- [BARU] Gabungkan Nama Item dengan Kode Unik ---
                // Format: "Nama Item - KODE" (Contoh: Seragam Batik - SP5)
                $finalItemName = $item['name'] . ' - ' . $uniqueCode;

                // Create Project per Item
                Project::create([
                    // Data Statis (Sama untuk semua item di sesi ini)
                    'order_date' => $request->order_date,
                    'customer_name' => $request->customer_name,

                    // Data Dinamis (Beda tiap item)
                    'name' => $finalItemName, // <--- Pakai nama baru yang ada kodenya
                    'total_qty' => $cleanQty,
                    'price_per_qty' => $cleanPrice,
                    'total_price' => $cleanTotal,
                    'budget' => $cleanBudget,
                    'profit' => $profit,

                    // System Defaults
                    'remaining_qty' => $cleanQty,
                    'status' => 'not_started'
                ]);
            }
        });

        return redirect()->route('admin.projects.index')->with('success', 'Berhasil menambahkan ' . count($request->items) . ' project baru dengan kode unik!');
    }

    public function edit($id)
    {
        $project = Project::findOrFail($id);
        return view('admin.projects.edit', compact('project'));
    }

    // 3. PROSES UPDATE DATA
    public function update(Request $request, $id)
    {
        $project = Project::findOrFail($id);

        // Bersihkan Titik (Sama seperti store)
        $request->merge([
            'total_qty' => $request->total_qty ? str_replace('.', '', $request->total_qty) : null,
            'price_per_qty' => $request->price_per_qty ? str_replace('.', '', $request->price_per_qty) : null,
            'total_price' => $request->total_price ? str_replace('.', '', $request->total_price) : null,
            'budget' => $request->budget ? str_replace('.', '', $request->budget) : null,
        ]);

        $request->validate([
            'order_date' => 'required|date',
            'customer_name' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'total_qty' => 'required|numeric|min:1',
            'price_per_qty' => 'required|numeric|min:0',
            'total_price' => 'required|numeric|min:0',
            'budget' => 'nullable|numeric|min:0',
            'status' => 'required|in:not_started,in_progress,completed', // Tambahan validasi status
        ]);

        // Update Data
        $project->update([
            'order_date' => $request->order_date,
            'customer_name' => $request->customer_name,
            'name' => $request->name,
            'total_qty' => $request->total_qty,
            'price_per_qty' => $request->price_per_qty,
            'total_price' => $request->total_price,
            'budget' => $request->budget,
            'status' => $request->status,
            'profit' => $request->total_price - $request->budget,
            // remaining_qty sebaiknya tidak direset total, kecuali logika bisnis meminta demikian
        ]);

        return redirect()->route('admin.projects.index')->with('success', 'Data project berhasil diperbarui!');
    }

    // --- [BARU] Helper Function: Generate Kode Singkatan ---
    private function generateCustomerCode($customerName)
    {
        // Hapus spasi berlebih di awal/akhir
        $name = trim($customerName);

        // Pecah kalimat menjadi array kata
        $words = explode(' ', $name);
        $code = '';

        // LOGIKA:
        if (count($words) > 1) {
            // KASUS A: Lebih dari 1 kata (Ambil huruf depan tiap kata)
            // Contoh: "SD Petra 5" -> ["SD", "Petra", "5"] -> "SP5"
            foreach ($words as $word) {
                // Ambil 1 huruf pertama dari setiap kata
                $code .= substr($word, 0, 1);
            }
        } else {
            // KASUS B: Cuma 1 kata (Ambil huruf awal dan akhir)
            // Contoh: "Pertamina" -> "Pa"
            $firstChar = substr($name, 0, 1);
            $lastChar = substr($name, -1);
            $code = $firstChar . $lastChar;
        }

        // Ubah jadi huruf besar semua (uppercase)
        return strtoupper($code);
    }
}
