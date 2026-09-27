<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Koperasi SMB</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrious/4.0.2/qrious.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap');
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="bg-[#f0f9ff] min-h-screen">

    <nav class="bg-white/80 backdrop-blur-md sticky top-0 z-50 border-b border-sky-100 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/logo_smb.jpg') }}" alt="Logo SMB" class="w-10 h-10 rounded-lg shadow-md object-cover">
                    <span class="font-bold text-xl text-sky-900 tracking-tight">Koperasi SMB</span>
                </div>

                <div class="flex items-center gap-3">

                    @if(Auth::user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="px-5 py-2 rounded-full bg-sky-600 text-white font-semibold text-sm hover:bg-sky-700 transition-all duration-300 flex items-center gap-2 shadow-lg shadow-sky-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                            </svg>
                            Admin Panel
                        </a>
                    @endif

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="px-5 py-2 rounded-full border border-red-200 text-red-600 font-semibold text-sm hover:bg-red-50 hover:border-red-300 transition-all duration-300 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            Keluar
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

        <div class="text-center mb-12 fade-in">
            <h1 class="text-4xl font-bold text-gray-800 mb-2">
                Halo, <span class="text-transparent bg-clip-text bg-gradient-to-r from-sky-600 to-green-500">{{ Auth::user()->name }}</span>! 👋
            </h1>
            <p class="text-gray-500">Selamat datang kembali di Dashboard Anggota.</p>
        </div>

        <div class="bg-white rounded-[30px] shadow-xl border border-sky-100 overflow-hidden max-w-4xl mx-auto flex flex-col md:flex-row profile-card opacity-0 translate-y-10">
            <div class="md:w-1/3 bg-gradient-to-b from-sky-50 to-white p-10 flex flex-col items-center justify-center border-r border-sky-50 text-center">
                <div class="w-32 h-32 rounded-full bg-gradient-to-br from-sky-200 to-green-200 p-1 mb-4 shadow-inner">
                    <div class="w-full h-full rounded-full bg-white flex items-center justify-center text-4xl font-bold text-sky-600 uppercase">
                        {{ substr(Auth::user()->name, 0, 2) }}
                    </div>
                </div>
                <span class="px-4 py-1 rounded-full bg-sky-100 text-sky-700 text-xs font-bold uppercase tracking-wider mb-2">
                    {{ Auth::user()->role ?? 'Member' }}
                </span>
                <p class="text-xs text-gray-400">Bergabung sejak {{ Auth::user()->created_at->format('d M Y') }}</p>
            </div>

            <div class="md:w-2/3 p-10">
                <h3 class="text-xl font-bold text-gray-800 mb-6 border-b border-gray-100 pb-4">Informasi Akun</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="info-item">
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Nama Lengkap</label>
                        <p class="text-gray-700 font-medium">{{ Auth::user()->name }}</p>
                    </div>
                    <div class="info-item">
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Email</label>
                        <p class="text-gray-700 font-medium">{{ Auth::user()->email }}</p>
                    </div>
                    </div>

                <div class="mt-8 pt-6 border-t border-gray-100 flex gap-4">
                    <a href="{{ route('profile.edit') }}" class="px-6 py-2.5 bg-sky-600 text-white rounded-lg text-sm font-semibold hover:bg-sky-700 transition shadow-lg shadow-sky-200 inline-flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path>
                        </svg>
                        Edit Profil
                    </a>
                </div>
            </div>
        </div>

        <div class="mt-12 fade-in-up opacity-0 translate-y-10">
            <div class="flex justify-between items-end mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">🛠️ Workdesk Saya</h2>
                    <p class="text-sm text-gray-500 mt-1">Daftar jahitan yang sedang Anda kerjakan.</p>
                </div>
                <button onclick="openQrModal()" class="group bg-white border border-sky-200 hover:border-sky-400 text-sky-700 hover:bg-sky-50 px-5 py-2.5 rounded-xl font-semibold shadow-sm transition-all duration-300 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4h-4v-4H8m13-4V7a1 1 0 00-1-1h-4a1 1 0 00-1 1v3a1 1 0 001 1h4a1 1 0 001-1zM7 16H5a1 1 0 01-1-1v-3a1 1 0 011-1h4a1 1 0 011 1v3a1 1 0 01-1 1zm-4-9h4v4H3V7z" />
                    </svg>
                    QR Code Saya
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($assignments as $task)
                    @php
                        // --- PERBAIKAN VARIABEL DI SINI ---
                        // Menggunakan 'qty_assigned' bukan 'total_qty'
                        // Menggunakan 'qty_completed' (sudah benar)
                        $target = $task->qty_assigned;
                        $completed = $task->qty_completed;

                        // Cegah pembagian nol
                        $percentage = ($target > 0) ? ($completed / $target) * 100 : 0;
                        $remaining = $target - $completed;

                        // Ambil Nama Project dari Relasi
                        $projectName = $task->project ? $task->project->name : 'Project Dihapus';
                    @endphp

                    <div class="bg-white rounded-[20px] p-6 shadow-lg border border-sky-50 hover:shadow-xl transition-shadow duration-300 relative overflow-hidden group">
                        <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-gradient-to-br from-sky-100 to-green-100 rounded-full opacity-50 blur-xl group-hover:scale-110 transition-transform"></div>

                        <div class="relative z-10">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h3 class="text-lg font-bold text-gray-800 line-clamp-1" title="{{ $projectName }}">{{ $projectName }}</h3>
                                    <p class="text-xs text-gray-400 mt-1">Diterima: {{ $task->created_at->format('d M, H:i') }}</p>
                                </div>
                                <span class="bg-yellow-50 text-yellow-700 text-[10px] font-bold px-2 py-1 rounded-lg border border-yellow-100 uppercase tracking-wide">
                                    In Progress
                                </span>
                            </div>

                            <p class="text-sm text-gray-500 mb-6 min-h-[40px]">
                                {{ $task->project->description ?? 'Tidak ada catatan khusus.' }}
                            </p>

                            <div class="mb-4">
                                <div class="flex justify-between text-xs font-semibold mb-2">
                                    <span class="text-gray-500">Target: {{ $target }} pcs</span>
                                    <span class="text-sky-600">{{ round($percentage) }}% Selesai</span>
                                </div>
                                <div class="w-full bg-gray-100 rounded-full h-3 overflow-hidden">
                                    <div class="bg-gradient-to-r from-sky-500 to-green-400 h-3 rounded-full transition-all duration-1000 ease-out" style="width: {{ $percentage }}%"></div>
                                </div>
                                <div class="text-right mt-2">
                                    <p class="text-xs text-gray-400">Sisa Tanggungan: <span class="text-red-500 font-bold text-sm">{{ $remaining }} pcs</span></p>
                                </div>
                            </div>

                            <button
                                onclick="openUpdateModal('{{ $task->id }}', '{{ $projectName }}', {{ $remaining }})"
                                class="w-full py-2.5 rounded-xl bg-gray-800 text-white text-sm font-semibold hover:bg-gray-900 transition-colors flex items-center justify-center gap-2 shadow-lg shadow-gray-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Update Progress
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="col-span-1 md:col-span-2 lg:col-span-3">
                        <div class="bg-white rounded-[20px] border border-dashed border-gray-300 p-12 text-center">
                            <div class="w-16 h-16 bg-sky-50 text-sky-400 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-800">Workdesk Kosong</h3>
                            <p class="text-gray-500 text-sm mt-1 max-w-sm mx-auto">
                                Belum ada pekerjaan yang ditugaskan. Silahkan temui Admin dan tunjukkan QR Code Anda.
                            </p>
                            <button onclick="openQrModal()" class="mt-6 px-6 py-2 bg-sky-100 text-sky-700 rounded-full font-semibold text-sm hover:bg-sky-200 transition">
                                Tampilkan QR Code
                            </button>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </main>

    <div id="qrModal" class="fixed inset-0 z-[60] hidden transition-opacity duration-300" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" onclick="closeQrModal()"></div>
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-sm border border-sky-100">
                <div class="bg-gradient-to-br from-sky-500 to-sky-600 px-4 py-6 text-center">
                    <h3 class="text-lg font-bold text-white" id="modal-title">QR Code Identitas</h3>
                    <p class="text-sky-100 text-xs mt-1">Tunjukkan kepada Admin saat mengambil stok.</p>
                </div>
                <div class="px-4 py-8 flex flex-col items-center justify-center bg-white">
                    <canvas id="qr-code-canvas" class="border-4 border-white shadow-lg rounded-lg"></canvas>
                    <div class="mt-6 text-center">
                         <span class="px-3 py-1 bg-sky-50 text-sky-700 rounded-full text-xs font-bold border border-sky-100">
                            {{ Auth::user()->name }}
                        </span>
                        <p class="text-xs text-gray-400 mt-2">ID: #USER-{{ Auth::user()->id }}</p>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                    <button type="button" onclick="closeQrModal()" class="inline-flex w-full justify-center rounded-lg bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <div id="updateModal" class="fixed inset-0 z-[60] hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" onclick="closeUpdateModal()"></div>
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-md">
                <form id="updateForm" action="" method="POST">
                    @csrf
                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                                <h3 class="text-base font-semibold leading-6 text-gray-900">Setor / Update Progress</h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500 mb-4">Masukkan jumlah barang yang <b>BARU SAJA</b> selesai Anda kerjakan.</p>
                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Project</label>
                                            <input type="text" id="modalProjectName" disabled class="w-full px-3 py-2 border border-gray-200 rounded-lg bg-gray-50 text-gray-600 text-sm font-medium">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Jumlah Selesai (Pcs)</label>
                                            <input type="number" name="completed_amount" min="1" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500 transition-all text-lg font-bold text-gray-800 placeholder-gray-300" placeholder="0">
                                            <p class="text-xs text-red-500 mt-2 text-right">Sisa Tanggungan: <span id="modalRemaining" class="font-bold"></span> pcs</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                        <button type="submit" class="inline-flex w-full justify-center rounded-lg bg-sky-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-sky-500 sm:ml-3 sm:w-auto transition-colors">Simpan Update</button>
                        <button type="button" onclick="closeUpdateModal()" class="mt-3 inline-flex w-full justify-center rounded-lg bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto transition-colors">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        gsap.from("nav", { y: -100, duration: 1, ease: "power4.out" });
        gsap.from(".fade-in", { opacity: 0, y: 20, duration: 1, delay: 0.3 });
        gsap.to(".profile-card", { opacity: 1, y: 0, duration: 1, ease: "power3.out", delay: 0.5 });
        gsap.from(".info-item", { opacity: 0, x: 20, stagger: 0.1, delay: 0.8 });
        gsap.to(".fade-in-up", { scrollTrigger: ".fade-in-up", opacity: 1, y: 0, duration: 1, ease: "power3.out", delay: 1 });

        // QR Modal
        function openQrModal() {
            const modal = document.getElementById('qrModal');
            modal.classList.remove('hidden');
            gsap.fromTo(modal.querySelector('.transform'), { scale: 0.9, opacity: 0 }, { scale: 1, opacity: 1, duration: 0.3, ease: "back.out(1.7)" });

            var qr = new QRious({
                element: document.getElementById('qr-code-canvas'),
                value: 'USER-{{ Auth::user()->id }}',
                size: 200,
                level: 'H',
                foreground: '#0284c7'
            });
        }
        function closeQrModal() {
            const modal = document.getElementById('qrModal');
            gsap.to(modal.querySelector('.transform'), { scale: 0.9, opacity: 0, duration: 0.2, onComplete: () => modal.classList.add('hidden') });
        }

        // Update Modal
        function openUpdateModal(id, name, remaining) {
            const modal = document.getElementById('updateModal');
            const form = document.getElementById('updateForm');
            form.action = '/assignment/' + id + '/update';
            document.getElementById('modalProjectName').value = name;
            document.getElementById('modalRemaining').innerText = remaining;
            modal.classList.remove('hidden');
            gsap.fromTo(modal.querySelector('.transform'), { y: 50, opacity: 0 }, { y: 0, opacity: 1, duration: 0.3, ease: "power2.out" });
        }
        function closeUpdateModal() {
            const modal = document.getElementById('updateModal');
            gsap.to(modal.querySelector('.transform'), { y: 50, opacity: 0, duration: 0.2, onComplete: () => modal.classList.add('hidden') });
        }
    </script>
</body>
</html>
