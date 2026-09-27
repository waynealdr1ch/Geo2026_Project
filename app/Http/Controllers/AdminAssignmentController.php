<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Assignment;
use App\Models\Project;
use App\Models\User;

class AdminAssignmentController extends Controller
{
    // 1. TAMPILKAN LIST TUGAS
    public function index()
    {
        $assignments = Assignment::with(['user', 'project'])->latest()->paginate(10);
        return view('admin.assignments.index', compact('assignments'));
    }

    // 2. FORM ASSIGNMENT (BARANG KELUAR)
    public function create()
    {
        $projects = Project::where('status', '!=', 'completed')
                           ->where('remaining_qty', '>', 0)
                           ->get();

        $users = User::where('role', 'member')->get();

        return view('admin.assignments.create', compact('projects', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'project_id' => 'required',
            'user_id'    => 'required',
            'qty_assigned' => 'required|integer|min:1',
        ]);

        // CEK 1: Apakah user ini sibuk?
        // UBAH DISINI: User dianggap sibuk jika in_progress ATAU sedang checking (antri verif)
        $isBusy = Assignment::where('user_id', $request->user_id)
                            ->whereIn('status', ['in_progress', 'checking'])
                            ->exists();

        if ($isBusy) {
            return back()->with('error', 'GAGAL! Penjahit ini masih memiliki tugas aktif atau belum diverifikasi.');
        }

        $project = Project::find($request->project_id);

        if ($request->qty_assigned > $project->remaining_qty) {
            return back()->with('error', "Stok project kurang. Sisa: {$project->remaining_qty}");
        }

        Assignment::create([
            'project_id' => $request->project_id,
            'user_id'    => $request->user_id,
            'qty_assigned' => $request->qty_assigned,
            'qty_completed' => 0,
            'status'     => 'in_progress'
        ]);

        $project->decrement('remaining_qty', $request->qty_assigned);

        if ($project->status == 'not_started') {
            $project->update(['status' => 'in_progress']);
        }

        return redirect()->route('admin.assignments.index')->with('success', 'Tugas diberikan.');
    }

    public function scannerCheckIn()
    {
        $users = User::where('role', 'member')->orderBy('name', 'asc')->get();

        return view('admin.assignments.scanner', compact('users'));
    }

    // --- LOGIC SCANNER DIUBAH DISINI ---
    public function checkUserActiveTask($userId)
    {
        // Cari assignment yang statusnya CHECKING (User sudah selesai jahit, minta verif)
        // Opsional: Jika ingin admin bisa scan yang belum selesai juga, gunakan whereIn
        $task = Assignment::with(['project', 'user'])
                    ->where('user_id', $userId)
                    ->where('status', 'checking') // UBAH: Cari yang statusnya checking
                    ->first();

        if (!$task) {
            // Cek double, barangkali user mau scan tapi status masih in_progress (belum lapor selesai di aplikasi)
            $stillWorking = Assignment::where('user_id', $userId)->where('status', 'in_progress')->exists();

            if($stillWorking) {
                return response()->json(['status' => 'empty', 'message' => 'User ini belum menyelesaikan pekerjaannya di aplikasi (Status masih In Progress).']);
            }

            return response()->json(['status' => 'empty', 'message' => 'User ini tidak memiliki barang untuk diverifikasi.']);
        }

        return response()->json(['status' => 'found', 'data' => $task]);
    }

    // PROSES: Admin Verifikasi & Terima Barang
    public function verifyTask(Request $request)
    {
        $request->validate([
            'assignment_id' => 'required|exists:assignments,id',
            'qty_accepted'  => 'required|integer|min:1',
            'notes'         => 'nullable|string'
        ]);

        $assignment = Assignment::find($request->assignment_id);

        if ($request->qty_accepted > $assignment->qty_assigned) {
            return response()->json(['status' => 'error', 'message' => 'Jumlah barang berlebih!'], 400);
        }

        // UPDATE STATUS JADI COMPLETED
        $assignment->update([
            'qty_completed' => $request->qty_accepted,
            'status'        => 'completed', // FINAL STATUS
            'notes'         => $request->notes
        ]);

        // Cek Project Selesai Total?
        $project = Project::find($assignment->project_id);
        $totalFinished = Assignment::where('project_id', $project->id)
                                   ->where('status', 'completed')
                                   ->sum('qty_completed');

        if ($totalFinished >= $project->total_qty) {
            $project->update(['status' => 'completed']);
            $msg = "Verifikasi Berhasil! Project SELESAI.";
        } else {
            $msg = "Verifikasi Berhasil. Barang diterima.";
        }

        return response()->json(['status' => 'success', 'message' => $msg]);
    }
}
