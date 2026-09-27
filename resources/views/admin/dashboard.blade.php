<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Production Control</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen">

    <nav class="bg-slate-900 text-white shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center gap-3">
                    <span class="bg-blue-600 text-white p-2 rounded-lg font-bold text-xs tracking-wider">ADMIN</span>
                    <span class="font-bold text-xl tracking-tight">Production Control</span>
                </div>

                <div class="flex items-center gap-4">
                    <a href="{{ route('dashboard') }}" class="text-slate-300 hover:text-white text-sm font-medium transition">
                        &larr; Dashboard user
                    </a>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-1.5 rounded-full text-xs font-bold transition">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        <div class="mb-8">
            <h1 class="text-3xl font-bold text-slate-800">Ringkasan Produksi</h1>
            <p class="text-slate-500 mt-1">Pantau status project, tugas penjahit, dan verifikasi barang.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
            <a href="{{ route('admin.projects.index') }}" class="group bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center hover:shadow-lg hover:border-blue-200 transition cursor-pointer">
                <div class="p-4 bg-blue-50 text-blue-600 rounded-xl mr-5 group-hover:bg-blue-600 group-hover:text-white transition-colors duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <div>
                    <p class="text-slate-400 text-xs font-bold uppercase tracking-wider group-hover:text-blue-600 transition-colors">Total Projects</p>
                    <p class="text-3xl font-bold text-slate-800">{{ $totalProjects ?? 0 }} <span class="text-sm font-normal text-slate-400">Batch</span></p>
                </div>
            </a>

            <a href="{{ route('admin.assignments.index') }}" class="group bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center hover:shadow-lg hover:border-yellow-200 transition cursor-pointer">
                <div class="p-4 bg-yellow-50 text-yellow-600 rounded-xl mr-5 group-hover:bg-yellow-500 group-hover:text-white transition-colors duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-slate-400 text-xs font-bold uppercase tracking-wider group-hover:text-yellow-600 transition-colors">Sedang Dijahit</p>
                    <p class="text-3xl font-bold text-slate-800">{{ $pendingTasks ?? 0 }} <span class="text-sm font-normal text-slate-400">Pcs</span></p>
                </div>
            </a>

            <a href="http://localhost:8501/" target="_top" class="group bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center hover:shadow-lg hover:border-green-200 transition cursor-pointer">
                <div class="p-4 bg-green-50 text-green-600 rounded-xl mr-5 group-hover:bg-green-600 group-hover:text-white transition-colors duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
                <div>
                    <p class="text-slate-400 text-xs font-bold uppercase tracking-wider group-hover:text-green-600 transition-colors">Company Report</p>
                    <p class="text-xl font-bold text-slate-800 flex items-center gap-2">
                        Lihat Data
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                        </svg>
                    </p>
                </div>
            </a>
        </div>

        <h2 class="text-xl font-bold text-slate-800 mb-6">Menu Akses Cepat</h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <a href="{{ route('admin.projects.create') }}" class="group bg-white p-8 rounded-2xl shadow-sm border border-slate-100 hover:shadow-lg hover:border-blue-200 transition-all duration-300 relative overflow-hidden">
                <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition">
                    <svg class="w-24 h-24 text-blue-600" fill="currentColor" viewBox="0 0 20 20"><path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" /><path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd" /></svg>
                </div>
                <div class="w-14 h-14 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mb-6 group-hover:scale-110 transition-transform shadow-sm">
                    <span class="text-3xl font-bold">+</span>
                </div>
                <h3 class="text-lg font-bold text-slate-800 group-hover:text-blue-600 transition-colors">1. Buat Project Baru</h3>
                <p class="text-slate-500 text-sm mt-2 leading-relaxed">Input pesanan masuk, tentukan jumlah, dan generate tugas otomatis.</p>
            </a>

            <a href="{{ route('admin.assignments.create') }}" class="group bg-white p-8 rounded-2xl shadow-sm border border-slate-100 hover:shadow-lg hover:border-indigo-200 transition-all duration-300 relative overflow-hidden">
                <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition">
                    <svg class="w-24 h-24 text-indigo-600" fill="currentColor" viewBox="0 0 20 20"><path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z" /></svg>
                </div>
                <div class="w-14 h-14 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center mb-6 group-hover:scale-110 transition-transform shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-800 group-hover:text-indigo-600 transition-colors">2. Distribusi Tugas</h3>
                <p class="text-slate-500 text-sm mt-2 leading-relaxed">Berikan kain/potongan ke penjahit dan catat pengeluaran stok project.</p>
            </a>

            <a href="{{ route('admin.assignments.scannerCheckIn') }}" class="group bg-white p-8 rounded-2xl shadow-sm border border-slate-100 hover:shadow-lg hover:border-emerald-200 transition-all duration-300 relative overflow-hidden">
                <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition">
                    <svg class="w-24 h-24 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3 4a1 1 0 011-1h3a1 1 0 011 1v3a1 1 0 01-1 1H4a1 1 0 01-1-1V4zm2 2V5h1v1H5zM3 13a1 1 0 011-1h3a1 1 0 011 1v3a1 1 0 01-1 1H4a1 1 0 01-1-1v-3zm2 2v-1h1v1H5zM13 3a1 1 0 00-1 1v3a1 1 0 001 1h3a1 1 0 001-1V4a1 1 0 00-1-1h-3zm1 2v1h1V5h-1z" clip-rule="evenodd" /><path d="M11 4a1 1 0 10-2 0v1H8V4a1 1 0 00-2 0v2a1 1 0 011.172 2H10a1 1 0 110 2H8v2a1 1 0 01-1 1H4a1 1 0 01-1-1V6a1 1 0 011-1h3v1h1V4a1 1 0 011-1h2z" /></svg>
                </div>
                <div class="w-14 h-14 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mb-6 group-hover:scale-110 transition-transform shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4h-4v-4H8m13-4V7a1 1 0 00-1-1h-4a1 1 0 00-1 1v3a1 1 0 001 1h4a1 1 0 001-1zM7 16H5a1 1 0 01-1-1v-3a1 1 0 011-1h4a1 1 0 011 1v3a1 1 0 01-1 1zm-4-9h4v4H3V7z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-800 group-hover:text-emerald-600 transition-colors">3. Verifikasi & Terima</h3>
                <p class="text-slate-500 text-sm mt-2 leading-relaxed">Masuk mode Scanner. Cek hasil jahit, terima barang, dan update status.</p>
            </a>

        </div>
    </main>
</body>
</html>
