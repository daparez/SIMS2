@extends('layouts.app')

@section('title', 'Data Jurusan - Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold py-1 mb-0"><span class="text-muted fw-light">Master Data /</span> Data Jurusan</h4>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
        <i class="bx bx-plus me-1"></i> Tambah Jurusan
    </button>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="bx bx-check-circle me-1"></i> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="card">
    <h5 class="card-header">Daftar Jurusan Sekolah</h5>
    <div class="table-responsive text-nowrap">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode</th>
                    <th>Nama Jurusan</th>
                    <th>Deskripsi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                @forelse($jurusans as $index => $j)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td><span class="badge bg-label-primary fw-bold">{{ $j->kode_jurusan }}</span></td>
                    <td><strong>{{ $j->nama_jurusan }}</strong></td>
                    <td>{{ $j->deskripsi ?? '-' }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-1">
                            {{-- Tombol Edit Modal --}}
                            <button type="button" class="btn btn-icon btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#modalEdit{{ $j->id }}" title="Edit">
                                <i class="bx bx-edit-alt"></i>
                            </button>

                            {{-- Tombol Hapus --}}
                            <form action="{{ route('admin.jurusan.destroy', $j->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus jurusan ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-icon btn-sm btn-outline-danger" title="Hapus">
                                    <i class="bx bx-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>

                <!-- MODAL EDIT DATA -->
                <div class="modal fade" id="modalEdit{{ $j->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <form action="{{ route('admin.jurusan.update', $j->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Jurusan</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label">Kode Jurusan</label>
                                        <input type="text" name="kode_jurusan" class="form-control" value="{{ $j->kode_jurusan }}" required />
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Nama Jurusan</label>
                                        <input type="text" name="nama_jurusan" class="form-control" value="{{ $j->nama_jurusan }}" required />
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Deskripsi (Opsional)</label>
                                        <textarea name="deskripsi" class="form-control" rows="3">{{ $j->deskripsi }}</textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-4">Belum ada data jurusan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- MODAL TAMBAH DATA -->
<div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form action="{{ route('admin.jurusan.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Jurusan Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Kode Jurusan (Contoh: RPL, TKJ, MIPA)</label>
                        <input type="text" name="kode_jurusan" class="form-control" placeholder="RPL" required />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap Jurusan</label>
                        <input type="text" name="nama_jurusan" class="form-control" placeholder="Rekayasa Perangkat Lunak" required />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi (Opsional)</label>
                        <textarea name="deskripsi" class="form-control" rows="3" placeholder="Keterangan jurusan..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection