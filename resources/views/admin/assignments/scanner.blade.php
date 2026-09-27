<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Scanner Verifikasi - Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <style>
        body { font-family: 'Poppins', sans-serif; }

        /* Style Area Kamera */
        #reader { width: 100%; border-radius: 12px; overflow: hidden; background: #000; transform: scaleX(-1) !important; }

        /* FIX MIRROR: Paksa video tidak terbalik */
        #reader video {
            object-fit: cover;
            border-radius: 12px;
            transform: scaleX(1) !important; /* Paksa normal */
        }

        /* Animasi Loading */
        .loader {
            border: 3px solid #f3f3f3;
            border-top: 3px solid #3b82f6;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            animation: spin 1s linear infinite;
        }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
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
                    <a href="{{ route('admin.dashboard') }}" class="text-slate-300 hover:text-white text-sm font-medium transition flex items-center gap-1">
                        &larr; Dashboard
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        <div class="mb-8 text-center">
            <h1 class="text-3xl font-bold text-slate-800">Scanner Verifikasi</h1>
            <p class="text-slate-500 mt-1">Scan ID Penjahit untuk menerima dan memverifikasi barang.</p>
        </div>

        <div class="bg-white rounded-3xl shadow-xl border border-slate-100 overflow-hidden relative">

            <div class="h-2 bg-blue-600 w-full"></div>

            <div class="p-8">

                <div id="scan-area" class="transition-all duration-300">

                    <div id="cameraContainer" class="hidden mb-6">
                        <div id="reader" class="border-2 border-slate-300 rounded-xl shadow-inner"></div>
                        <button type="button" onclick="stopCamera()" class="mt-3 text-sm text-red-600 hover:text-red-800 font-bold underline w-full text-center">
                            Tutup Kamera
                        </button>
                    </div>

                    <button id="startScanBtn" onclick="startCamera()" class="w-full bg-blue-50 hover:bg-blue-100 border border-blue-200 text-blue-700 font-bold py-4 px-4 rounded-xl flex items-center justify-center gap-2 transition-all mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span>Buka Kamera Scan QR</span>
                    </button>

                    <div class="flex items-center gap-2 text-[10px] text-slate-400 font-bold uppercase tracking-widest mb-6">
                        <span class="h-px bg-slate-200 flex-1"></span> ATAU PILIH NAMA <span class="h-px bg-slate-200 flex-1"></span>
                    </div>

                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>

                        <select id="scannerInput" onchange="triggerManualCheck()" class="block w-full pl-12 pr-4 py-4 bg-slate-50 border border-slate-200 rounded-xl text-lg font-bold text-slate-800 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition appearance-none cursor-pointer">
                            <option value="" disabled selected>-- Pilih Nama Penjahit --</option>
                            @if(isset($users) && count($users) > 0)
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} (ID: {{ $user->id }})</option>
                                @endforeach
                            @else
                                <option value="" disabled>Tidak ada data penjahit</option>
                            @endif
                        </select>

                        <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>

                    <div id="errorMessage" class="hidden mt-4 p-4 bg-red-50 text-red-600 rounded-xl border border-red-100 flex items-start gap-3 animate-pulse">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div>
                            <span class="font-bold block">Gagal!</span>
                            <span id="errorText" class="text-sm">User tidak ditemukan.</span>
                        </div>
                    </div>

                     <div id="loadingIndicator" class="hidden mt-4 text-center">
                        <div class="inline-flex items-center gap-2 text-slate-500 bg-slate-100 px-4 py-2 rounded-full text-sm">
                            <div class="loader"></div> Memproses data...
                        </div>
                    </div>
                </div>

                <div id="task-detail-area" class="hidden animate-fade-in-up">

                    <div class="flex justify-between items-start mb-6 border-b border-slate-100 pb-6">
                        <div>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">PROJECT</p>
                            <h2 id="detail-project-name" class="text-2xl font-bold text-slate-800 leading-tight">-</h2>
                        </div>
                        <div class="text-right">
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">PENJAHIT</p>
                            <h3 id="detail-user-name" class="text-xl font-bold text-blue-600">-</h3>
                        </div>
                    </div>

                    <form id="verificationForm" onsubmit="submitVerification(event)">
                        <div class="bg-blue-50 rounded-xl p-5 mb-6 flex justify-between items-center border border-blue-100">
                            <div>
                                <span class="text-blue-600 text-sm font-bold block mb-1">TOTAL DITUGASKAN</span>
                                <span id="display-target" class="text-3xl font-bold text-slate-800">0</span> <span class="text-slate-500">Pcs</span>
                            </div>
                            <div class="text-right">
                                <span class="bg-blue-200 text-blue-800 text-xs font-bold px-3 py-1 rounded-full">CHECKING</span>
                            </div>
                        </div>

                        <input type="hidden" name="qty_accepted" id="val-qty">
                        <input type="hidden" name="assignment_id" id="val-assignment-id">

                        <div class="text-center mb-8">
                            <p class="text-slate-500 text-sm">
                                Pastikan fisik barang sesuai dengan total yang ditugaskan.
                                <br>Klik tombol di bawah untuk menyetujui.
                            </p>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <button type="button" onclick="resetScanner()"
                                class="py-3 px-4 bg-slate-100 text-slate-600 font-bold rounded-xl hover:bg-slate-200 transition">
                                Batal
                            </button>
                            <button type="submit"
                                class="py-3 px-4 bg-green-600 text-white font-bold rounded-xl hover:bg-green-700 shadow-lg shadow-green-200 transition flex justify-center items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Verifikasi Selesai
                            </button>
                        </div>
                    </form>
                </div>

                <div id="successMessage" class="hidden text-center py-8">
                    <div class="w-20 h-20 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-4 animate-bounce">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-slate-800 mb-2">Berhasil!</h3>
                    <p id="successText" class="text-slate-500 mb-6">Data telah disimpan.</p>
                    <button onclick="resetScanner()" class="bg-blue-600 text-white px-8 py-2 rounded-full font-bold hover:bg-blue-700 transition">
                        Scan Berikutnya
                    </button>
                </div>

            </div>
        </div>

        <p class="text-center text-slate-400 text-sm mt-8">© 2026 Koperasi SMB Production System</p>
    </main>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        let html5QrcodeScanner = null;

        // --- 1. SETUP KAMERA ---
        function startCamera() {
            const cameraContainer = document.getElementById('cameraContainer');
            const startBtn = document.getElementById('startScanBtn');

            cameraContainer.classList.remove('hidden');
            startBtn.classList.add('hidden');

            html5QrcodeScanner = new Html5Qrcode("reader");

            html5QrcodeScanner.start(
                { facingMode: "environment" },
                { fps: 10, qrbox: { width: 250, height: 250 } },
                onScanSuccess,
                onScanFailure
            ).catch(err => {
                console.error("Error start camera", err);
                alert("Gagal membuka kamera. Pastikan izin kamera diberikan.");
                stopCamera();
            });
        }

        function stopCamera() {
            if(html5QrcodeScanner) {
                html5QrcodeScanner.stop().then(() => {
                    html5QrcodeScanner.clear();
                    document.getElementById('cameraContainer').classList.add('hidden');
                    document.getElementById('startScanBtn').classList.remove('hidden');
                }).catch(err => console.log("Stop failed", err));
            } else {
                document.getElementById('cameraContainer').classList.add('hidden');
                document.getElementById('startScanBtn').classList.remove('hidden');
            }
        }

        function onScanSuccess(decodedText, decodedResult) {
            console.log(`Scan result: ${decodedText}`);
            let cleanId = decodedText.replace("USER-", "");

            // Set value ke dropdown agar sinkron
            const dropdown = document.getElementById('scannerInput');
            dropdown.value = cleanId;

            stopCamera();
            checkTask(cleanId);
        }

        function onScanFailure(error) {
            // Biarkan kosong
        }

        // --- 2. LOGIC CEK TUGAS KE SERVER ---

        function triggerManualCheck() {
            const val = document.getElementById('scannerInput').value;
            checkTask(val);
        }

        function checkTask(userId) {
            const loading = document.getElementById('loadingIndicator');
            const errorArea = document.getElementById('errorMessage');

            if(!userId) return;

            // Reset UI State
            errorArea.classList.add('hidden');
            loading.classList.remove('hidden');

            fetch(`/admin/assignments/check-active-task/${userId}`)
                .then(res => res.json())
                .then(data => {
                    loading.classList.add('hidden');

                    if(data.status === 'found') {
                        showTaskDetail(data.data);
                    } else {
                        document.getElementById('errorText').innerText = data.message;
                        errorArea.classList.remove('hidden');
                    }
                })
                .catch(err => {
                    loading.classList.add('hidden');
                    alert('Terjadi kesalahan sistem / koneksi.');
                    console.error(err);
                });
        }

        function showTaskDetail(task) {
            document.getElementById('scan-area').classList.add('hidden');
            document.getElementById('task-detail-area').classList.remove('hidden');

            document.getElementById('detail-project-name').innerText = task.project.name;
            document.getElementById('detail-user-name').innerText = task.user.name;
            document.getElementById('display-target').innerText = task.qty_assigned;

            document.getElementById('val-assignment-id').value = task.id;

            // [PERUBAHAN] Input Hidden diisi otomatis dengan jumlah Full Assigned
            document.getElementById('val-qty').value = task.qty_assigned;
        }

        // --- 3. SUBMIT VERIFIKASI ---
        function submitVerification(e) {
            e.preventDefault();

            const form = document.getElementById('verificationForm');
            const formData = new FormData(form);

            fetch("{{ route('admin.assignments.verify') }}", {
                method: 'POST',
                body: formData,
                headers: { 'X-CSRF-TOKEN': csrfToken }
            })
            .then(res => res.json())
            .then(data => {
                if(data.status === 'success') {
                    showSuccess(data.message);
                } else {
                    alert('Gagal: ' + data.message);
                }
            })
            .catch(err => {
                alert('Error saat menyimpan data.');
                console.error(err);
            });
        }

        function showSuccess(msg) {
            document.getElementById('task-detail-area').classList.add('hidden');
            document.getElementById('successMessage').classList.remove('hidden');
            document.getElementById('successText').innerText = msg;
        }

        function resetScanner() {
            document.getElementById('successMessage').classList.add('hidden');
            document.getElementById('task-detail-area').classList.add('hidden');
            document.getElementById('scan-area').classList.remove('hidden');
            document.getElementById('errorMessage').classList.add('hidden');

            // Reset Dropdown
            document.getElementById('scannerInput').value = "";
        }
    </script>
</body>
</html>
