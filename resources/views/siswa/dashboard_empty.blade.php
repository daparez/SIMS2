@extends('layouts.app')

@section('title', 'Profil Belum Terhubung - SIM Sekolah')

@section('content')
<div class="row justify-content-center my-5">
    <div class="col-md-6 text-center">
        <div class="card p-4 shadow-sm">
            <div class="card-body">
                <div class="avatar avatar-xl bg-label-warning rounded-circle mx-auto mb-3" style="width: 80px; height: 80px; line-height: 80px;">
                    <i class="bx bx-user-x fs-1"></i>
                </div>
                <h4 class="fw-bold mb-2">Profil Siswa Belum Terhubung</h4>
                <p class="text-muted mb-4">
                    Akun kamu (<span class="fw-bold">{{ Auth::user()->username }}</span>) belum terhubung dengan data pokok siswa di database. Silakan hubungi Petugas Admin / Tata Usaha sekolah untuk melakukan sinkronisasi data NISN kamu.
                </p>
                <div class="d-flex justify-content-center gap-2">
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="btn btn-outline-danger">
                        <i class="bx bx-log-out me-1"></i> Keluar / Logout
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection