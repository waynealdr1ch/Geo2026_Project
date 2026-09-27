<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    public function create()
{
    // Menampilkan form buat project
    return view('projects.create');
}

public function scanner()
{
    // Menampilkan layar scanner
    return view('projects.scanner');
}

    // --- LANGKAH 2: Create Project & Auto-Generate Tasks ---
    public function store(Request $request)
    {
        // 1. Validasi Input
        $validated = $request->validate([
            'name' => 'required|string',
            'total_quantity' => 'required|integer|min:1',
            'deadline' => 'required|date',
        ]);

        DB::beginTransaction(); // Mulai transaksi database
        try {
            // 2. Buat Project Header
            $project = Project::create([
                'name' => $validated['name'],
                'total_quantity' => $validated['total_quantity'],
                'status' => 'pending',
                'deadline' => $validated['deadline']
            ]);

            // 3. LOGIC LOOPING: Generate Task sebanyak quantity
            $tasks = [];
            for ($i = 1; $i <= $project->total_quantity; $i++) {
                // Generate Kode Unik: IDProject - Urutan (Misal: 12-001)
                $code = $project->id . '-' . str_pad($i, 3, '0', STR_PAD_LEFT);

                $tasks[] = [
                    'project_id' => $project->id,
                    'task_code' => $code,
                    'status' => 'available',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            // Insert Batch (Lebih cepat daripada insert satu-satu)
            Task::insert($tasks);

            // 4. Catat ke Audit Log
            AuditLog::create([
                'user_id' => Auth::id(), // Pastikan user sudah login
                'action' => 'CREATE_PROJECT',
                'description' => "Membuat project {$project->name} dengan {$project->total_quantity} tasks."
            ]);

            DB::commit(); // Simpan perubahan
            return response()->json(['message' => 'Project & Tasks Generated Successfully']);

        } catch (\Exception $e) {
            DB::rollBack(); // Batalkan jika ada error
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // --- LANGKAH 3A: Penjahit Mengambil Pekerjaan ---
    public function takeTask(Request $request)
    {
        $request->validate([
            'code' => 'required|exists:tasks,task_code',
            'tailor_id' => 'required' // Sesuaikan dengan table users/tailors kamu
        ]);

        // Cari task berdasarkan barcode
        $task = Task::where('task_code', $request->code)->first();

        if ($task->status !== 'available') {
            return response()->json(['error' => 'Task ini sudah diambil orang lain atau sudah selesai!'], 400);
        }

        $task->update([
            'assigned_to' => $request->tailor_id,
            'status' => 'taken',
            'taken_at' => now(),
        ]);

        // Update Status Project jadi 'processing' jika masih 'pending'
        if ($task->project->status === 'pending') {
            $task->project->update(['status' => 'processing']);
        }

        return response()->json(['message' => 'Silakan kerjakan task ini', 'data' => $task]);
    }

    // --- LANGKAH 3B: Penjahit Menyetor Pekerjaan ---
    public function submitTask(Request $request)
    {
        $request->validate([
            'code' => 'required|exists:tasks,task_code',
        ]);

        $task = Task::where('task_code', $request->code)->first();

        // Validasi sederhana
        if ($task->status !== 'taken') {
             return response()->json(['error' => 'Task belum diambil atau sudah selesai, tidak bisa disetor.'], 400);
        }

        $task->update([
            'status' => 'done',
            'finished_at' => now(),
        ]);

        // Cek apakah semua task di project ini sudah selesai?
        $remainingTasks = Task::where('project_id', $task->project_id)
                              ->where('status', '!=', 'done')
                              ->count();

        if ($remainingTasks == 0) {
            $task->project->update(['status' => 'completed']);
        }

        return response()->json(['message' => 'Terima kasih, pekerjaan diterima', 'data' => $task]);
    }
}
