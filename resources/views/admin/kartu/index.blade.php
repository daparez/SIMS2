@extends('layouts.app')

@section('title', 'Cetak Kartu Pelajar - Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold py-1 mb-0"><span class="text-muted fw-light">Cetak /</span> Kartu Pelajar Per Kelas</h4>
</div>

<div class="row">
    @forelse($kelases as $k)
    <div class="col-md-4 col-sm-6 mb-4">
        <div class="card h-100 card-border-shadow-primary hover-elevation">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="avatar avatar-md me-3">
                        <span class="avatar-initial rounded bg-label-primary fs-3"><i class="bx bx-id-card"></i></span>
                    </div>
                    <div>
                        <h5 class="card-title mb-0 fw-bold">Kelas {{ $k->kelas }}</h5>
                        <small class="text-muted">{{ $k->total_siswa }} Siswa Terdaftar</small>
                    </div>
                </div>
                <div class="d-grid mt-3">
                    <a href="{{ route('admin.kartu.cetak', $k->kelas) }}" target="_blank" class="btn btn-outline-primary">
                        <i class="bx bx-printer me-1"></i> Cetak Massal Kartu
                    </a>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="card text-center py-5">
            <div class="card-body">
                <i class="bx bx-user-x display-1 text-muted mb-3"></i>
                <h5 class="text-muted">Belum ada data siswa/kelas terdaftar.</h5>
            </div>
        </div>
    </div>
    @endforelse
</div>
@endsection