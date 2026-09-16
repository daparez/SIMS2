@extends('layouts.app')

@section('title', 'Data Siswa - Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold py-1 mb-0"><span class="text-muted fw-light">Admin /</span> Data Siswa</h4>
    <a href="{{ route('admin.siswa.create') }}" class="btn btn-primary">
        <i class="bx bx-plus me-1"></i> Tambah Siswa Baru
    </a>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="bx bx-check-circle me-1"></i> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="card">
    <!-- Header & Form Filter / Pencarian -->
    <div class="card-header pb-0">
        <div class="d-flex justify-content-between align-items-center row gy-2 mb-3">
            <div class="col-md-4">
                <h5 class="mb-0 fw-bold">Daftar Seluruh Siswa</h5>
            </div>
            
            <div class="col-md-8">
                <form action="{{ route('admin.siswa.index') }}" method="GET" class="row g-2 justify-content-end">
                    <!-- Filter Kelas -->
                    <div class="col-sm-4">
                        <select name="kelas" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="">-- Semua Kelas --</option>
                            @foreach($listKelas as $k)
                                <option value="{{ $k }}" {{ request('kelas') == $k ? 'selected' : '' }}>
                                    Kelas {{ $k }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Input Search -->
                    <div class="col-sm-6">
                        <div class="input-group input-group-sm">
                            <input type="text" name="search" class="form-control" placeholder="Cari Nama / NISN / NIS..." value="{{ request('search') }}">
                            <button class="btn btn-outline-primary" type="submit">
                                <i class="bx bx-search"></i>
                            </button>
                            @if(request('search') || request('kelas'))
                                <a href="{{ route('admin.siswa.index') }}" class="btn btn-outline-secondary" title="Reset Filter">
                                    <i class="bx bx-refresh"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Tabel Data Siswa -->
    <div class="table-responsive text-nowrap">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Siswa</th>
                    <th>NISN / NIS</th>
                    <th>Kelas & Jurusan</th>
                    <th>Status</th>
                    <th>Pass Default</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                @forelse($siswas as $index => $s)
                <tr>
                    <td>{{ $siswas->firstItem() + $index }}</td>
                    <td>
                        <div class="d-flex align-items-center">
                            @if($s->foto)
                                <img src="{{ asset('storage/' . $s->foto) }}" alt="Avatar" class="rounded-circle me-3" width="40" height="40" style="object-fit: cover;">
                            @else
                                <div class="avatar avatar-xs me-3">
                                    <span class="avatar-initial rounded-circle bg-label-primary">
                                        {{ strtoupper(substr($s->nama_lengkap, 0, 2)) }}
                                    </span>
                                </div>
                            @endif
                            <div>
                                <h6 class="mb-0 fw-semibold">{{ $s->nama_lengkap }}</h6>
                                <small class="text-muted">{{ $s->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <strong>{{ $s->nisn }}</strong>
                        <br><small class="text-muted">NIS: {{ $s->nis ?? '-' }}</small>
                    </td>
                    <td>
                        <span class="badge bg-label-info">{{ $s->kelas }}</span>
                        @if($s->jurusan)
                            <br><small class="text-muted">{{ $s->jurusan }}</small>
                        @endif
                    </td>
                    <td>
                        @if($s->status_siswa == 'aktif')
                            <span class="badge bg-label-success">Aktif</span>
                        @elseif($s->status_siswa == 'lulus')
                            <span class="badge bg-label-primary">Lulus</span>
                        @elseif($s->status_siswa == 'mutasi')
                            <span class="badge bg-label-warning">Mutasi</span>
                        @else
                            <span class="badge bg-label-danger">Keluar</span>
                        @endif
                    </td>
                    <td>
                        <code class="text-dark">{{ $s->tgl_lahir ? \Carbon\Carbon::parse($s->tgl_lahir)->format('dmY') : '-' }}</code>
                    </td>
                    <td>
                        <div class="d-flex gap-1 align-items-center">
                            {{-- Tombol Modal Detail Siswa --}}
                            <button type="button" class="btn btn-icon btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#modalDetail{{ $s->id }}" title="Lihat Data Lengkap">
                                <i class="bx bx-show"></i>
                            </button>

                            {{-- Tombol Modal QR Code --}}
                            <button type="button" class="btn btn-icon btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalQR{{ $s->id }}" title="Lihat QR Code">
                                <i class="bx bx-qr-scan"></i>
                            </button>

                            {{-- Tombol Edit --}}
                            <a href="{{ route('admin.siswa.edit', $s->id) }}" class="btn btn-icon btn-sm btn-outline-warning" title="Edit Data">
                                <i class="bx bx-edit-alt"></i>
                            </a>

                            {{-- Tombol Hapus --}}
                            <form action="{{ route('admin.siswa.destroy', $s->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data siswa ini beserta akun loginnya?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-icon btn-sm btn-outline-danger" title="Hapus">
                                    <i class="bx bx-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>

                <!-- MODAL DETAIL DATA LENGKAP SISWA -->
                <div class="modal fade" id="modalDetail{{ $s->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header border-bottom">
                                <h5 class="modal-title fw-bold"><i class="bx bx-user me-2 text-primary"></i>Detail Lengkap Siswa</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row g-3">
                                    <!-- Foto & Identitas Ringkas -->
                                    <div class="col-md-3 text-center border-end pe-3">
                                        @if($s->foto)
                                            <img src="{{ asset('storage/' . $s->foto) }}" alt="Foto Siswa" class="img-fluid rounded mb-2" style="max-height: 180px; object-fit: cover;">
                                        @else
                                            <div class="avatar avatar-xl mx-auto mb-2" style="width: 100px; height: 100px;">
                                                <span class="avatar-initial rounded bg-label-primary fs-1">
                                                    {{ strtoupper(substr($s->nama_lengkap, 0, 2)) }}
                                                </span>
                                            </div>
                                        @endif
                                        <h6 class="fw-bold mb-1">{{ $s->nama_lengkap }}</h6>
                                        <span class="badge bg-label-success mb-2">{{ ucfirst($s->status_siswa) }}</span>
                                        <p class="text-muted small mb-0">Username: {{ $s->nisn }}</p>
                                    </div>

                                    <!-- Detail Informasi -->
                                    <div class="col-md-9 ps-4">
                                        <div class="row g-2">
                                            <div class="col-6 mb-2">
                                                <small class="text-muted d-block">NISN / NIS</small>
                                                <span class="fw-semibold text-dark">{{ $s->nisn }} / {{ $s->nis ?? '-' }}</span>
                                            </div>
                                            <div class="col-6 mb-2">
                                                <small class="text-muted d-block">NIK (KTP/KK)</small>
                                                <span class="fw-semibold text-dark">{{ $s->nik ?? '-' }}</span>
                                            </div>

                                            <div class="col-6 mb-2">
                                                <small class="text-muted d-block">Kelas & Jurusan</small>
                                                <span class="fw-semibold text-dark">{{ $s->kelas }} {{ $s->jurusan ? '('.$s->jurusan.')' : '' }}</span>
                                            </div>
                                            <div class="col-6 mb-2">
                                                <small class="text-muted d-block">Jenis Kelamin</small>
                                                <span class="fw-semibold text-dark">{{ $s->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                                            </div>

                                            <div class="col-6 mb-2">
                                                <small class="text-muted d-block">Tempat, Tanggal Lahir</small>
                                                <span class="fw-semibold text-dark">{{ $s->tempat_lahir ?? '-' }}, {{ $s->tgl_lahir ? \Carbon\Carbon::parse($s->tgl_lahir)->translatedFormat('d F Y') : '-' }}</span>
                                            </div>
                                            <div class="col-6 mb-2">
                                                <small class="text-muted d-block">Agama</small>
                                                <span class="fw-semibold text-dark">{{ $s->agama ?? '-' }}</span>
                                            </div>

                                            <div class="col-6 mb-2">
                                                <small class="text-muted d-block">No. HP / WhatsApp Siswa</small>
                                                <span class="fw-semibold text-dark">{{ $s->no_hp ?? '-' }}</span>
                                            </div>
                                            <div class="col-6 mb-2">
                                                <small class="text-muted d-block">Email</small>
                                                <span class="fw-semibold text-dark">{{ $s->user->email ?? '-' }}</span>
                                            </div>

                                            <hr class="my-2">

                                            <div class="col-6 mb-2">
                                                <small class="text-muted d-block">Nama Orang Tua / Wali</small>
                                                <span class="fw-semibold text-dark">{{ $s->nama_ortu ?? '-' }}</span>
                                            </div>
                                            <div class="col-6 mb-2">
                                                <small class="text-muted d-block">No. HP Orang Tua</small>
                                                <span class="fw-semibold text-dark">{{ $s->no_hp_ortu ?? '-' }}</span>
                                            </div>

                                            <div class="col-12 mb-2">
                                                <small class="text-muted d-block">Alamat Domisili</small>
                                                <span class="fw-semibold text-dark">{{ $s->alamat ?? '-' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer border-top">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                <a href="{{ route('admin.siswa.edit', $s->id) }}" class="btn btn-warning">
                                    <i class="bx bx-edit me-1"></i> Edit Data
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- MODAL POPUP QR CODE SISWA -->
                <div class="modal fade" id="modalQR{{ $s->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
                        <div class="modal-content text-center p-3">
                            <div class="modal-header border-0 pb-0 justify-content-center">
                                <h5 class="modal-title fw-bold">Kartu Pelajar Digital</h5>
                            </div>
                            <div class="modal-body">
                                <h6 class="mb-1 fw-semibold">{{ $s->nama_lengkap }}</h6>
                                <p class="text-muted small mb-3">NISN: {{ $s->nisn }} | Kelas: {{ $s->kelas }}</p>
                                
                                <div class="p-3 bg-light rounded d-inline-block border">
                                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data={{ $s->nisn }}" alt="QR Code {{ $s->nama_lengkap }}" class="img-fluid" />
                                </div>
                                
                                <p class="text-muted small mt-3 mb-0">Scan QR Code ini pada kamera scanner untuk absensi harian.</p>
                            </div>
                            <div class="modal-footer border-0 pt-0 justify-content-center">
                                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                <button type="button" class="btn btn-sm btn-primary" onclick="window.print()">
                                    <i class="bx bx-printer me-1"></i> Cetak
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-4 text-muted">
                        @if(request('search') || request('kelas'))
                            Data siswa tidak ditemukan dengan kata kunci/filter tersebut.
                        @else
                            Belum ada data siswa terdaftar.
                        @endif
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination Links (20 Data Per Halaman) -->
    <div class="card-footer d-flex justify-content-between align-items-center">
        <small class="text-muted">
            Menampilkan {{ $siswas->firstItem() ?? 0 }} sampai {{ $siswas->lastItem() ?? 0 }} dari {{ $siswas->total() }} siswa
        </small>
        <div>
            {{ $siswas->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection