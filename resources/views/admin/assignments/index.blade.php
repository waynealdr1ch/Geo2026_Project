<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tracking Tugas - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Poppins', sans-serif; }</style>
</head>
<body class="bg-slate-50 min-h-screen p-4 md:p-8">

    <div class="max-w-7xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Task Management</h1>
                <p class="text-slate-500 text-sm">Monitoring barang keluar ke penjahit.</p>
            </div>
            <div class="flex gap-3">
                 <a href="{{ route('admin.dashboard') }}" class="bg-white border border-slate-300 text-slate-600 px-4 py-2 rounded-xl text-sm font-bold hover:bg-slate-50 transition">
                    &larr; Dashboard
                </a>
                <a href="{{ route('admin.assignments.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-xl text-sm font-bold hover:bg-indigo-700 transition shadow-lg shadow-indigo-500/30">
                    + Assign Tugas Baru
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm">
                <p class="font-bold">Sukses!</p>
                <p>{{ session('success') }}</p>
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider">
                            <th class="p-4 font-bold border-b border-slate-100">Task ID</th>
                            <th class="p-4 font-bold border-b border-slate-100">Project Item</th>
                            <th class="p-4 font-bold border-b border-slate-100">Penjahit (User)</th>
                            <th class="p-4 font-bold border-b border-slate-100 text-center">Qty Assigned</th>
                            <th class="p-4 font-bold border-b border-slate-100 text-center">Progress</th>
                            <th class="p-4 font-bold border-b border-slate-100 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-slate-700 divide-y divide-slate-100">
                        @forelse($assignments as $task)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="p-4 font-mono font-bold text-indigo-600">
                                #TASK-{{ str_pad($task->id, 5, '0', STR_PAD_LEFT) }}
                            </td>

                            <td class="p-4">
                                <span class="font-bold block">{{ $task->project->name ?? 'Deleted Project' }}</span>
                                <span class="text-xs text-slate-400">{{ \Carbon\Carbon::parse($task->created_at)->format('d M Y, H:i') }}</span>
                            </td>

                            <td class="p-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-full bg-slate-200 flex items-center justify-center text-xs font-bold text-slate-600">
                                        {{ substr($task->user->name ?? 'U', 0, 1) }}
                                    </div>
                                    <div>
                                        <span class="block font-bold">{{ $task->user->name ?? 'Unknown' }}</span>
                                        <span class="text-[10px] text-slate-400">ID: {{ $task->user_id }}</span>
                                    </div>
                                </div>
                            </td>

                            <td class="p-4 text-center font-bold text-lg">{{ $task->qty_assigned }}</td>

                            <td class="p-4 text-center">
                                <div class="w-24 bg-slate-100 rounded-full h-2 mx-auto overflow-hidden">
                                    @php
                                        $percent = $task->qty_assigned > 0 ? ($task->qty_completed / $task->qty_assigned) * 100 : 0;

                                        // Warna Bar: Jika Checking/Completed jadi Hijau, kalau masih progress jadi Kuning/Biru
                                        $barColor = 'bg-yellow-500';
                                        if($task->status == 'checking') $barColor = 'bg-blue-500';
                                        if($task->status == 'completed') $barColor = 'bg-green-500';
                                    @endphp
                                    <div class="{{ $barColor }} h-2 rounded-full" style="width: {{ $percent }}%"></div>
                                </div>
                                <span class="text-[10px] text-slate-400 mt-1 block">{{ $task->qty_completed }} / {{ $task->qty_assigned }} Selesai</span>
                            </td>

                            <td class="p-4 text-center">
                                @if($task->status == 'completed')
                                    <span class="px-3 py-1 rounded-full text-[10px] font-bold bg-green-100 text-green-700 border border-green-200 uppercase tracking-wide">
                                        Selesai
                                    </span>

                                @elseif($task->status == 'checking')
                                    <span class="px-3 py-1 rounded-full text-[10px] font-bold bg-blue-100 text-blue-700 border border-blue-200 uppercase tracking-wide animate-pulse">
                                        Pengecekan
                                    </span>

                                @else
                                    <span class="px-3 py-1 rounded-full text-[10px] font-bold bg-yellow-100 text-yellow-700 border border-yellow-200 uppercase tracking-wide">
                                        Proses Jahit
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-slate-400">
                                Belum ada tugas yang diberikan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-slate-100">
                {{ $assignments->links() }}
            </div>
        </div>
    </div>
</body>
</html>
