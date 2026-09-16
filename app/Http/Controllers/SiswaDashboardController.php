<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Absensi;
use App\Models\PembayaranSpp;
use App\Models\Pengumuman;
use App\Models\SekolahSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class SiswaDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // 1. Ambil Data Siswa berdasarkan User yang sedang Login
        $siswa = Siswa::where('user_id', $user->id)
                      ->orWhere('nisn', $user->username)
                      ->first();

        if (!$siswa) {
            return view('siswa.dashboard_empty'); // Fallback jika profil siswa belum dihubungkan
        }

        // 2. Data Statistik Absensi Siswa
        $totalHadir = Absensi::where('siswa_id', $siswa->id)->where('status', 'Hadir')->count();
        $totalIzin  = Absensi::where('siswa_id', $siswa->id)->where('status', 'Izin')->count();
        $totalSakit = Absensi::where('siswa_id', $siswa->id)->where('status', 'Sakit')->count();
        $totalAlpha = Absensi::where('siswa_id', $siswa->id)->where('status', 'Alpha')->count();

        // Cek status absensi hari ini (Timezone Asia/Jakarta)
        $today = Carbon::now('Asia/Jakarta')->format('Y-m-d');
        $absensiHariIni = Absensi::where('siswa_id', $siswa->id)->where('tanggal', $today)->first();

        // 3. Data Riwayat Pembayaran SPP / Keuangan
        $riwayatPembayaran = PembayaranSpp::with('jenisPembayaran')
            ->where('siswa_id', $siswa->id)
            ->latest()
            ->take(5)
            ->get();

        $totalDibayar = PembayaranSpp::where('siswa_id', $siswa->id)->sum('jumlah_bayar');

        // 4. Ambil Data Pengumuman Terbaru untuk Siswa
        $pengumumans = Pengumuman::whereIn('target', ['semua', 'siswa'])
            ->where('status', 'aktif')
            ->latest()
            ->take(3)
            ->get();

        // 5. Ambil Settingan Sekolah untuk Kebutuhan Kartu Pelajar Digital
        $setting = SekolahSetting::first();

        return view('siswa.dashboard', compact(
            'siswa',
            'totalHadir',
            'totalIzin',
            'totalSakit',
            'totalAlpha',
            'absensiHariIni',
            'riwayatPembayaran',
            'totalDibayar',
            'pengumumans',
            'setting'
        ));
    }

    /**
     * Memproses Update Biodata & Upload Seluruh Berkas Dokumen Mandiri oleh Siswa
     */
    public function updateBiodata(Request $request)
    {
        $user = Auth::user();
        $siswa = Siswa::where('user_id', $user->id)
                      ->orWhere('nisn', $user->username)
                      ->firstOrFail();

        // Validasi Form Input
        $request->validate([
            'nik'            => 'nullable|numeric|digits:16',
            'tempat_lahir'   => 'nullable|string|max:100',
            'tgl_lahir'      => 'nullable|date',
            'agama'          => 'nullable|string',
            'no_hp'          => 'nullable|string|max:15',
            'nama_ortu'      => 'nullable|string|max:100',
            'no_hp_ortu'     => 'nullable|string|max:15',
            'alamat'         => 'nullable|string',
            'foto'           => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'file_kk'        => 'nullable|mimes:pdf,jpg,jpeg,png|max:2048',
            'file_ijazah'    => 'nullable|mimes:pdf,jpg,jpeg,png|max:2048',
            'file_akta'      => 'nullable|mimes:pdf,jpg,jpeg,png|max:2048',
            'file_ktp_ayah'  => 'nullable|mimes:pdf,jpg,jpeg,png|max:2048',
            'file_ktp_ibu'   => 'nullable|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only([
            'nik', 'tempat_lahir', 'tgl_lahir', 'agama',
            'no_hp', 'nama_ortu', 'no_hp_ortu', 'alamat'
        ]);

        // List Kolom Dokumen Digital
        $docFields = ['foto', 'file_kk', 'file_ijazah', 'file_akta', 'file_ktp_ayah', 'file_ktp_ibu'];

        foreach ($docFields as $field) {
            if ($request->hasFile($field)) {
                // Hapus file lama jika ada penggantian berkas
                if ($siswa->$field) {
                    Storage::disk('public')->delete($siswa->$field);
                }
                
                $folder = ($field === 'foto') ? 'foto_siswa' : 'dokumen_siswa';
                $data[$field] = $request->file($field)->store($folder, 'public');
            }
        }

        // Simpan Perubahan ke Database
        $siswa->update($data);

        return redirect()->back()->with('success', 'Biodata & Dokumen Pendukung Berhasil Diperbarui!');
    }
}