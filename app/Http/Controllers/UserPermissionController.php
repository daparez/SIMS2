<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserPermission;
use Illuminate\Http\Request;

class UserPermissionController extends Controller
{
    /**
     * Tampilkan Daftar Seluruh User & Hak Aksesnya
     */
    public function index(Request $request)
    {
        $query = User::with('permissions')->where('role', '!=', 'admin');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('username', 'like', '%' . $request->search . '%');
        }

        $users = $query->paginate(15)->withQueryString();

        // Daftar Seluruh Fitur Khusus yang Bisa Diatur Hak Aksesnya
        $availablePermissions = [
            'scan_absensi'      => 'Scan QR Code Absensi',
            'rekap_absensi'     => 'Lihat Rekap Absensi',
            'kelola_pengumuman' => 'Kelola Pengumuman Sekolah',
            'cetak_kartu'       => 'Cetak Kartu Pelajar',
            'keuangan'          => 'Kelola Keuangan & SPP',
        ];

        return view('admin.hak_akses.index', compact('users', 'availablePermissions'));
    }

    /**
     * Simpan Perubahan Hak Akses User
     */
    public function update(Request $request, $userId)
    {
        $user = User::findOrFail($userId);

        // Hapus semua permission lama user ini
        UserPermission::where('user_id', $user->id)->delete();

        // Simpan permission baru yang dicentang admin
        if ($request->has('permissions')) {
            foreach ($request->permissions as $perm) {
                UserPermission::create([
                    'user_id'    => $user->id,
                    'permission' => $perm,
                ]);
            }
        }

        return redirect()->back()->with('success', "Hak akses untuk {$user->name} berhasil diperbarui!");
    }
}