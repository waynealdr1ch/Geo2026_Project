<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil - Koperasi Sumber Mulia Barokah</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style> body { font-family: 'Poppins', sans-serif; } </style>
</head>
<body class="bg-[#f0f9ff] min-h-screen">

    <nav class="bg-white shadow-sm p-4">
        <div class="container mx-auto flex justify-between items-center">
             <div class="flex items-center gap-2">
                <img src="{{ asset('images/logo_smb.jpg') }}" alt="Logo SMB" class="w-10 h-10 rounded-lg shadow-md object-cover">
                <span class="font-bold text-xl text-sky-900">Koperasi SMB</span>
            </div>
             <a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-sky-600 font-semibold flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
                Kembali ke Dashboard
            </a>
        </div>
    </nav>

    <div class="container mx-auto p-8 flex justify-center">
        <div class="bg-white w-full max-w-3xl rounded-[30px] shadow-xl p-10 relative overflow-hidden">
             <h2 class="text-3xl font-bold text-sky-900 mb-8 text-center">Edit Profil Anggota</h2>

             <form action="{{ route('profile.update') }}" method="POST" class="space-y-6">
                @csrf
                @method('PATCH') <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Nama Lengkap</label>
                        <input type="text" value="{{ $user->name }}" readonly class="w-full border-b-2 border-gray-200 py-3 text-gray-500 bg-gray-100 cursor-not-allowed focus:outline-none">
                        <p class="text-xs text-gray-400 mt-1">*Tidak dapat diubah</p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Username</label>
                        <input type="text" value="{{ $user->username }}" readonly class="w-full border-b-2 border-gray-200 py-3 text-gray-500 bg-gray-100 cursor-not-allowed focus:outline-none">
                         <p class="text-xs text-gray-400 mt-1">*Tidak dapat diubah</p>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Email</label>
                        <input type="text" value="{{ $user->email }}" readonly class="w-full border-b-2 border-gray-200 py-3 text-gray-500 bg-gray-100 cursor-not-allowed focus:outline-none">
                         <p class="text-xs text-gray-400 mt-1">*Hubungi pengurus jika ingin mengubah email</p>
                    </div>

                    <div class="md:col-span-2">
                        <label for="phone_number" class="block text-xs font-bold text-sky-600 uppercase mb-2">Nomor Telepon / WhatsApp (Dapat Diubah)</label>
                        <input type="text" id="phone_number" name="phone_number" value="{{ old('phone_number', $user->phone_number) }}" class="w-full border-b-2 border-sky-200 py-3 text-gray-700 focus:outline-none focus:border-sky-500 bg-sky-50/50 transition-colors placeholder-gray-300" required>
                        @error('phone_number')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="address" class="block text-xs font-bold text-sky-600 uppercase mb-2">Alamat Lengkap (Dapat Diubah)</label>
                        <textarea id="address" name="address" rows="3" class="w-full border-b-2 border-sky-200 py-3 text-gray-700 focus:outline-none focus:border-sky-500 bg-sky-50/50 transition-colors placeholder-gray-300 resize-none" required>{{ old('address', $user->address) }}</textarea>
                        <p class="text-xs text-gray-400 mt-1">Contoh: Jl. Mawar No. 10, Kec. Tambaksari, Surabaya</p>
                        @error('address')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-10 flex justify-end gap-4">
                    <a href="{{ route('dashboard') }}" class="px-6 py-3 rounded-xl font-bold text-gray-500 hover:bg-gray-100 transition-all">
                        Batal
                    </a>
                    <button type="submit" class="bg-gradient-to-r from-sky-600 to-green-500 text-white px-8 py-3 rounded-xl font-bold shadow-lg hover:shadow-sky-500/30 transform hover:-translate-y-1 transition-all duration-300">
                        Simpan Perubahan
                    </button>
                </div>
             </form>

             <div class="absolute -top-20 -right-20 w-60 h-60 bg-sky-100 rounded-full mix-blend-multiply filter blur-3xl opacity-50 pointer-events-none"></div>
             <div class="absolute -bottom-20 -left-20 w-60 h-60 bg-green-100 rounded-full mix-blend-multiply filter blur-3xl opacity-50 pointer-events-none"></div>
        </div>
    </div>

</body>
</html>
