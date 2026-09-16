<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleManager
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // 1. Jika belum login, tendang ke login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // 2. Jika role user SAMA dengan role yang diminta halaman, izinkan lewat
        if (Auth::user()->role === $role) {
            return $next($request);
        }

        // 3. Jika role TIDAK COCOK, arahkan ke dashboard role yang bersangkutan
        $userRole = Auth::user()->role;

        if ($userRole === 'admin' && !$request->is('admin/*')) {
            return redirect()->route('admin.dashboard');
        } elseif ($userRole === 'guru' && !$request->is('guru/*')) {
            return redirect()->route('guru.dashboard');
        } elseif ($userRole === 'siswa' && !$request->is('siswa/*')) {
            return redirect()->route('siswa.dashboard');
        }

        // Fallback jika terjadi konflik
        return $next($request);
    }
}