@extends('layouts.app')

@section('title', 'Mata Pelajaran - Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold py-1 mb-0"><span class="text-muted fw-light">Master Data /</span> Mata Pelajaran</h4>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
        <i class="bx bx-plus me-1"></i> Tambah Mapel
    </button>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="bx bx-check-circle me-1"></i> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="card">
    <h5 class="card-header">Daftar Mata Pelajaran</h5>
    <div class="table-responsive text-nowrap">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode Mapel</th>
                    <th>Nama Mata Pelajaran</th>
                    <th>Kelompok</th>
                    <th>Beban (Jam/Mgg)</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                @forelse($mapels as $index => $m)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td><span class="badge bg-label-primary fw-bold">{{ $m->kode_mapel }}</span></td>
                    <td><strong>{{ $m->nama_mapel }}</strong></td>
                    <td>
                        <span class="badge bg-label-info">Kelompok {{ $m->kelompok }}</span>
                    </td>
                    <td>{{ $m->sks_jam }} Jam</td>
                    <td>
                        <div class="d-flex align-items-center gap-1">
                            {{-- Tombol Edit Modal --}}
                            <button type="button" class="btn btn-icon btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#modalEdit{{ $m->id }}" title="Edit">
                                <i class="bx bx-edit-alt"></i>
                            </button>

                            {{-- Tombol Hapus --}}
                            <form action="{{ route('admin.mapel.destroy', $m->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus mata pelajaran ini?')">
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
                <div class="modal fade" id="modalEdit{{ $m->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <form action="{{ route('admin.mapel.update', $m->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Mata Pelajaran</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label">Kode Mapel</label>
                                        <input type="text" name="kode_mapel" class="form-control" value="{{ $m->kode_mapel }}" required />
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Nama Mata Pelajaran</label>
                                        <input type="text" name="nama_mapel" class="form-control" value="{{ $m->nama_mapel }}" required />
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Kelompok Kurikulum</label>
                                        <select name="kelompok" class="form-select" required>
                                            <option value="A" {{ $m->kelompok == 'A' ? 'selected' : '' }}>Kelompok A (Wajib Umum)</option>
                                            <option value="B" {{ $m->kelompok == 'B' ? 'selected' : '' }}>Kelompok B (Wajib Kewilayahan)</option>
                                            <option value="C1" {{ $m->kelompok == 'C1' ? 'selected' : '' }}>Kelompok C1 (Dasar Bidang Keahlian)</option>
                                            <option value="C2" {{ $m->kelompok == 'C2' ? 'selected' : '' }}>Kelompok C2 (Dasar Program Keahlian)</option>
                                            <option value="C3" {{ $m->kelompok == 'C3' ? 'selected' : '' }}>Kelompok C3 (Kompetensi Keahlian)</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Beban Jam Pelajaran per Minggu</label>
                                        <input type="number" name="sks_jam" class="form-control" value="{{ $m->sks_jam }}" min="1" max="10" required />
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
                    <td colspan="6" class="text-center py-4">Belum ada data mata pelajaran.</td>
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
            <form action="{{ route('admin.mapel.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Mata Pelajaran Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Kode Mapel (Contoh: MTK, PWEB)</label>
                        <input type="text" name="kode_mapel" class="form-control" placeholder="PWEB" required />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Mata Pelajaran</label>
                        <input type="text" name="nama_mapel" class="form-control" placeholder="Pemrograman Web & Perangkat Bergerak" required />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kelompok Kurikulum</label>
                        <select name="kelompok" class="form-select" required>
                            <option value="A">Kelompok A (Wajib Umum)</option>
                            <option value="B">Kelompok B (Wajib Kewilayahan)</option>
                            <option value="C1">Kelompok C1 (Dasar Bidang Keahlian)</option>
                            <option value="C2">Kelompok C2 (Dasar Program Keahlian)</option>
                            <option value="C3">Kelompok C3 (Kompetensi Keahlian)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Beban Jam Pelajaran per Minggu</label>
                        <input type="number" name="sks_jam" class="form-control" value="2" min="1" max="10" required />
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