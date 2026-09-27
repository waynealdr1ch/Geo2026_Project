<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Project: {{ $project->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Poppins', sans-serif; }</style>
</head>
<body class="bg-slate-50 min-h-screen p-4 md:p-8">

    <div class="max-w-5xl mx-auto bg-white rounded-3xl shadow-xl border border-slate-100 overflow-hidden">

        <div class="bg-slate-900 px-8 py-6 flex justify-between items-center">
            <div>
                <h1 class="text-xl font-bold text-white">Detail Project</h1>
                <p class="text-slate-400 text-xs mt-1">Edit informasi project dan status pengerjaan.</p>
            </div>
            <a href="{{ route('admin.projects.index') }}" class="text-slate-300 hover:text-white text-sm font-medium transition">&larr; Kembali ke List</a>
        </div>

        <form action="{{ route('admin.projects.update', $project->id) }}" method="POST" class="p-8">
            @csrf
            @method('PUT') <div class="grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-12">

                <div class="space-y-5">
                    <h3 class="text-slate-800 font-bold border-b border-slate-100 pb-2 mb-4">A. Data Umum</h3>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Status Project</label>
                        <select name="status" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-slate-800 focus:outline-none focus:border-blue-500 transition">
                            <option value="not_started" {{ $project->status == 'not_started' ? 'selected' : '' }}>Belum Dimulai</option>
                            <option value="in_progress" {{ $project->status == 'in_progress' ? 'selected' : '' }}>Sedang Dikerjakan</option>
                            <option value="completed" {{ $project->status == 'completed' ? 'selected' : '' }}>Selesai</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Tanggal Order</label>
                        <input type="date" name="order_date" value="{{ $project->order_date }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-slate-800 focus:outline-none focus:border-blue-500 transition" required>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nama Instansi</label>
                        <input type="text" name="customer_name" value="{{ $project->customer_name }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-slate-800 focus:outline-none focus:border-blue-500 transition" required>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nama Item / Produk</label>
                        <input type="text" name="name" value="{{ $project->name }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-slate-800 focus:outline-none focus:border-blue-500 transition" required>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Quantity (Pcs)</label>
                        <input type="text" inputmode="numeric" id="qty" name="total_qty" value="{{ number_format($project->total_qty, 0, ',', '.') }}" class="format-angka w-full bg-blue-50 border border-blue-200 rounded-xl px-4 py-3 text-blue-900 font-bold text-lg focus:outline-none focus:border-blue-500 transition" required>
                    </div>
                </div>

                <div class="space-y-5">
                    <h3 class="text-slate-800 font-bold border-b border-slate-100 pb-2 mb-4">B. Data Keuangan</h3>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Harga Per Pcs (Rp)</label>
                        <div class="relative">
                            <span class="absolute left-4 top-3.5 text-slate-400 font-bold text-sm">Rp</span>
                            <input type="text" inputmode="numeric" id="price" name="price_per_qty" value="{{ number_format($project->price_per_qty, 0, ',', '.') }}" class="format-angka w-full pl-10 bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-slate-800 font-medium focus:outline-none focus:border-emerald-500 transition" required>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Total Nilai Project (Rp)</label>
                        <div class="relative">
                            <span class="absolute left-4 top-3.5 text-slate-500 font-bold text-sm">Rp</span>
                            <input type="text" id="total" name="total_price" value="{{ number_format($project->total_price, 0, ',', '.') }}" readonly class="w-full pl-10 bg-slate-100 border border-slate-200 rounded-xl px-4 py-3 text-slate-500 font-bold cursor-not-allowed" required>
                        </div>
                        <p class="text-[10px] text-emerald-600 mt-1 font-semibold" id="math-info">Total = Quantity x Harga Satuan</p>
                    </div>

                    <div class="pt-4 border-t border-dashed border-slate-200">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Modal / Budget (Opsional)</label>
                        <div class="relative">
                            <span class="absolute left-4 top-3.5 text-slate-400 font-bold text-sm">Rp</span>
                            <input type="text" inputmode="numeric" name="budget" value="{{ $project->budget ? number_format($project->budget, 0, ',', '.') : '' }}" class="format-angka w-full pl-10 bg-orange-50 border border-orange-200 rounded-xl px-4 py-3 text-slate-800 font-medium focus:outline-none focus:border-orange-500 transition">
                        </div>
                    </div>
                </div>
            </div>

            <div class="pt-8 mt-4 border-t border-slate-100 flex justify-end gap-4">
                <a href="{{ route('admin.projects.index') }}" class="px-6 py-4 rounded-2xl font-bold text-slate-500 hover:bg-slate-100 transition">Batal</a>
                <button type="submit" class="w-full md:w-auto px-8 bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 rounded-2xl shadow-lg hover:shadow-xl transition transform hover:-translate-y-1">
                    SIMPAN PERUBAHAN
                </button>
            </div>
        </form>
    </div>

    <script>
        function formatAngka(angka) {
            if (!angka) return '';
            let str = angka.toString().replace(/[^\d]/g, ''); // Pastikan string dulu
            return str.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        function unformatAngka(str) {
            if (!str) return 0;
            return parseFloat(str.replace(/\./g, '')) || 0;
        }

        const inputs = document.querySelectorAll('.format-angka');
        inputs.forEach(input => {
            input.addEventListener('input', function(e) {
                let val = this.value;
                this.value = formatAngka(val);
                hitungTotal();
            });
        });

        const qtyInput = document.getElementById('qty');
        const priceInput = document.getElementById('price');
        const totalInput = document.getElementById('total');

        function hitungTotal() {
            let qty = unformatAngka(qtyInput.value);
            let price = unformatAngka(priceInput.value);
            let total = qty * price;
            totalInput.value = formatAngka(String(total));
        }
    </script>
</body>
</html>
