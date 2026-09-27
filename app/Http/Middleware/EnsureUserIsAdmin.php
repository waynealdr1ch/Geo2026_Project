<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah user login DAN rolenya admin
        if (Auth::check() && Auth::user()->role === 'admin') {
            return $next($request);
        }

        // Kalau bukan admin, tendang ke login admin atau home
        return redirect('/admin')->with('error', 'Anda tidak memiliki akses administrator.');
    }
}
