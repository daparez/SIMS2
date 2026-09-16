<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Absensi;
use App\Models\PembayaranSpp;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::now()->format('Y-m-d');

        // 1. Ambil Total Data Induk
        $totalSiswa = Siswa::where('status_siswa', 'aktif')->count();
        $totalGuru  = Guru::count();
        $totalKelas = Kelas::count();

        // 2. Ambil Pemasukan Keuangan Bulan Ini
        $pemasukanBulanIni = PembayaranSpp::whereMonth('tgl_bayar', Carbon::now()->month)
            ->whereYear('tgl_bayar', Carbon::now()->year)
            ->sum('jumlah_bayar');

        // 3. Ambil Statistik Absensi Hari Ini
        $totalHadirHariIni = Absensi::where('tanggal', $today)->where('status', 'Hadir')->count();
        $persentaseHadir   = $totalSiswa > 0 ? round(($totalHadirHariIni / $totalSiswa) * 100) : 0;

        // 4. Ambil 5 Transaksi Keuangan Terbaru
        $transaksiTerbaru = PembayaranSpp::with(['siswa', 'jenisPembayaran'])->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalSiswa',
            'totalGuru',
            'totalKelas',
            'pemasukanBulanIni',
            'totalHadirHariIni',
            'persentaseHadir',
            'transaksiTerbaru'
        ));
    }
}