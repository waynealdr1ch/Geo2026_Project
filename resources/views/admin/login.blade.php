<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Access - Koperasi Sumber Mulia Barokah</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap');
        body { font-family: 'Poppins', sans-serif; }

        /* Custom Scrollbar */
        .custom-scroll::-webkit-scrollbar { width: 4px; }
        .custom-scroll::-webkit-scrollbar-track { background: transparent; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        .custom-scroll::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        /* Preloader CSS */
        #preloader {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: #f1f5f9; z-index: 9999;
            display: flex; justify-content: center; align-items: center;
        }
        .loader-svg path {
            fill: transparent; stroke-width: 3; stroke: #334155; /* Slate-700 */
            stroke-dasharray: 200; stroke-dashoffset: 200;
            animation: dash 2s linear infinite, fillDark 2s ease-in-out forwards;
        }
        @keyframes dash { to { stroke-dashoffset: 0; } }
    </style>
</head>
<body class="bg-slate-100 min-h-screen w-full flex items-center justify-center p-4 overflow-hidden">

    <div id="preloader">
        <svg class="loader-svg w-24 h-24" viewBox="0 0 100 100">
            <path d="M50 5 L90 25 L90 75 L50 95 L10 75 L10 25 Z" />
        </svg>
    </div>

    <div class="main-card bg-white w-full max-w-5xl md:h-[85vh] md:max-h-[650px] h-auto rounded-[30px] shadow-2xl overflow-hidden flex flex-col md:flex-row relative opacity-0 transform translate-y-10">

        <div class="w-full md:w-5/12 h-full flex flex-col relative z-10 bg-white">
            <div class="flex-1 overflow-y-auto custom-scroll p-8 md:p-10 flex flex-col justify-center">

                <div class="mb-8 form-element">
                    <div class="flex items-center gap-2 mb-2">
                        <img src="{{ asset('images/logo_smb.jpg') }}" alt="Logo SMB" class="w-10 h-10 rounded-lg shadow-md object-cover grayscale">
                        <span class="font-bold text-lg text-slate-800">Administrator</span>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900">Akses Pengurus.</h3>
                    <p class="text-gray-500 text-xs mt-1 leading-relaxed">
                        Login terbatas untuk manajemen koperasi.
                    </p>

                    @if ($errors->any())
                        <div class="mt-4 p-3 bg-red-50 border-l-4 border-red-500 text-red-700 text-xs rounded shadow-sm">
                            <p class="font-bold">Akses Ditolak:</p>
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                     @if (session('error'))
                        <div class="mt-3 p-3 bg-red-50 border-l-4 border-red-500 text-red-700 text-xs rounded shadow-sm">
                            {{ session('error') }}
                        </div>
                    @endif
                </div>

                <form action="{{ route('admin.login.post') }}" method="POST" class="space-y-5 form-element">
                    @csrf
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1 tracking-wider">Username / Email</label>
                        <div class="relative">
                            <input type="text" name="username" value="{{ old('username') }}" class="w-full border-b border-gray-300 py-2 text-sm text-gray-800 focus:outline-none focus:border-slate-800 transition-colors bg-transparent placeholder-gray-400" placeholder="admin@koperasi.id" required>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1 tracking-wider">Kata Sandi</label>
                        <div class="relative">
                            <input type="password" name="password" class="w-full border-b border-gray-300 py-2 text-sm text-gray-800 focus:outline-none focus:border-slate-800 transition-colors bg-transparent placeholder-gray-400" placeholder="••••••••" required>
                        </div>
                    </div>

                    <div class="flex justify-between items-center text-[11px] text-gray-500 py-1">
                        <label class="flex items-center gap-2 cursor-pointer select-none">
                            <input type="checkbox" name="remember" class="accent-slate-800 w-3.5 h-3.5"> Ingat Saya
                        </label>
                    </div>

                    <button type="submit" class="w-full bg-slate-800 text-white py-3 rounded-xl font-bold text-sm shadow-md hover:shadow-lg hover:shadow-slate-500/30 transform hover:-translate-y-0.5 transition-all duration-300 hover:bg-slate-900">
                        MASUK DASHBOARD
                    </button>
                </form>

                <div class="mt-8 text-center text-[10px] text-gray-400 form-element">
                    <a href="{{ route('login') }}" class="text-slate-600 font-bold hover:underline">← Kembali ke Login Anggota</a>
                </div>
            </div>
        </div>

        <div class="hidden md:block w-7/12 relative bg-slate-900 overflow-hidden h-full">
            <div class="absolute inset-0 bg-gradient-to-br from-slate-900 via-gray-800 to-slate-900 opacity-95 z-10"></div>
            <div class="absolute -top-20 -right-20 w-[500px] h-[500px] bg-indigo-900 rounded-full mix-blend-overlay filter blur-3xl opacity-40 animate-blob"></div>
            <div class="absolute top-1/2 -right-20 w-[400px] h-[400px] bg-slate-700 rounded-full mix-blend-overlay filter blur-3xl opacity-40 animate-blob animation-delay-2000"></div>

            <div class="absolute inset-0 z-20 flex flex-col justify-center px-12 text-white text-right items-end content-right">
                <h1 class="text-3xl lg:text-4xl font-bold mb-3 leading-tight">Panel<br>Administrasi</h1>
                <p class="text-slate-300 text-sm lg:text-base max-w-xs">Kelola data koperasi, stok, dan penugasan dengan efisien dan aman.</p>

                <div class="mt-6 py-1 px-3 border border-slate-600 rounded text-[10px] text-slate-400 uppercase tracking-widest">
                    Authorized Personnel Only
                </div>
            </div>
        </div>
    </div>

    <script>
        window.addEventListener('load', () => {
            const tl = gsap.timeline();
            tl.to("#preloader", {
                duration: 0.8,
                y: "-100%",
                ease: "power4.inOut",
                delay: 0.3,
                onComplete: () => {
                    document.getElementById("preloader").style.display = "none";
                }
            })
              .to(".main-card", { duration: 1, opacity: 1, y: 0, ease: "power3.out", borderRadius: "30px" }, "-=0.3")
              .from(".form-element", { duration: 0.8, y: 20, opacity: 0, stagger: 0.1, ease: "back.out(1.7)" }, "-=0.5")
              .from(".content-right", { duration: 1, x: 40, opacity: 0, ease: "power2.out" }, "-=1");
        });
    </script>
</body>
</html>
