<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Pesanan Multi-Item - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Poppins', sans-serif; }</style>
</head>
<body class="bg-slate-50 min-h-screen p-4 md:p-8">

    <div class="max-w-6xl mx-auto">

        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Input Pesanan Baru</h1>
                <p class="text-slate-500 text-sm">Satu pesanan bisa memiliki banyak item project.</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="bg-white border border-slate-300 text-slate-600 px-4 py-2 rounded-xl text-sm font-bold hover:bg-slate-50 transition">
                &larr; Dashboard
            </a>
        </div>

        <form action="{{ route('admin.projects.store') }}" method="POST">
            @csrf

            <div class="bg-white rounded-3xl shadow-sm border border-slate-200 p-6 mb-6">
                <h2 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
                    <span class="bg-blue-100 text-blue-600 w-8 h-8 rounded-full flex items-center justify-center text-sm">1</span>
                    Informasi Pemesan
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Tanggal Order</label>
                        <input type="date" name="order_date" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-slate-800 focus:outline-none focus:border-blue-500 transition" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nama Instansi / Pelanggan</label>
                        <input type="text" name="customer_name" placeholder="Contoh: Dinas Pemerintahan Surabaya" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-slate-800 focus:outline-none focus:border-blue-500 transition" required>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-3xl shadow-sm border border-slate-200 p-6 mb-20">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                        <span class="bg-indigo-100 text-indigo-600 w-8 h-8 rounded-full flex items-center justify-center text-sm">2</span>
                        Daftar Item Project
                    </h2>
                    <button type="button" onclick="addNewItem()" class="bg-indigo-50 text-indigo-600 px-4 py-2 rounded-lg text-xs font-bold hover:bg-indigo-100 transition flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                        Tambah Item Lain
                    </button>
                </div>

                <div id="items-container" class="space-y-6">
                    <div class="item-card bg-slate-50 rounded-2xl p-6 border border-slate-200 relative group transition-all hover:border-indigo-300">
                        <div class="absolute top-4 right-4 opacity-0 group-hover:opacity-100 transition-opacity">
                            <span class="text-xs text-slate-400 font-bold bg-white px-2 py-1 rounded shadow-sm">Item #1</span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                            <div class="md:col-span-4">
                                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Nama Item / Produk</label>
                                <input type="text" name="items[0][name]" placeholder="Cth: Kemeja Batik" class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2 text-sm focus:border-indigo-500 outline-none" required>
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Qty (Pcs)</label>
                                <input type="text" inputmode="numeric" name="items[0][total_qty]" placeholder="0" class="format-angka input-qty w-full bg-blue-50 border border-blue-200 rounded-lg px-3 py-2 text-sm font-bold text-blue-800 focus:border-blue-500 outline-none" required>
                            </div>

                            <div class="md:col-span-3">
                                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Harga Satuan (Rp)</label>
                                <input type="text" inputmode="numeric" name="items[0][price_per_qty]" placeholder="0" class="format-angka input-price w-full bg-white border border-slate-200 rounded-lg px-3 py-2 text-sm focus:border-indigo-500 outline-none" required>
                            </div>

                            <div class="md:col-span-3">
                                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Total (Rp)</label>
                                <input type="text" name="items[0][total_price]" readonly class="input-total w-full bg-slate-200 border border-transparent rounded-lg px-3 py-2 text-sm font-bold text-slate-600 outline-none cursor-not-allowed">
                            </div>

                            <div class="md:col-span-12 pt-2 border-t border-slate-200/50 mt-2">
                                <div class="flex items-center gap-4">
                                    <label class="text-[10px] font-bold text-orange-400 uppercase whitespace-nowrap">Modal / Budget (Opsional):</label>
                                    <input type="text" inputmode="numeric" name="items[0][budget]" placeholder="Kosongkan jika belum ada" class="format-angka w-full bg-orange-50 border border-orange-100 rounded-lg px-3 py-1.5 text-xs focus:border-orange-300 outline-none">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="fixed bottom-0 left-0 w-full bg-white border-t border-slate-200 p-4 shadow-lg z-40">
                <div class="max-w-6xl mx-auto flex justify-between items-center">
                    <div class="text-sm text-slate-500">
                        Total Item: <span id="total-item-count" class="font-bold text-slate-800">1</span>
                    </div>
                    <button type="submit" class="bg-slate-900 hover:bg-slate-800 text-white font-bold py-3 px-8 rounded-xl shadow-lg hover:shadow-xl transition transform hover:-translate-y-1 flex items-center gap-2">
                        <span>SIMPAN SEMUA PESANAN</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </button>
                </div>
            </div>

        </form>
    </div>

    <script>
        let itemCount = 1;
        const container = document.getElementById('items-container');
        const countDisplay = document.getElementById('total-item-count');

        // Fungsi Format Angka (1000 -> 1.000)
        function formatAngka(angka) {
            if (!angka) return '';
            let str = angka.replace(/[^\d]/g, '');
            return str.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        function unformatAngka(str) {
            if (!str) return 0;
            return parseFloat(str.replace(/\./g, '')) || 0;
        }

        // Logic Hitung per Baris
        function attachEvents(card) {
            const qtyInput = card.querySelector('.input-qty');
            const priceInput = card.querySelector('.input-price');
            const totalInput = card.querySelector('.input-total');
            const formatInputs = card.querySelectorAll('.format-angka');

            // Event Format Angka
            formatInputs.forEach(input => {
                input.addEventListener('input', function() {
                    this.value = formatAngka(this.value);
                });
            });

            // Event Hitung Total
            function calculate() {
                let qty = unformatAngka(qtyInput.value);
                let price = unformatAngka(priceInput.value);
                let total = qty * price;
                totalInput.value = formatAngka(String(total));
            }

            qtyInput.addEventListener('input', calculate);
            priceInput.addEventListener('input', calculate);
        }

        // Initialize item pertama
        attachEvents(document.querySelector('.item-card'));

        // Fungsi Tambah Item Baru
        function addNewItem() {
            const index = itemCount; // index array baru
            itemCount++;
            countDisplay.innerText = itemCount;

            const html = `
                <div class="item-card bg-slate-50 rounded-2xl p-6 border border-slate-200 relative group transition-all hover:border-indigo-300 animate-fade-in">
                    <button type="button" onclick="removeItem(this)" class="absolute -top-3 -right-3 bg-red-100 text-red-500 hover:bg-red-500 hover:text-white rounded-full p-1 shadow-sm transition-colors" title="Hapus Item">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                    </button>

                    <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                        <div class="md:col-span-4">
                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Nama Item / Produk</label>
                            <input type="text" name="items[${index}][name]" placeholder="Cth: Celana Panjang" class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2 text-sm focus:border-indigo-500 outline-none" required>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Qty (Pcs)</label>
                            <input type="text" inputmode="numeric" name="items[${index}][total_qty]" placeholder="0" class="format-angka input-qty w-full bg-blue-50 border border-blue-200 rounded-lg px-3 py-2 text-sm font-bold text-blue-800 focus:border-blue-500 outline-none" required>
                        </div>

                        <div class="md:col-span-3">
                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Harga Satuan (Rp)</label>
                            <input type="text" inputmode="numeric" name="items[${index}][price_per_qty]" placeholder="0" class="format-angka input-price w-full bg-white border border-slate-200 rounded-lg px-3 py-2 text-sm focus:border-indigo-500 outline-none" required>
                        </div>

                        <div class="md:col-span-3">
                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Total (Rp)</label>
                            <input type="text" name="items[${index}][total_price]" readonly class="input-total w-full bg-slate-200 border border-transparent rounded-lg px-3 py-2 text-sm font-bold text-slate-600 outline-none cursor-not-allowed">
                        </div>

                        <div class="md:col-span-12 pt-2 border-t border-slate-200/50 mt-2">
                            <div class="flex items-center gap-4">
                                <label class="text-[10px] font-bold text-orange-400 uppercase whitespace-nowrap">Modal / Budget (Opsional):</label>
                                <input type="text" inputmode="numeric" name="items[${index}][budget]" placeholder="Kosongkan jika belum ada" class="format-angka w-full bg-orange-50 border border-orange-100 rounded-lg px-3 py-1.5 text-xs focus:border-orange-300 outline-none">
                            </div>
                        </div>
                    </div>
                </div>
            `;

            container.insertAdjacentHTML('beforeend', html);

            // Pasang event listener ke elemen baru
            const newCard = container.lastElementChild;
            attachEvents(newCard);
        }

        // Fungsi Hapus Item
        function removeItem(btn) {
            if (itemCount > 1) {
                btn.closest('.item-card').remove();
                itemCount--;
                countDisplay.innerText = itemCount;
            } else {
                alert("Minimal harus ada 1 item!");
            }
        }
    </script>

    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fadeIn 0.3s ease-out forwards;
        }
    </style>
</body>
</html>
