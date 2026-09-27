<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Assignment;

class DashboardController extends Controller
{
    public function index()
    {
        $assignments = Assignment::with('project')
                        ->where('user_id', Auth::id())
                        // UBAH DISINI: Ambil status in_progress DAN checking
                        ->whereIn('status', ['in_progress', 'checking'])
                        ->latest()
                        ->get();

        return view('dashboard', compact('assignments'));
    }

    public function updateProgress(Request $request, $id)
    {
        $request->validate([
            'completed_amount' => 'required|integer|min:1'
        ]);

        $assignment = Assignment::findOrFail($id);

        if($assignment->user_id != Auth::id()) {
            return back()->with('error', 'Unauthorized');
        }

        // Hitung total baru
        $newTotal = $assignment->qty_completed + $request->completed_amount;

        if($newTotal > $assignment->qty_assigned) {
             return back()->with('error', 'Jumlah melebihi target tugas!');
        }

        $assignment->qty_completed = $newTotal;

        // --- LOGIC BARU DISINI ---
        // Jika target tercapai (5/5), status jadi CHECKING
        if($assignment->qty_completed >= $assignment->qty_assigned) {
            $assignment->status = 'checking';
        } else {
            // Jika belum (misal 3/5), pastikan tetap in_progress
            $assignment->status = 'in_progress';
        }

        $assignment->save();

        // Redirect kembali
        return back()->with('success', 'Progress berhasil diupdate! Silahkan lapor Admin jika sudah selesai.');
    }
}
