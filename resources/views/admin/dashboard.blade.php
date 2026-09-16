@extends('layouts.app')

@section('title', 'Dashboard Admin - SIM Sekolah')

@section('content')
<!-- Welcome Banner dengan Efek Gradient -->
<div class="row">
    <div class="col-lg-12 mb-4 order-0">
        <div class="card bg-primary text-white position-relative overflow-hidden" style="background: linear-gradient(135deg, #696cff 0%, #393bbf 100%);">
            <div class="d-flex align-items-end row">
                <div class="col-sm-7">
                    <div class="card-body">
                        <h4 class="card-title text-white fw-bold mb-2">Selamat Datang Kembali, {{ Auth::user()->name }}! 🎉</h4>
                        <p class="mb-3 text-white-50">
                            Sistem Informasi Sekolah berjalan optimal. Hari ini <span class="fw-bold text-white">{{ $totalHadirHariIni }} siswa</span> sudah melakukan scan absensi QR Code (<span class="fw-bold text-white">{{ $persentaseHadir }}%</span> kehadiran).
                        </p>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.absensi.scan') }}" class="btn btn-sm btn-light text-primary fw-bold">
                                <i class="bx bx-qr-scan me-1"></i> Buka Scanner QR
                            </a>
                            <a href="{{ route('admin.keuangan.create') }}" class="btn btn-sm btn-outline-light">
                                <i class="bx bx-plus me-1"></i> Catat Pembayaran
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-sm-5 text-center text-sm-left">
                    <div class="card-body pb-0 px-0 px-md-4">
                        <img src="{{ asset('assets/img/illustrations/man-with-laptop-light.png') }}" height="150" alt="Admin Dashboard" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Kartu Statistik Utama (Dinamis dari Database) -->
<div class="row">
    <!-- Total Siswa -->
    <div class="col-lg-3 col-md-6 col-6 mb-4">
        <div class="card card-border-shadow-primary h-100">
            <div class="card-body">
                <div class="card-title d-flex align-items-start justify-content-between">
                    <div class="avatar flex-shrink-0 me-3">
                        <span class="avatar-initial rounded bg-label-primary"><i class="bx bx-user fs-3"></i></span>
                    </div>
                    <span class="badge bg-label-success rounded-pill">Aktif</span>
                </div>
                <span class="fw-semibold d-block mb-1 text-muted">Total Siswa</span>
                <h3 class="card-title mb-2 text-primary fw-bold">{{ number_format($totalSiswa ?? 0) }}</h3>
                <small class="text-muted">Siswa terdaftar aktif</small>
            </div>
        </div>
    </div>

    <!-- Total Guru -->
    <div class="col-lg-3 col-md-6 col-6 mb-4">
        <div class="card card-border-shadow-success h-100">
            <div class="card-body">
                <div class="card-title d-flex align-items-start justify-content-between">
                    <div class="avatar flex-shrink-0 me-3">
                        <span class="avatar-initial rounded bg-label-success"><i class="bx bx-user-voice fs-3"></i></span>
                    </div>
                    <span class="badge bg-label-info rounded-pill">Pendidik</span>
                </div>
                <span class="fw-semibold d-block mb-1 text-muted">Total Guru & Staf</span>
                <h3 class="card-title mb-2 text-success fw-bold">{{ number_format($totalGuru ?? 0) }}</h3>
                <small class="text-muted">Tenaga pengajar aktif</small>
            </div>
        </div>
    </div>

    <!-- Total Rombel / Kelas -->
    <div class="col-lg-3 col-md-6 col-6 mb-4">
        <div class="card card-border-shadow-info h-100">
            <div class="card-body">
                <div class="card-title d-flex align-items-start justify-content-between">
                    <div class="avatar flex-shrink-0 me-3">
                        <span class="avatar-initial rounded bg-label-info"><i class="bx bx-home-alt fs-3"></i></span>
                    </div>
                    <span class="badge bg-label-secondary rounded-pill">Rombel</span>
                </div>
                <span class="fw-semibold d-block mb-1 text-muted">Total Kelas</span>
                <h3 class="card-title mb-2 text-info fw-bold">{{ number_format($totalKelas ?? 0) }}</h3>
                <small class="text-muted">Ruang kelas aktif</small>
            </div>
        </div>
    </div>

    <!-- Pemasukan Keuangan Bulan Ini -->
    <div class="col-lg-3 col-md-6 col-6 mb-4">
        <div class="card card-border-shadow-warning h-100">
            <div class="card-body">
                <div class="card-title d-flex align-items-start justify-content-between">
                    <div class="avatar flex-shrink-0 me-3">
                        <span class="avatar-initial rounded bg-label-warning"><i class="bx bx-wallet fs-3"></i></span>
                    </div>
                    <span class="badge bg-label-warning rounded-pill">Bulan Ini</span>
                </div>
                <span class="fw-semibold d-block mb-1 text-muted">Pemasukan SPP</span>
                <h3 class="card-title mb-2 text-warning fw-bold">Rp {{ number_format($pemasukanBulanIni ?? 0, 0, ',', '.') }}</h3>
                <small class="text-success fw-semibold"><i class="bx bx-up-arrow-alt"></i> Terverifikasi</small>
            </div>
        </div>
    </div>
</div>

<!-- Section Pintasan Akses Cepat & Transaksi Terakhir -->
<div class="row">
    <!-- Pintasan Layanan Admin (Quick Links) -->
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="card-title m-0 fw-bold"><i class="bx bx-rocket text-primary me-2"></i>Akses Cepat</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.absensi.scan') }}" class="btn btn-outline-primary text-start p-3">
                        <div class="d-flex align-items-center">
                            <i class="bx bx-qr-scan fs-2 me-3"></i>
                            <div>
                                <strong class="d-block">Scanner Absensi QR</strong>
                                <small class="text-muted">Buka kamera presensi masuk/pulang</small>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('admin.siswa.create') }}" class="btn btn-outline-success text-start p-3">
                        <div class="d-flex align-items-center">
                            <i class="bx bx-user-plus fs-2 me-3"></i>
                            <div>
                                <strong class="d-block">Tambah Siswa Baru</strong>
                                <small class="text-muted">Input siswa & buat akun otomatis</small>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('admin.keuangan.create') }}" class="btn btn-outline-warning text-start p-3">
                        <div class="d-flex align-items-center">
                            <i class="bx bx-credit-card fs-2 me-3"></i>
                            <div>
                                <strong class="d-block">Transaksi SPP Baru</strong>
                                <small class="text-muted">Catat iuran & cetak kuitansi</small>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel 5 Transaksi Keuangan Terbaru -->
    <div class="col-md-8 mb-4">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="card-title m-0 fw-bold"><i class="bx bx-history text-primary me-2"></i>Transaksi Pembayaran Terbaru</h5>
                <a href="{{ route('admin.keuangan.index') }}" class="btn btn-xs btn-outline-primary">Lihat Semua</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Kode TRX</th>
                            <th>Siswa</th>
                            <th>Pembayaran</th>
                            <th>Nominal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transaksiTerbaru as $t)
                        <tr>
                            <td><span class="badge bg-label-primary">{{ $t->kode_transaksi }}</span></td>
                            <td>
                                <strong>{{ $t->siswa->nama_lengkap }}</strong>
                                <br><small class="text-muted">{{ $t->siswa->kelas }}</small>
                            </td>
                            <td><small class="fw-semibold">{{ $t->jenisPembayaran->nama_pembayaran }}</small></td>
                            <td><span class="fw-bold text-success">Rp {{ number_format($t->jumlah_bayar, 0, ',', '.') }}</span></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">Belum ada transaksi recorded hari ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection