@extends('layouts.app')

@section('title', 'Data Guru & Tenaga Pendidik - SIMAK')

@push('page-styles')
<style>
    .avatar-initial-custom {
        width: 42px;
        height: 42px;
        font-weight: 700;
        font-size: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .table-hover tbody tr:hover {
        background-color: rgba(105, 108, 255, 0.03) !important;
    }
</style>
@endpush

@section('content')
<!-- HEADER UTAMA & ACTION BUTTONS -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
    <div>
        <h4 class="fw-bold py-1 mb-0"><span class="text-muted fw-light">Admin /</span> Data Guru</h4>
        <small class="text-muted">Kelola data kepegawaian, kredensial akun, serta penugasan tenaga pendidik.</small>
    </div>
    <div class="d-flex flex-wrap gap-2">
        <a href="{{ route('admin.guru.cetak') }}" target="_blank" class="btn btn-outline-secondary fw-bold shadow-sm">
            <i class="bx bx-printer me-1"></i> Cetak Rekap PDF
        </a>
        <a href="{{ route('admin.guru.create') }}" class="btn btn-primary fw-bold shadow-sm">
            <i class="bx bx-plus me-1"></i> Tambah Guru Baru
        </a>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
    <div class="d-flex align-items-center">
        <i class="bx bx-check-circle fs-4 me-2"></i>
        <div>{{ session('success') }}</div>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<!-- TABEL UTAMA DENGAN LIVE SEARCH -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom py-3">
        <div class="row align-items-center g-3">
            <div class="col-md-6">
                <h5 class="fw-bold mb-0 text-dark"><i class="bx bx-list-ul me-2 text-primary"></i>Daftar Tenaga Pendidik</h5>
            </div>
            <!-- INPUT LIVE SEARCH -->
            <div class="col-md-6">
                <div class="input-group input-group-merge">
                    <span class="input-group-text bg-light border-end-0"><i class="bx bx-search fs-5 text-muted"></i></span>
                    <input type="text" id="searchInputGuru" class="form-control bg-light border-start-0 ps-0" placeholder="Cari nama, NIP, NIK, atau kode guru..." />
                </div>
            </div>
        </div>
    </div>

    <div class="table-responsive text-nowrap">
        <table class="table table-hover align-middle mb-0" id="tableDataGuru">
            <thead class="table-light text-uppercase small">
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 28%;">Guru</th>
                    <th style="width: 12%;">Kode Guru</th>
                    <th style="width: 20%;">NIP / NIK</th>
                    <th style="width: 18%;">Status Kepegawaian</th>
                    <th style="width: 10%;">Wali Kelas</th>
                    <th style="width: 7%;" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                @forelse($gurus as $index => $g)
                <tr class="guru-row">
                    <td class="fw-bold text-center">{{ $index + 1 }}</td>
                    <td>
                        <div class="d-flex align-items-center">
                            @if($g->foto)
                                <img src="{{ asset('storage/' . $g->foto) }}" alt="Foto Guru" class="rounded-circle me-3 shadow-sm" width="42" height="42" style="object-fit: cover;">
                            @else
                                <div class="avatar avatar-initial-custom rounded-circle bg-label-primary me-3 text-uppercase">
                                    {{ strtoupper(substr($g->nama_lengkap, 0, 2)) }}
                                </div>
                            @endif
                            <div>
                                <h6 class="mb-0 fw-bold text-dark search-target-nama">{{ $g->nama_lengkap }}</h6>
                                <small class="text-muted"><i class="bx bx-phone me-1"></i>{{ $g->no_hp ?? '-' }}</small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="badge bg-label-dark fw-bold px-2 py-1 search-target-kode">({{ $g->kode_guru ?? $g->id }})</span>
                    </td>
                    <td>
                        <span class="fw-semibold text-dark search-target-nip">{{ $g->nip ?? '-' }}</span>
                        <br><small class="text-muted search-target-nik">NIK: {{ $g->nik ?? '-' }}</small>
                    </td>
                    <td>
                        <span class="badge bg-label-info mb-1">{{ $g->jenis_ptk ?? 'Guru' }}</span>
                        <br><small class="text-dark fw-semibold">{{ $g->status_kepegawaian ?? '-' }}</small>
                    </td>
                    <td>
                        @if($g->waliKelas)
                            <span class="badge bg-label-primary fw-bold">{{ $g->waliKelas->nama_kelas }}</span>
                        @else
                            <span class="text-muted small">-</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-1">
                            <!-- TOMBOL DETAIL -->
                            <button type="button" class="btn btn-icon btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#modalDetailGuru{{ $g->id }}" title="Lihat Detail Selengkapnya">
                                <i class="bx bx-show"></i>
                            </button>

                            <!-- TOMBOL EDIT -->
                            <a href="{{ route('admin.guru.edit', $g->id) }}" class="btn btn-icon btn-sm btn-outline-warning" title="Edit Data">
                                <i class="bx bx-edit-alt"></i>
                            </a>

                            <!-- TOMBOL HAPUS -->
                            <form action="{{ route('admin.guru.destroy', $g->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus guru ini beserta akunnya?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-icon btn-sm btn-outline-danger" title="Hapus Data">
                                    <i class="bx bx-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5 text-muted">
                        <i class="bx bx-folder-open fs-1 mb-2 d-block"></i>
                        Belum ada data guru terdaftar dalam sistem.
                    </td>
                </tr>
                @endforelse
                
                <tr id="noSearchResultRow" style="display: none;">
                    <td colspan="7" class="text-center py-5 text-muted">
                        <i class="bx bx-search-alt fs-1 mb-2 d-block"></i>
                        Data guru yang Anda cari tidak ditemukan.
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- CONTAINER MODAL DETAIL GURU (DI LUAR TABEL) -->
@foreach($gurus as $g)
<div class="modal fade text-start" id="modalDetailGuru{{ $g->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-bottom bg-light py-3">
                <h5 class="modal-title fw-bold text-dark mb-0">
                    <i class="bx bx-id-card text-primary me-2 fs-4"></i> Detail Informasi Profil Guru
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body p-4">
                <!-- HEADER PROFIL GURU -->
                <div class="row align-items-center mb-4 pb-3 border-bottom">
                    <div class="col-md-3 text-center mb-3 mb-md-0">
                        @if($g->foto)
                            <img src="{{ asset('storage/' . $g->foto) }}" alt="Foto Guru" class="rounded-circle img-thumbnail shadow-sm" width="110" height="110" style="object-fit: cover; aspect-ratio: 1/1;">
                        @else
                            <div class="avatar avatar-xl mx-auto" style="width: 110px; height: 110px;">
                                <span class="avatar-initial rounded-circle bg-label-primary fs-1 fw-bold d-flex align-items-center justify-content-center" style="width: 110px; height: 110px;">
                                    {{ strtoupper(substr($g->nama_lengkap, 0, 2)) }}
                                </span>
                            </div>
                        @endif
                    </div>
                    <div class="col-md-9 text-center text-md-start">
                        <h4 class="fw-bold mb-1 text-primary">{{ $g->nama_lengkap }}</h4>
                        <div class="d-flex flex-wrap justify-content-center justify-content-md-start gap-2 mb-2">
                            <span class="badge bg-primary">Kode Guru: {{ $g->kode_guru ?? $g->id }}</span>
                            <span class="badge bg-info">{{ $g->jenis_ptk ?? 'Guru' }}</span>
                            <span class="badge bg-success">{{ $g->status_kepegawaian ?? '-' }}</span>
                            @if($g->is_sertifikasi)
                                <span class="badge bg-warning text-dark"><i class="bx bx-award me-1"></i>Tersertifikasi</span>
                            @endif
                        </div>
                        <p class="text-muted mb-0 small">
                            <i class="bx bx-envelope me-1"></i> Email Sesi: <strong>{{ $g->user->email ?? '-' }}</strong> | 
                            <i class="bx bx-phone me-1"></i> <strong>{{ $g->no_hp ?? '-' }}</strong>
                        </p>
                    </div>
                </div>

                <!-- SEKSI DETAIL TERBAGI 3 KARTU MODAL -->
                <div class="row g-3">
                    <!-- 1. DATA KEPEGAWAIAN -->
                    <div class="col-md-6">
                        <div class="card bg-light border-0 h-100 p-3 shadow-none">
                            <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">
                                <i class="bx bx-briefcase me-1 text-primary"></i> Data Kepegawaian
                            </h6>
                            <table class="table table-sm table-borderless mb-0 text-wrap small">
                                <tr>
                                    <th style="width: 45%;" class="text-muted">NIP</th>
                                    <td class="fw-semibold">: {{ $g->nip ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">NIK</th>
                                    <td class="fw-semibold">: {{ $g->nik ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">NUPTK</th>
                                    <td class="fw-semibold">: {{ $g->nuptk ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Pangkat / Gol.</th>
                                    <td class="fw-semibold">: {{ $g->pangkat_golongan ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Tgl Masuk</th>
                                    <td class="fw-semibold">: {{ $g->tgl_masuk ? \Carbon\Carbon::parse($g->tgl_masuk)->format('d F Y') : '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Wali Kelas</th>
                                    <td class="fw-semibold">: {{ $g->waliKelas->nama_kelas ?? 'Bukan Wali Kelas' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- 2. BIODATA PRIBADI -->
                    <div class="col-md-6">
                        <div class="card bg-light border-0 h-100 p-3 shadow-none">
                            <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">
                                <i class="bx bx-user me-1 text-primary"></i> Biodata Pribadi
                            </h6>
                            <table class="table table-sm table-borderless mb-0 text-wrap small">
                                <tr>
                                    <th style="width: 45%;" class="text-muted">Jenis Kelamin</th>
                                    <td class="fw-semibold">: {{ ($g->jenis_kelamin ?? '') == 'L' ? 'Laki-Laki' : 'Perempuan' }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Tempat, Tgl Lahir</th>
                                    <td class="fw-semibold">: {{ $g->tempat_lahir ?? '-' }}, {{ $g->tgl_lahir ? \Carbon\Carbon::parse($g->tgl_lahir)->format('d F Y') : '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Agama</th>
                                    <td class="fw-semibold">: {{ $g->agama ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">No. HP / WA</th>
                                    <td class="fw-semibold">: {{ $g->no_hp ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Alamat Rumah</th>
                                    <td class="fw-semibold">: {{ $g->alamat ?? '-' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- 3. RIWAYAT PENDIDIKAN -->
                    <div class="col-md-12">
                        <div class="card bg-light border-0 p-3 shadow-none">
                            <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">
                                <i class="bx bx-book-bookmark me-1 text-primary"></i> Riwayat Pendidikan
                            </h6>
                            <table class="table table-sm table-borderless mb-0 text-wrap small">
                                <tr>
                                    <th style="width: 22%;" class="text-muted">Pendidikan Terakhir</th>
                                    <td class="fw-semibold">: {{ $g->pendidikan_terakhir ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Jurusan / Prodi</th>
                                    <td class="fw-semibold">: {{ $g->jurusan_pendidikan ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Nama Perguruan Tinggi</th>
                                    <td class="fw-semibold">: {{ $g->nama_kampus ?? '-' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer border-top bg-light py-2">
                <a href="{{ route('admin.guru.edit', $g->id) }}" class="btn btn-sm btn-warning">
                    <i class="bx bx-edit-alt me-1"></i> Edit Data Guru Ini
                </a>
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endforeach

@endsection

@push('page-scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('searchInputGuru');
        const rows = document.querySelectorAll('.guru-row');
        const noResultRow = document.getElementById('noSearchResultRow');

        searchInput.addEventListener('input', function () {
            const query = this.value.toLowerCase().trim();
            let matchesCount = 0;

            rows.forEach(row => {
                const nama = row.querySelector('.search-target-nama')?.textContent.toLowerCase() || '';
                const kode = row.querySelector('.search-target-kode')?.textContent.toLowerCase() || '';
                const nip  = row.querySelector('.search-target-nip')?.textContent.toLowerCase() || '';
                const nik  = row.querySelector('.search-target-nik')?.textContent.toLowerCase() || '';

                if (nama.includes(query) || kode.includes(query) || nip.includes(query) || nik.includes(query)) {
                    row.style.display = '';
                    matchesCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            if (matchesCount === 0 && rows.length > 0) {
                noResultRow.style.display = '';
            } else {
                noResultRow.style.display = 'none';
            }
        });
    });
</script>
@endpush