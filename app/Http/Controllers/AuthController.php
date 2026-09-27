<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;

class AuthController extends Controller
{
    // 1. Tampilkan Halaman Login
    public function showLoginForm() {
        return view('login');
    }

    // 2. Proses Login Manual (Pakai Username)
    public function login(Request $request) {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        $remember = $request->has('remember'); // Cek checkbox Remember Me

        // Auth attempt pakai USERNAME, bukan email
        if (Auth::attempt(['username' => $request->username, 'password' => $request->password], $remember)) {
            $request->session()->regenerate();
            return redirect()->intended('/');
        }

        return back()->withErrors(['username' => 'Username atau Password salah.']);
    }

    // 3. Redirect ke Google
    public function redirectToGoogle() {
        return Socialite::driver('google')->redirect();
    }

    // 4. Handle Balikan dari Google
    public function handleGoogleCallback() {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Logic: Admin sudah daftarin Email user.
            // Kita cek, ada gak email Google ini di database kita?
            $user = User::where('email', $googleUser->getEmail())->first();

            if ($user) {
                // KETEMU! Update ID Google-nya biar tercatat
                $user->update(['google_id' => $googleUser->getId()]);

                // Login user & aktifkan Remember Me
                Auth::login($user, true);
                return redirect('/');
            } else {
                // TIDAK KETEMU -> Tolak login
                return redirect('/login')->withErrors(['username' => 'Akun Google ini belum terdaftar di sistem kami.']);
            }

        } catch (\Exception $e) {
            return redirect('/login')->withErrors('Gagal login dengan Google.');
        }
    }

    // 5. Logout
    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
