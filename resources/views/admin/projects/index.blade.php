<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Project - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Poppins', sans-serif; }</style>
</head>
<body class="bg-slate-50 min-h-screen p-4 md:p-8">

    <div class="max-w-7xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Daftar Project</h1>
                <p class="text-slate-500 text-sm">Kelola semua pesanan masuk di sini.</p>
            </div>
            <div class="flex gap-3">
                 <a href="{{ route('admin.dashboard') }}" class="bg-white border border-slate-300 text-slate-600 px-4 py-2 rounded-xl text-sm font-bold hover:bg-slate-50 transition">
                    &larr; Dashboard
                </a>
                <a href="{{ route('admin.projects.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-xl text-sm font-bold hover:bg-blue-700 transition shadow-lg shadow-blue-500/30">
                    + Project Baru
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm" role="alert">
                <p class="font-bold">Berhasil!</p>
                <p>{{ session('success') }}</p>
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider">
                            <th class="p-4 font-bold border-b border-slate-100">Tanggal</th>
                            <th class="p-4 font-bold border-b border-slate-100">Project / Item</th>
                            <th class="p-4 font-bold border-b border-slate-100">Instansi</th>
                            <th class="p-4 font-bold border-b border-slate-100 text-center">Total Qty</th>
                            <th class="p-4 font-bold border-b border-slate-100 text-center">Sisa</th>
                            <th class="p-4 font-bold border-b border-slate-100 text-right">Total Nilai</th>
                            <th class="p-4 font-bold border-b border-slate-100 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-slate-700 divide-y divide-slate-100">
                        @forelse($projects as $project)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="p-4">{{ \Carbon\Carbon::parse($project->order_date)->format('d M Y') }}</td>
                            <td class="p-4">
                                <a href="{{ route('admin.projects.edit', $project->id) }}" class="font-bold text-blue-600 hover:text-blue-800 hover:underline">
                                    {{ $project->name }}
                                </a>
                            </td>
                            <td class="p-4">{{ $project->customer_name }}</td>
                            <td class="p-4 text-center font-bold">{{ number_format($project->total_qty, 0, ',', '.') }}</td>

                            <td class="p-4 text-center">
                                @if($project->remaining_qty == 0)
                                    <span class="text-green-600 font-bold">0</span>
                                @else
                                    <span class="text-slate-500 font-bold">{{ number_format($project->remaining_qty, 0, ',', '.') }}</span>
                                @endif
                            </td>

                            <td class="p-4 text-right font-medium text-emerald-600">Rp {{ number_format($project->total_price, 0, ',', '.') }}</td>
                            <td class="p-4 text-center">
                                @if($project->status == 'not_started')
                                    <span class="px-3 py-1 rounded-full text-[10px] font-bold bg-slate-100 text-slate-500 uppercase">Belum Mulai</span>
                                @elseif($project->status == 'in_progress')
                                    <span class="px-3 py-1 rounded-full text-[10px] font-bold bg-yellow-100 text-yellow-600 uppercase">Proses</span>
                                @else
                                    <span class="px-3 py-1 rounded-full text-[10px] font-bold bg-green-100 text-green-600 uppercase">Selesai</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="p-8 text-center text-slate-400">
                                Belum ada data project. Silakan tambah baru.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-slate-100">
                {{ $projects->links() }}
            </div>
        </div>
    </div>
</body>
</html>
