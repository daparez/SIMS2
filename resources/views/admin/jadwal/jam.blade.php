@extends('layouts.app')

@section('title', 'Atur Master Jam Pelajaran - SIMAK')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold py-1 mb-0"><span class="text-muted fw-light">Admin /</span> Pengaturan Master Jam Pelajaran</h4>
    <a href="{{ route('admin.jadwal.index') }}" class="btn btn-secondary">
        <i class="bx bx-arrow-back me-1"></i> Kembali ke Matriks Jadwal
    </a>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="bx bx-check-circle me-1"></i> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="row">
    <!-- FORM TAMBAH JAM -->
    <div class="col-md-4 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom fw-bold text-primary">
                <i class="bx bx-plus-circle me-1"></i> Tambah Slot Waktu
            </div>
            <div class="card-body py-3">
                <form action="{{ route('admin.jadwal.jam.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Hari</label>
                        <select name="hari" class="form-select" required>
                            <option value="Senin">Senin</option>
                            <option value="Selasa">Selasa</option>
                            <option value="Rabu">Rabu</option>
                            <option value="Kamis">Kamis</option>
                            <option value="Jumat">Jumat</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Jam Ke-</label>
                        <input type="number" name="jam_ke" class="form-control" placeholder="1, 2, 3..." required />
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold">Jam Mulai</label>
                            <input type="time" name="jam_mulai" class="form-control" required />
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold">Jam Selesai</label>
                            <input type="time" name="jam_selesai" class="form-control" required />
                        </div>
                    </div>

                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="is_istirahat" id="is_istirahat" value="1">
                        <label class="form-check-label fw-semibold text-danger" for="is_istirahat">
                            Tandai Sebagai Jam Istirahat
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary w-100"><i class="bx bx-save me-1"></i> Simpan Slot Jam</button>
                </form>
            </div>
        </div>
    </div>

    <!-- DAFTAR JAM PELAJARAN SEKARANG -->
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom fw-bold">
                <i class="bx bx-time me-1"></i> Daftar Jam Pelajaran Terdaftar
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Hari</th>
                            <th>Jam Ke</th>
                            <th>Waktu</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($jamPelajarans->flatten() as $j)
                        <tr>
                            <td><span class="badge bg-label-primary">{{ $j->hari }}</span></td>
                            <td>Jam Ke-{{ $j->jam_ke }}</td>
                            <td>{{ \Carbon\Carbon::parse($j->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($j->jam_selesai)->format('H:i') }}</td>
                            <td>
                                @if($j->is_istirahat)
                                    <span class="badge bg-warning">ISTIRAHAT</span>
                                @else
                                    <span class="badge bg-success">KBM / Belajar</span>
                                @endif
                            </td>
                            <td>
                                <form action="{{ route('admin.jadwal.jam.destroy', $j->id) }}" method="POST" onsubmit="return confirm('Hapus slot jam ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-xs btn-outline-danger"><i class="bx bx-trash"></i> Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center py-4 text-muted">Belum ada slot jam pelajaran. Silakan tambahkan lewat form di samping.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection