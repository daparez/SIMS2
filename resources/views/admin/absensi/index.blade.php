@extends('layouts.app')

@section('title', 'Rekap Absensi - Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold py-1 mb-0"><span class="text-muted fw-light">Absensi /</span> Rekapitulasi Harian</h4>
    <a href="{{ route('admin.absensi.scan') }}" class="btn btn-primary">
        <i class="bx bx-qr-scan me-1"></i> Buka Scanner QR
    </a>
</div>

<div class="card">
    <h5 class="card-header">Rekap Kehadiran Tanggal: {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</h5>
    <div class="table-responsive text-nowrap">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Siswa</th>
                    <th>Kelas</th>
                    <th>Jam Masuk</th>
                    <th>Jam Keluar</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                @forelse($absensis as $index => $a)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        <strong>{{ $a->siswa->nama_lengkap }}</strong>
                        <br><small class="text-muted">NISN: {{ $a->siswa->nisn }}</small>
                    </td>
                    <td><span class="badge bg-label-info">{{ $a->siswa->kelas }}</span></td>
                    <td><span class="badge bg-label-success">{{ $a->jam_masuk }}</span></td>
                    <td>
                        @if($a->jam_keluar)
                            <span class="badge bg-label-danger">{{ $a->jam_keluar }}</span>
                        @else
                            <span class="badge bg-label-secondary">Belum Pulang</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge bg-success">{{ $a->status }}</span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-4">Belum ada aktivitas absensi hari ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection