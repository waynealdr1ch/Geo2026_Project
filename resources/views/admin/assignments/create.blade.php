<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beri Tugas Baru - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <style>
        body { font-family: 'Poppins', sans-serif; }
        /* Style untuk area kamera */
        #reader {
            width: 100%;
            border-radius: 12px;
            overflow: hidden;
            transform: scaleX(-1) !important;
        }
        #reader video {
            object-fit: cover;
            border-radius: 12px;
            transform: scaleX(1) !important;
        }
    </style>
</head>
<body class="bg-slate-50 min-h-screen p-4 md:p-8">

    <div class="max-w-4xl mx-auto bg-white rounded-3xl shadow-xl border border-slate-100 overflow-hidden">

        <div class="bg-slate-900 px-8 py-6 flex justify-between items-center">
            <div>
                <h1 class="text-xl font-bold text-white">Distribusi Tugas (Barang Keluar)</h1>
                <p class="text-slate-400 text-xs mt-1">Assign potongan kain ke penjahit.</p>
            </div>
            <a href="{{ route('admin.assignments.index') }}" class="text-slate-300 hover:text-white text-sm font-medium transition">&larr; Lihat List Tugas</a>
        </div>

        @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 m-8 mb-0 rounded shadow-sm">
                <p class="font-bold">Gagal!</p>
                <p>{{ session('error') }}</p>
            </div>
        @endif

        <form action="{{ route('admin.assignments.store') }}" method="POST" class="p-8">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                <div class="space-y-6">
                    <h3 class="text-slate-800 font-bold border-b border-slate-100 pb-2">1. Pilih Sumber Project</h3>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nama Project</label>
                        <select name="project_id" id="projectSelect" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-slate-800 focus:outline-none focus:border-blue-500 transition" required>
                            <option value="" data-stok="0">-- Pilih Project --</option>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}" data-stok="{{ $project->remaining_qty }}">
                                    {{ $project->name }} (Sisa: {{ $project->remaining_qty }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="bg-blue-50 p-4 rounded-xl border border-blue-100 hidden" id="stokInfoBox">
                        <p class="text-xs text-blue-600 font-bold uppercase">Ketersediaan Stok</p>
                        <p class="text-2xl font-bold text-blue-900 mt-1"><span id="stokDisplay">0</span> <span class="text-sm font-normal">Pcs</span></p>
                        <p class="text-[10px] text-blue-400 mt-1">Maksimal penugasan tidak boleh melebihi angka ini.</p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Jumlah Diserahkan (Pcs)</label>
                        <input type="number" name="qty_assigned" id="qtyInput" min="1" placeholder="0" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-slate-800 font-bold text-lg focus:outline-none focus:border-blue-500 transition" required>
                    </div>
                </div>

                <div class="space-y-6">
                    <h3 class="text-slate-800 font-bold border-b border-slate-100 pb-2">2. Target Penjahit</h3>

                    <div id="cameraArea" class="hidden mb-4">
                        <div id="reader" class="border-2 border-slate-300 rounded-lg"></div>
                        <button type="button" id="stopScanBtn" class="mt-2 text-xs text-red-600 hover:text-red-800 font-bold underline w-full text-center">
                            Tutup Kamera
                        </button>
                    </div>

                    <button type="button" id="startScanBtn" class="group w-full bg-indigo-50 hover:bg-indigo-100 border border-indigo-200 text-indigo-700 font-bold py-3 px-4 rounded-xl flex items-center justify-center gap-2 transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4h-4v-4H8m13-4V7a1 1 0 00-1-1h-4a1 1 0 00-1 1v3a1 1 0 001 1h4a1 1 0 001-1zM7 16H5a1 1 0 01-1-1v-3a1 1 0 011-1h4a1 1 0 011 1v3a1 1 0 01-1 1zm-4-9h4v4H3V7z" />
                        </svg>
                        <span>Scan QR Code ID</span>
                    </button>

                    <div class="flex items-center gap-2 text-[10px] text-slate-400 font-bold uppercase tracking-widest my-2">
                        <span class="h-px bg-slate-200 flex-1"></span>
                        ATAU
                        <span class="h-px bg-slate-200 flex-1"></span>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Pilih Nama Manual</label>
                        <select name="user_id" id="userSelect" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-slate-800 focus:outline-none focus:border-indigo-500 transition" required>
                            <option value="">-- Cari Nama Penjahit --</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">ID: {{ $user->id }} - {{ $user->name }}</option>
                            @endforeach
                        </select>
                        <p class="text-[10px] text-slate-400 mt-1">Hasil scan QR akan otomatis memilih nama di atas.</p>

                        <div id="scanSuccessMsg" class="hidden mt-2 p-2 bg-green-50 border border-green-200 text-green-700 text-xs rounded-lg flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <span>QR Terdeteksi! Penjahit dipilih.</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pt-8 mt-4 border-t border-slate-100">
                <button type="submit" class="w-full bg-slate-900 hover:bg-slate-800 text-white font-bold py-4 rounded-2xl shadow-lg hover:shadow-xl transition transform hover:-translate-y-1 flex justify-center items-center gap-2">
                    <span>BERIKAN TUGAS SEKARANG</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </button>
            </div>
        </form>
    </div>

    <script>
        // --- 1. Logic Stok Project ---
        const projectSelect = document.getElementById('projectSelect');
        const stokInfoBox = document.getElementById('stokInfoBox');
        const stokDisplay = document.getElementById('stokDisplay');
        const qtyInput = document.getElementById('qtyInput');

        projectSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const sisaStok = selectedOption.getAttribute('data-stok');

            if(sisaStok && sisaStok > 0) {
                stokInfoBox.classList.remove('hidden');
                stokDisplay.innerText = sisaStok;
                qtyInput.max = sisaStok;
            } else {
                stokInfoBox.classList.add('hidden');
            }
        });

        // --- 2. Logic QR Scanner (Real Camera) ---
        const html5QrcodeScanner = new Html5Qrcode("reader");
        const startScanBtn = document.getElementById('startScanBtn');
        const stopScanBtn = document.getElementById('stopScanBtn');
        const cameraArea = document.getElementById('cameraArea');
        const userSelect = document.getElementById('userSelect');
        const scanSuccessMsg = document.getElementById('scanSuccessMsg');

        // Fungsi saat QR Code terbaca
        function onScanSuccess(decodedText, decodedResult) {
            console.log(`Code matched = ${decodedText}`, decodedResult);

            // --- BAGIAN INI YANG DIPERBAIKI ---
            // Format QR: "USER-5". Kita hapus "USER-" biar sisa "5"
            let cleanId = decodedText.replace("USER-", "");

            // Loop dropdown untuk mencari ID yang cocok
            let found = false;
            for (let i = 0; i < userSelect.options.length; i++) {
                // Bandingkan value dropdown (angka) dengan cleanId (angka hasil scan)
                if (userSelect.options[i].value == cleanId) {
                    userSelect.selectedIndex = i;
                    found = true;
                    break;
                }
            }

            if (found) {
                // Hentikan kamera jika ketemu
                stopScanner();

                // Tampilkan pesan sukses visual
                scanSuccessMsg.classList.remove('hidden');
                scanSuccessMsg.innerHTML = `<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> <span>User ID: ${cleanId} Ditemukan!</span>`;

                userSelect.classList.add('ring-2', 'ring-green-500', 'bg-green-50');

                // Hilangkan highlight setelah 3 detik
                setTimeout(() => {
                    userSelect.classList.remove('ring-2', 'ring-green-500', 'bg-green-50');
                    scanSuccessMsg.classList.add('hidden');
                }, 3000);
            } else {
                // Jika QR terbaca tapi ID tidak ada di database dropdown
                alert("QR Code terbaca: " + decodedText + " (" + cleanId + "), tapi User ID tersebut tidak ditemukan di list penjahit.");
            }
        }

        function onScanFailure(error) {
            // Biarkan kosong
        }

        // Tombol Start Scan
        startScanBtn.addEventListener('click', () => {
            cameraArea.classList.remove('hidden');
            startScanBtn.classList.add('hidden');

            html5QrcodeScanner.start(
                { facingMode: "environment" },
                {
                    fps: 10,
                    qrbox: { width: 250, height: 250 }
                },
                onScanSuccess,
                onScanFailure
            ).catch(err => {
                console.error("Gagal memulai kamera", err);
                alert("Gagal akses kamera. Pastikan izin kamera diberikan.");
                cameraArea.classList.add('hidden');
                startScanBtn.classList.remove('hidden');
            });
        });

        // Tombol Stop Scan
        function stopScanner() {
            html5QrcodeScanner.stop().then(() => {
                cameraArea.classList.add('hidden');
                startScanBtn.classList.remove('hidden');
            }).catch(err => {
                console.log("Stop failed ", err);
            });
        }

        stopScanBtn.addEventListener('click', stopScanner);

    </script>
</body>
</html>
