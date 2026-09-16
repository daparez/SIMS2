<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // 1. Menampilkan Form Login (Sneat)
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // 2. Memproses Autentikasi Login
    public function login(Request $request)
    {
        // Validasi input dari form
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ], [
            'username.required' => 'NISN / NIP / Username wajib diisi!',
            'password.required' => 'Password wajib diisi!',
        ]);

        // Cek login menggunakan NISN/NIP/Username dan Password
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Ambil role user yang berhasil login
            $user = Auth::user();

            // Redirect otomatis sesuai Role
            if ($user->role === 'admin') {
                return redirect()->intended('/admin/dashboard');
            } elseif ($user->role === 'guru') {
                return redirect()->intended('/guru/dashboard');
            } elseif ($user->role === 'siswa') {
                return redirect()->intended('/siswa/dashboard');
            }

            // Fallback jika role tidak terdefinisi
            return redirect()->intended('/dashboard');
        }

        // Jika login gagal
        return back()->withErrors([
            'username' => 'NISN/NIP atau Password yang Anda masukkan salah.',
        ])->onlyInput('username');
    }

    // 3. Memproses Logout
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah berhasil keluar.');
    }
}