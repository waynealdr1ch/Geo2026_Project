<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    // Menampilkan halaman edit profil
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    // Memproses update profil
    public function update(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Validasi: Hanya No Telp dan Alamat yang boleh diubah
        $request->validate([
            'phone_number' => 'required|string|max:20',
            'address' => 'required|string|max:500',
        ]);

        // Update data user
        $user->update([
            'phone_number' => $request->phone_number,
            'address' => $request->address,
        ]);

        return redirect()->route('dashboard')->with('success', 'Profil berhasil diperbarui!');
    }
}
