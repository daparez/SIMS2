<?php

namespace App\Http\Controllers;

use App\Models\ProfileSekolah;
use App\Models\Pengumuman;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\Jurusan;
use App\Models\Kelas;
use App\Models\Galeri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LandingController extends Controller
{
    /**
     * Tampilkan Halaman Depan / Landing Welcome Page (Publik)
     */
    public function index()
    {
        $profile = ProfileSekolah::first();

        // Data statistik real-time dari database
        $totalSiswa   = Siswa::count();
        $totalGuru    = Guru::count();
        $totalJurusan = Jurusan::count();
        $totalKelas   = Kelas::count();

        // Data Dinamis untuk Komponen Landing Page
        $jurusans    = Jurusan::all();
        $pengumumans = Pengumuman::latest()->take(3)->get();
        $galeris     = Galeri::latest()->take(6)->get();

        return view('welcome', compact(
            'profile', 
            'totalSiswa', 
            'totalGuru', 
            'totalJurusan', 
            'totalKelas',
            'jurusans',
            'pengumumans', 
            'galeris'
        ));
    }

    /**
     * Tampilkan Form Kelola Halaman Landing & Galeri (Khusus Admin)
     */
    public function editProfile()
    {
        $profile = ProfileSekolah::firstOrCreate(['id' => 1]);
        $galeris = Galeri::latest()->get();

        return view('admin.setting.landing', compact('profile', 'galeris'));
    }

    /**
     * Simpan Perubahan Informasi & Foto Landing Page
     */
    public function updateProfile(Request $request)
    {
        $profile = ProfileSekolah::firstOrCreate(['id' => 1]);

        $request->validate([
            'nama_sekolah'         => 'required|string|max:255',
            'slogan'               => 'nullable|string|max:255',
            'sejarah_sekolah'      => 'nullable|string',
            'visi'                 => 'nullable|string',
            'misi'                 => 'nullable|string',
            'foto_hero'            => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
            'foto_gedung'          => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
            'foto_kepala_sekolah'  => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = $request->except(['foto_hero', 'foto_gedung', 'foto_kepala_sekolah']);

        // Handler Upload Foto Hero Banner
        if ($request->hasFile('foto_hero')) {
            if ($profile->foto_hero && Storage::disk('public')->exists($profile->foto_hero)) {
                Storage::disk('public')->delete($profile->foto_hero);
            }
            $data['foto_hero'] = $request->file('foto_hero')->store('landing', 'public');
        }

        // Handler Upload Foto Gedung / Lingkungan
        if ($request->hasFile('foto_gedung')) {
            if ($profile->foto_gedung && Storage::disk('public')->exists($profile->foto_gedung)) {
                Storage::disk('public')->delete($profile->foto_gedung);
            }
            $data['foto_gedung'] = $request->file('foto_gedung')->store('landing', 'public');
        }

        // Handler Upload Foto Kepala Sekolah
        if ($request->hasFile('foto_kepala_sekolah')) {
            if ($profile->foto_kepala_sekolah && Storage::disk('public')->exists($profile->foto_kepala_sekolah)) {
                Storage::disk('public')->delete($profile->foto_kepala_sekolah);
            }
            $data['foto_kepala_sekolah'] = $request->file('foto_kepala_sekolah')->store('landing', 'public');
        }

        $profile->update($data);

        return redirect()->back()->with('success', 'Informasi & Foto Landing Page Sekolah berhasil diperbarui!');
    }

    /**
     * Simpan Foto Galeri Baru
     */
    public function storeGaleri(Request $request)
    {
        $request->validate([
            'judul'    => 'required|string|max:255',
            'kategori' => 'required|string|max:100',
            'foto'     => 'required|image|mimes:jpg,jpeg,png,webp|max:3072',
        ]);

        $path = $request->file('foto')->store('galeri', 'public');

        Galeri::create([
            'judul'    => $request->judul,
            'kategori' => $request->kategori,
            'foto'     => $path,
        ]);

        return redirect()->back()->with('success', 'Foto galeri kegiatan berhasil ditambahkan!');
    }

    /**
     * Hapus Foto Galeri
     */
    public function destroyGaleri($id)
    {
        $galeri = Galeri::findOrFail($id);

        if ($galeri->foto && Storage::disk('public')->exists($galeri->foto)) {
            Storage::disk('public')->delete($galeri->foto);
        }

        $galeri->delete();

        return redirect()->back()->with('success', 'Foto galeri berhasil dihapus!');
    }
}