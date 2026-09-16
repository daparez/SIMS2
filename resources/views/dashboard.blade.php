@extends('layouts.app')

@section('title', 'Dashboard - SIM Sekolah')

@section('content')
<!-- Welcome Banner -->
<div class="row">
    <div class="col-lg-12 mb-4 order-0">
        <div class="card">
            <div class="d-flex align-items-end row">
                <div class="col-sm-7">
                    <div class="card-body">
                        <h5 class="card-title text-primary">Selamat Datang, Admin! 🎉</h5>
                        <p class="mb-4">
                            Sistem Informasi Sekolah berjalan normal. Hari ini terdapat <span class="fw-bold">12 agenda</span> kegiatan dan presensi siswa tercapai <span class="fw-bold">98%</span>.
                        </p>
                        <a href="#" class="btn btn-sm btn-outline-primary">Lihat Laporan Kehadiran</a>
                    </div>
                </div>
                <div class="col-sm-5 text-center text-sm-left">
                    <div class="card-body pb-0 px-0 px-md-4">
                        <img src="{{ asset('assets/img/illustrations/man-with-laptop-light.png') }}" height="140" alt="View Badge User" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Cards Statistics / Ringkasan Data -->
<div class="row">
    <!-- Card Siswa -->
    <div class="col-lg-3 col-md-6 col-6 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="card-title d-flex align-items-start justify-content-between">
                    <div class="avatar flex-shrink-0 me-3">
                        <span class="avatar-initial rounded bg-label-primary"><i class="bx bx-user fs-3"></i></span>
                    </div>
                </div>
                <span class="fw-semibold d-block mb-1">Total Siswa</span>
                <h3 class="card-title mb-2">1,250</h3>
                <small class="text-success fw-semibold"><i class="bx bx-up-arrow-alt"></i> Active</small>
            </div>
        </div>
    </div>

    <!-- Card Guru -->
    <div class="col-lg-3 col-md-6 col-6 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="card-title d-flex align-items-start justify-content-between">
                    <div class="avatar flex-shrink-0 me-3">
                        <span class="avatar-initial rounded bg-label-success"><i class="bx bx-group fs-3"></i></span>
                    </div>
                </div>
                <span class="fw-semibold d-block mb-1">Total Guru & Staf</span>
                <h3 class="card-title mb-2">85</h3>
                <small class="text-success fw-semibold"><i class="bx bx-up-arrow-alt"></i> Active</small>
            </div>
        </div>
    </div>

    <!-- Card Rombel / Kelas -->
    <div class="col-lg-3 col-md-6 col-6 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="card-title d-flex align-items-start justify-content-between">
                    <div class="avatar flex-shrink-0 me-3">
                        <span class="avatar-initial rounded bg-label-info"><i class="bx bx-home-alt fs-3"></i></span>
                    </div>
                </div>
                <span class="fw-semibold d-block mb-1">Total Rombel</span>
                <h3 class="card-title mb-2">36</h3>
                <small class="text-muted fw-semibold">Kelas X - XII</small>
            </div>
        </div>
    </div>

    <!-- Card Tunggakan SPP -->
    <div class="col-lg-3 col-md-6 col-6 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="card-title d-flex align-items-start justify-content-between">
                    <div class="avatar flex-shrink-0 me-3">
                        <span class="avatar-initial rounded bg-label-warning"><i class="bx bx-wallet fs-3"></i></span>
                    </div>
                </div>
                <span class="fw-semibold d-block mb-1">Pembayaran SPP (Bulan Ini)</span>
                <h3 class="card-title mb-2">82%</h3>
                <small class="text-success fw-semibold"><i class="bx bx-up-arrow-alt"></i> Lunas</small>
            </div>
        </div>
    </div>
</div>
@endsection