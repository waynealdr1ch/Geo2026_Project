<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - Koperasi Sumber Mulia Barokah</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap');
        body { font-family: 'Poppins', sans-serif; }

        .custom-scroll::-webkit-scrollbar { width: 4px; }
        .custom-scroll::-webkit-scrollbar-track { background: transparent; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 4px; }
        .custom-scroll::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }

        #preloader {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: #f0f9ff; z-index: 9999;
            display: flex; justify-content: center; align-items: center;
        }
        .loader-svg path {
            fill: transparent; stroke-width: 3; stroke: #0ea5e9;
            stroke-dasharray: 200; stroke-dashoffset: 200;
            animation: dash 2s linear infinite, fillBlue 2s ease-in-out forwards;
        }
        @keyframes dash { to { stroke-dashoffset: 0; } }
    </style>
</head>
<body class="bg-[#f0f9ff] min-h-screen w-full flex items-center justify-center p-4 overflow-hidden">

    <div id="preloader">
        <svg class="loader-svg w-24 h-24" viewBox="0 0 100 100">
            <path d="M50 5 L90 25 L90 75 L50 95 L10 75 L10 25 Z" />
        </svg>
    </div>

    <div class="main-card bg-white w-full max-w-5xl md:h-[85vh] md:max-h-[650px] h-auto rounded-[30px] shadow-2xl overflow-hidden flex flex-col md:flex-row relative opacity-0 transform translate-y-10">

        <div class="w-full md:w-5/12 h-full flex flex-col relative z-10 bg-white">

            <div class="flex-1 overflow-y-auto custom-scroll p-8 md:p-10 flex flex-col justify-center">

                <div class="mb-5 form-element">
                    <div class="flex items-center gap-2 mb-1">
                        <img src="{{ asset('images/logo_smb.jpg') }}" alt="Logo SMB" class="w-10 h-10 rounded-lg shadow-md object-cover">
                        <span class="font-bold text-lg text-sky-900">Koperasi SMB</span>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800">Sugeng Rawuh.</h3>
                    <p class="text-gray-500 text-xs mt-1 leading-relaxed">
                        Silakan masukkan akun Anda untuk masuk.
                    </p>

                    @if ($errors->any())
                        <div class="mt-3 p-3 bg-red-50 border-l-4 border-red-500 text-red-700 text-xs rounded shadow-sm">
                            <p class="font-bold">Gagal Masuk:</p>
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

                <form action="{{ route('login.post') }}" method="POST" class="space-y-3 form-element">
                    @csrf
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1 tracking-wider">Email / Username</label>
                        <div class="relative">
                            <input type="text" name="username" value="{{ old('email') }}" class="w-full border-b border-gray-200 py-2 text-sm text-gray-700 focus:outline-none focus:border-sky-500 transition-colors bg-transparent placeholder-gray-300" placeholder="Contoh: user@email.com" required>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1 tracking-wider">Kata Sandi</label>
                        <div class="relative">
                            <input type="password" name="password" class="w-full border-b border-gray-200 py-2 text-sm text-gray-700 focus:outline-none focus:border-sky-500 transition-colors bg-transparent placeholder-gray-300" placeholder="••••••••" required>
                        </div>
                    </div>

                    <div class="flex justify-between items-center text-[11px] text-gray-500 py-1">
                        <label class="flex items-center gap-2 cursor-pointer select-none">
                            <input type="checkbox" name="remember" class="accent-sky-600 w-3.5 h-3.5"> Ingat Saya
                        </label>
                        <a href="#" class="hover:text-sky-600">Lupa Sandi?</a>
                    </div>

                    <button type="submit" class="w-full bg-gradient-to-r from-sky-600 to-green-500 text-white py-3 rounded-xl font-bold text-sm shadow-md hover:shadow-lg hover:shadow-sky-500/30 transform hover:-translate-y-0.5 transition-all duration-300">
                        MASUK APLIKASI
                    </button>
                </form>

                <div class="form-element mt-4">
                    <div class="relative mb-4">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-100"></div>
                        </div>
                        <div class="relative flex justify-center text-[10px]">
                            <span class="px-2 bg-white text-gray-400">Atau masuk dengan</span>
                        </div>
                    </div>

                    <a href="{{ route('auth.google') }}" class="relative z-50 w-full flex items-center justify-center gap-2 bg-white border border-gray-200 text-gray-600 py-2.5 rounded-xl text-sm font-semibold hover:bg-gray-50 hover:border-gray-300 transition-all duration-300 group">
                        <svg class="w-4 h-4" viewBox="0 0 24 24">
                            <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                            <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                            <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                            <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                        </svg>
                        Google
                    </a>

                    <div class="mt-4 text-center text-[10px] text-gray-400">
                        Belum terdaftar? <a href="#" class="text-sky-600 font-bold hover:underline">Hubungi Pengurus</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="hidden md:block w-7/12 relative bg-sky-900 overflow-hidden h-full">
            <div class="absolute inset-0 bg-gradient-to-br from-sky-900 via-teal-700 to-green-500 opacity-90 z-10"></div>
            <div class="absolute -top-20 -right-20 w-[500px] h-[500px] bg-sky-500 rounded-full mix-blend-overlay filter blur-3xl opacity-50 animate-blob"></div>
            <div class="absolute top-1/2 -right-20 w-[400px] h-[400px] bg-green-400 rounded-full mix-blend-overlay filter blur-3xl opacity-50 animate-blob animation-delay-2000"></div>

            <div class="absolute inset-0 z-20 flex flex-col justify-center px-12 text-white text-right items-end content-right">
                <h1 class="text-3xl lg:text-4xl font-bold mb-3 leading-tight">Koperasi<br>Sumber Mulia Barokah</h1>
                <p class="text-sky-100 text-sm lg:text-base max-w-xs">Dari kebersamaan, tumbuh kesejahteraan bersama.</p>

                <div class="absolute top-8 right-8 flex gap-5 text-[10px] font-semibold tracking-widest uppercase opacity-70">
                    <a href="#" class="hover:text-white transition">Tentang Kami</a>
                    <a href="#" class="hover:text-white transition">Bantuan</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        // FIX 5: Perbaikan Preloader agar benar-benar hilang dari layar
        window.addEventListener('load', () => {
            const tl = gsap.timeline();
            tl.to("#preloader", {
                duration: 0.8,
                y: "-100%",
                ease: "power4.inOut",
                delay: 0.5,
                // PENTING: Matikan display setelah animasi selesai
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
