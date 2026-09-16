@extends('layouts.app')

@section('title', 'Tahun Ajaran - Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold py-1 mb-0"><span class="text-muted fw-light">Master Data /</span> Tahun Ajaran</h4>
    
    <div class="d-flex gap-2">
        <!-- Tombol Auto Rollover (Kenaikan Kelas & Tahun Baru) -->
        <form action="{{ route('admin.tahun-ajaran.auto-rollover') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin memproses Tahun Ajaran Baru? Siswa kelas 10 & 11 akan otomatis NAIK KELAS, dan kelas 12 akan otomatis LULUS!')">
            @csrf
            <button type="submit" class="btn btn-warning">
                <i class="bx bx-fast-forward me-1"></i> Lanjut TA Baru & Naik Kelas
            </button>
        </form>

        <!-- Tombol Manual Tambah -->
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="bx bx-plus me-1"></i> Tambah Manual
        </button>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="bx bx-check-circle me-1"></i> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="bx bx-error me-1"></i> {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="card">
    <h5 class="card-header">Daftar Tahun Ajaran & Semester</h5>
    <div class="table-responsive text-nowrap">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tahun Ajaran</th>
                    <th>Semester</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                @forelse($tahunAjarans as $index => $t)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td><strong>{{ $t->tahun }}</strong></td>
                    <td>
                        <span class="text-capitalize">{{ $t->semester }}</span>
                    </td>
                    <td>
                        @if($t->is_active)
                            <span class="badge bg-label-success"><i class="bx bx-check me-1"></i> Aktif</span>
                        @else
                            <span class="badge bg-label-secondary">Tidak Aktif</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex align-items-center gap-1">
                            {{-- Tombol Aktifkan --}}
                            @if(!$t->is_active)
                            <form action="{{ route('admin.tahun-ajaran.activate', $t->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-xs btn-outline-success" title="Set Aktif">
                                    <i class="bx bx-power-off me-1"></i> Aktifkan
                                </button>
                            </form>
                            @endif

                            {{-- Tombol Edit Modal --}}
                            <button type="button" class="btn btn-icon btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#modalEdit{{ $t->id }}" title="Edit">
                                <i class="bx bx-edit-alt"></i>
                            </button>

                            {{-- Tombol Hapus --}}
                            @if(!$t->is_active)
                            <form action="{{ route('admin.tahun-ajaran.destroy', $t->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus tahun ajaran ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-icon btn-sm btn-outline-danger" title="Hapus">
                                    <i class="bx bx-trash"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>

                <!-- MODAL EDIT DATA -->
                <div class="modal fade" id="modalEdit{{ $t->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <form action="{{ route('admin.tahun-ajaran.update', $t->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Tahun Ajaran</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label">Tahun Ajaran</label>
                                        <input type="text" name="tahun" class="form-control" value="{{ $t->tahun }}" required />
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Semester</label>
                                        <select name="semester" class="form-select" required>
                                            <option value="ganjil" {{ $t->semester == 'ganjil' ? 'selected' : '' }}>Ganjil</option>
                                            <option value="genap" {{ $t->semester == 'genap' ? 'selected' : '' }}>Genap</option>
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
                    <td colspan="5" class="text-center py-4">Belum ada data tahun ajaran.</td>
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
            <form action="{{ route('admin.tahun-ajaran.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Tahun Ajaran Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tahun Ajaran (Contoh: 2025/2026)</label>
                        <input type="text" name="tahun" class="form-control" placeholder="2025/2026" required />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Semester</label>
                        <select name="semester" class="form-select" required>
                            <option value="ganjil">Ganjil</option>
                            <option value="genap">Genap</option>
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