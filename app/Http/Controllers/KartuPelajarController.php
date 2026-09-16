<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\SekolahSetting;
use Illuminate\Http\Request;

class KartuPelajarController extends Controller
{
    /**
     * Tampilkan Daftar Kelas untuk Cetak Kartu
     */
    public function index()
    {
        // Ambil daftar kelas beserta jumlah siswanya
        $kelases = Siswa::select('kelas')
            ->selectRaw('count(*) as total_siswa')
            ->groupBy('kelas')
            ->orderBy('kelas', 'asc')
            ->get();

        return view('admin.kartu.index', compact('kelases'));
    }

    /**
     * Tampilkan Seluruh Kartu Pelajar Berdasarkan Kelas (Siap Cetak Massal)
     */
    public function cetakPerKelas($kelas)
    {
        $siswas = Siswa::where('kelas', $kelas)->orderBy('nama_lengkap', 'asc')->get();
        $setting = SekolahSetting::first();

        return view('admin.kartu.cetak_kelas', compact('siswas', 'kelas', 'setting'));
    }
}