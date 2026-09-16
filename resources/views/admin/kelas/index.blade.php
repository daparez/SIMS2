@extends('layouts.app')

@section('title', 'Data Kelas - Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold py-1 mb-0"><span class="text-muted fw-light">Master Data /</span> Data Kelas</h4>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
        <i class="bx bx-plus me-1"></i> Tambah Kelas
    </button>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="bx bx-check-circle me-1"></i> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="card">
    <h5 class="card-header">Daftar Kelas Sekolah</h5>
    <div class="table-responsive text-nowrap">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Kelas</th>
                    <th>Tingkat</th>
                    <th>Jurusan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                @forelse($kelases as $index => $k)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td><strong>{{ $k->nama_kelas }}</strong></td>
                    <td>
                        <span class="badge bg-label-info">Tingkat {{ $k->tingkat }}</span>
                    </td>
                    <td>
                        @if($k->jurusan)
                            <span class="badge bg-label-primary">{{ $k->jurusan->nama_jurusan }} ({{ $k->jurusan->kode_jurusan }})</span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex align-items-center gap-1">
                            {{-- Tombol Edit Modal --}}
                            <button type="button" class="btn btn-icon btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#modalEdit{{ $k->id }}" title="Edit">
                                <i class="bx bx-edit-alt"></i>
                            </button>

                            {{-- Tombol Hapus --}}
                            <form action="{{ route('admin.kelas.destroy', $k->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kelas ini?')">
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
                <div class="modal fade" id="modalEdit{{ $k->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <form action="{{ route('admin.kelas.update', $k->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Kelas</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label">Nama Kelas (Contoh: 10 RPL 1)</label>
                                        <input type="text" name="nama_kelas" class="form-control" value="{{ $k->nama_kelas }}" required />
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Tingkat</label>
                                        <select name="tingkat" class="form-select" required>
                                            <option value="10" {{ $k->tingkat == '10' ? 'selected' : '' }}>Tingkat 10</option>
                                            <option value="11" {{ $k->tingkat == '11' ? 'selected' : '' }}>Tingkat 11</option>
                                            <option value="12" {{ $k->tingkat == '12' ? 'selected' : '' }}>Tingkat 12</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Jurusan (Opsional)</label>
                                        <select name="jurusan_id" class="form-select">
                                            <option value="">-- Tanpa Jurusan --</option>
                                            @foreach($jurusans as $j)
                                                <option value="{{ $j->id }}" {{ $k->jurusan_id == $j->id ? 'selected' : '' }}>
                                                    {{ $j->nama_jurusan }} ({{ $j->kode_jurusan }})
                                                </option>
                                            @endforeach
                                        </select>
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
                    <td colspan="5" class="text-center py-4">Belum ada data kelas.</td>
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
            <form action="{{ route('admin.kelas.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Kelas Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Kelas (Contoh: 10 RPL 1)</label>
                        <input type="text" name="nama_kelas" class="form-control" placeholder="10 RPL 1" required />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tingkat</label>
                        <select name="tingkat" class="form-select" required>
                            <option value="10">Tingkat 10</option>
                            <option value="11">Tingkat 11</option>
                            <option value="12">Tingkat 12</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jurusan (Opsional)</label>
                        <select name="jurusan_id" class="form-select">
                            <option value="">-- Tanpa Jurusan --</option>
                            @foreach($jurusans as $j)
                                <option value="{{ $j->id }}">{{ $j->nama_jurusan }} ({{ $j->kode_jurusan }})</option>
                            @endforeach
                        </select>
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