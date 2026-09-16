@extends('layouts.app')

@section('title', 'Manajemen Pengumuman - Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold py-1 mb-0"><span class="text-muted fw-light">Admin /</span> Pengumuman Sekolah</h4>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
        <i class="bx bx-plus me-1"></i> Buat Pengumuman Baru
    </button>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="bx bx-check-circle me-1"></i> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="card">
    <h5 class="card-header">Riwayat Pengumuman</h5>
    <div class="table-responsive text-nowrap">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Judul Pengumuman</th>
                    <th>Target</th>
                    <th>Tanggal Terbit</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pengumumans as $index => $p)
                <tr>
                    <td>{{ $pengumumans->firstItem() + $index }}</td>
                    <td>
                        <strong class="text-dark">{{ $p->judul }}</strong>
                        <br><small class="text-muted">{{ Str::limit($p->isi, 60) }}</small>
                    </td>
                    <td>
                        @if($p->target == 'semua')
                            <span class="badge bg-label-primary">Semua (Siswa & Guru)</span>
                        @elseif($p->target == 'siswa')
                            <span class="badge bg-label-info">Siswa</span>
                        @else
                            <span class="badge bg-label-warning">Guru</span>
                        @endif
                    </td>
                    <td>{{ \Carbon\Carbon::parse($p->created_at)->translatedFormat('d F Y, H:i') }} WIB</td>
                    <td>
                        <form action="{{ route('admin.pengumuman.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Hapus pengumuman ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-icon btn-sm btn-outline-danger" title="Hapus">
                                <i class="bx bx-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-4 text-muted">Belum ada pengumuman yang dibuat.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah Pengumuman -->
<div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h5 class="modal-title fw-bold"><i class="bx bx-megaphone text-primary me-2"></i>Buat Pengumuman Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.pengumuman.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Judul Pengumuman</label>
                        <input type="text" name="judul" class="form-control" placeholder="Contoh: Pengumuman Ujian Akhir Semester" required />
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Target Penerima</label>
                        <select name="target" class="form-select" required>
                            <option value="semua">Semua (Siswa & Guru)</option>
                            <option value="siswa">Siswa Sahaja</option>
                            <option value="guru">Guru & Staf Sahaja</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Isi Pengumuman</label>
                        <textarea name="isi" class="form-control" rows="4" placeholder="Tuliskan isi informasi lengkap di sini..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer border-top">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="bx bx-paper-plane me-1"></i> Terbit Pengumuman</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection