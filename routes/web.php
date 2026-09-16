<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\KartuPelajarController;
use App\Http\Controllers\SiswaDashboardController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\RuanganController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\PengumumanController;
use App\Http\Controllers\SekolahSettingController;
use App\Http\Controllers\UserPermissionController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\PembayaranSppController;
use App\Http\Controllers\TahunAjaranController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\MataPelajaranController;
use App\Http\Controllers\JurusanController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\SiswaController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 1. Redirect Halaman Utama langsung ke Form Login
Route::get('/', function () {
    return redirect()->route('login');
});

// 2. Route Autentikasi Login & Logout (Laravel Breeze)
Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

Route::get('/', [LandingController::class, 'index'])->name('welcome');

// 3. GROUP ROUTE KHUSUS ADMIN (Perlu Login + Role Admin)
Route::middleware(['auth', 'role:admin'])->prefix('admin')->as('admin.')->group(function () {
    
    // Dashboard Admin
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/setting-landing', [LandingController::class, 'editProfile'])->name('setting.landing');
    Route::put('/setting-landing', [LandingController::class, 'updateProfile'])->name('setting.landing.update');

    // Route Galeri
    Route::post('/galeri-landing', [LandingController::class, 'storeGaleri'])->name('setting.landing.galeri.store');
    Route::delete('/galeri-landing/{id}', [LandingController::class, 'destroyGaleri'])->name('setting.landing.galeri.destroy');
    
    // Route khusus Auto Rollover Kenaikan Kelas
    Route::post('tahun-ajaran/auto-rollover', [TahunAjaranController::class, 'autoRollover'])->name('tahun-ajaran.auto-rollover');

    // Kelola Hak Akses User
    Route::get('/hak-akses', [UserPermissionController::class, 'index'])->name('hak-akses.index');
    Route::put('/hak-akses/{userId}', [UserPermissionController::class, 'update'])->name('hak-akses.update');

    // Kelola Jadwal Pelajaran
    Route::get('/jadwal', [JadwalController::class, 'index'])->name('jadwal.index');
    Route::post('/jadwal', [JadwalController::class, 'store'])->name('jadwal.store');
    Route::delete('/jadwal/{id}', [JadwalController::class, 'destroy'])->name('jadwal.destroy');
    Route::get('/jadwal/cetak', [JadwalController::class, 'cetak'])->name('jadwal.cetak');
    Route::delete('/jadwal/reset', [JadwalController::class, 'reset'])->name('jadwal.reset');

    // Route Kelola Jam Pelajaran Manual
Route::get('/jadwal/jam-pelajaran', [JadwalController::class, 'jamIndex'])->name('jadwal.jam.index');
Route::post('/jadwal/jam-pelajaran', [JadwalController::class, 'jamStore'])->name('jadwal.jam.store');
Route::delete('/jadwal/jam-pelajaran/{id}', [JadwalController::class, 'jamDestroy'])->name('jadwal.jam.destroy');

    // Pengumuman
    Route::get('/pengumuman', [PengumumanController::class, 'index'])->name('pengumuman.index');
    Route::post('/pengumuman', [PengumumanController::class, 'store'])->name('pengumuman.store');
    Route::delete('/pengumuman/{id}', [PengumumanController::class, 'destroy'])->name('pengumuman.destroy');

    // Setting Lokasi & Radius Sekolah
    Route::get('/setting-lokasi', [SekolahSettingController::class, 'index'])->name('setting.lokasi');
    Route::put('/setting-lokasi', [SekolahSettingController::class, 'update'])->name('setting.lokasi.update');

    // Modul Keuangan & SPP
    Route::get('/keuangan/kuitansi/{pembayaran}', [PembayaranSppController::class, 'cetakKuitansi'])->name('keuangan.kuitansi');
    Route::resource('keuangan', PembayaranSppController::class)->except(['edit', 'update', 'show']);

    // Resource Data Master
    Route::get('/guru/cetak-pdf', [GuruController::class, 'cetakPdf'])->name('guru.cetak');
    Route::resource('guru', GuruController::class);

    // Route Barang & Kategori
    Route::post('/kategori-barang', [BarangController::class, 'storeKategori'])->name('kategori.store');
    Route::resource('barang', BarangController::class)->except(['create', 'edit', 'show']);
    
    Route::resource('mapel', MataPelajaranController::class)->except(['create', 'edit', 'show']);
    Route::resource('kelas', KelasController::class)->except(['create', 'edit', 'show']);
    Route::resource('ruangan', RuanganController::class)->except(['create', 'edit', 'show']);
    Route::patch('tahun-ajaran/{tahunAjaran}/activate', [TahunAjaranController::class, 'activate'])->name('tahun-ajaran.activate');
    Route::resource('jurusan', JurusanController::class)->except(['create', 'edit', 'show']);
    Route::resource('tahun-ajaran', TahunAjaranController::class)->except(['create', 'edit', 'show']);
    Route::resource('siswa', SiswaController::class);
});


// 4. ROUTE FITUR DIBAGIKAN / BISA DIAKSES SISWA & GURU (Berdasarkan Hak Akses Permission)
Route::middleware(['auth'])->prefix('admin')->as('admin.')->group(function () {
    
    // Absensi QR Code (Bisa diakses Admin / Siapapun yang diberi Hak Akses)
    Route::get('/absensi/scan', [AbsensiController::class, 'scan'])->name('absensi.scan');
    Route::post('/absensi/scan-process', [AbsensiController::class, 'scanProcess'])->name('absensi.scan-process');
    Route::get('/absensi/rekap', [AbsensiController::class, 'index'])->name('absensi.index');

    // Cetak Kartu Pelajar Massal
    Route::get('/kartu-pelajar', [KartuPelajarController::class, 'index'])->name('kartu.index');
    Route::get('/kartu-pelajar/cetak/{kelas}', [KartuPelajarController::class, 'cetakPerKelas'])->name('kartu.cetak');
});


// 5. GROUP ROUTE KHUSUS GURU
Route::middleware(['auth', 'role:guru'])->prefix('guru')->as('guru.')->group(function () {
    Route::get('/dashboard', function () {
        return view('guru.dashboard');
    })->name('dashboard');
});


// 6. GROUP ROUTE KHUSUS SISWA
Route::middleware(['auth', 'role:siswa'])->prefix('siswa')->as('siswa.')->group(function () {
    Route::get('/dashboard', [SiswaDashboardController::class, 'index'])->name('dashboard');
    Route::put('/biodata/update', [SiswaDashboardController::class, 'updateBiodata'])->name('biodata.update');
});


// 7. ROUTE PROFILE (Semua Role)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';