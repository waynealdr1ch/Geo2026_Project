<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Project;
use App\Models\Assignment; // Tambahkan ini
use App\Models\User;

class AdminController extends Controller
{
    // ... (Fungsi login & showLoginForm biarkan sama) ...
    public function showLoginForm()
    {
        if (Auth::check() && Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $fieldType = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if (Auth::attempt([$fieldType => $request->username, 'password' => $request->password])) {
            $user = Auth::user();
            if ($user->role === 'admin') {
                $request->session()->regenerate();
                return redirect()->intended(route('admin.dashboard'));
            } else {
                Auth::logout();
                return back()->with('error', 'Akun Anda bukan Administrator.');
            }
        }

        return back()->withErrors(['username' => 'Kredensial tidak cocok.']);
    }

    // --- PERBAIKAN DI SINI ---
    public function dashboard()
    {
        // 1. Hitung Total Project
        $totalProjects = Project::count();

        // 2. Hitung Pending Tasks (Total item yang sedang dijahit / in_progress)
        // Kita ambil sum(qty_assigned) dari assignment yang statusnya 'in_progress'
        $pendingTasks = Assignment::where('status', 'in_progress')->sum('qty_assigned');

        // 3. Stok Material (Sementara kita nol-kan dulu karena tabel materials belum ada)
        $lowStockMaterials = 0;

        // Kirim semua variabel ke view
        return view('admin.dashboard', compact('totalProjects', 'pendingTasks', 'lowStockMaterials'));
    }
}
