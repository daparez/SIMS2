@extends('layouts.app')

@section('title', 'Master Ruangan & Fasilitas - SIMAK')

@push('page-styles')
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
<style>
    .table-hover tbody tr:hover {
        background-color: rgba(105, 108, 255, 0.03) !important;
    }
    .select2-container {
        width: 100% !important;
    }
</style>
@endpush

@section('content')
<!-- HEADER UTAMA & ACTION BUTTONS -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
    <div>
        <h4 class="fw-bold py-1 mb-0"><span class="text-muted fw-light">Sarpras /</span> Master Ruangan</h4>
        <small class="text-muted">Kelola sarana prasarana fisik, fasilitas ruangan, serta penanggung jawab.</small>
    </div>
    <div>
        <button type="button" class="btn btn-primary fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahRuangan">
            <i class="bx bx-plus me-1"></i> Tambah Ruangan Baru
        </button>
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

<!-- TABEL MASTER RUANGAN -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom py-3">
        <div class="row align-items-center g-3">
            <div class="col-md-6">
                <h5 class="fw-bold mb-0 text-dark"><i class="bx bx-building-house me-2 text-primary"></i>Daftar Ruangan & Fasilitas</h5>
            </div>
            <!-- INPUT LIVE SEARCH -->
            <div class="col-md-6">
                <div class="input-group input-group-merge">
                    <span class="input-group-text bg-light border-end-0"><i class="bx bx-search fs-5 text-muted"></i></span>
                    <input type="text" id="searchInputRuangan" class="form-control bg-light border-start-0 ps-0" placeholder="Cari kode, nama ruangan, atau penanggung jawab..." />
                </div>
            </div>
        </div>
    </div>

    <div class="table-responsive text-nowrap">
        <table class="table table-hover align-middle mb-0" id="tableRuangan">
            <thead class="table-light text-uppercase small">
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 15%;">Kode Ruang</th>
                    <th style="width: 25%;">Nama Ruangan</th>
                    <th style="width: 15%;">Kategori</th>
                    <th style="width: 12%;">Kapasitas / Luas</th>
                    <th style="width: 18%;">Penanggung Jawab</th>
                    <th style="width: 10%;">Kondisi</th>
                    <th style="width: 8%;" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                @forelse($ruangans as $index => $r)
                <tr class="ruangan-row">
                    <td class="fw-bold text-center">{{ $index + 1 }}</td>
                    <td>
                        <span class="badge bg-label-dark fw-bold search-target-kode">{{ $r->kode_ruangan }}</span>
                    </td>
                    <td>
                        <h6 class="mb-0 fw-bold text-dark search-target-nama">{{ $r->nama_ruangan }}</h6>
                        <small class="text-muted">{{ Str::limit($r->keterangan, 30) ?? 'Tidak ada catatan' }}</small>
                    </td>
                    <td>
                        <span class="badge bg-label-primary text-uppercase">{{ $r->kategori_ruangan }}</span>
                    </td>
                    <td>
                        <span class="fw-semibold text-dark"><i class="bx bx-group me-1"></i>{{ $r->kapasitas }} Orang</span>
                        <br><small class="text-muted"><i class="bx bx-area me-1"></i>{{ $r->luas_ruangan ?? '-' }} m²</small>
                    </td>
                    <td>
                        <span class="fw-semibold text-dark search-target-pj">{{ $r->penanggungJawab->nama_lengkap ?? '-' }}</span>
                        @if($r->penanggungJawab)
                            <br><small class="text-muted">NIP: {{ $r->penanggungJawab->nip ?? '-' }}</small>
                        @endif
                    </td>
                    <td>
                        @if($r->status_kondisi == 'baik')
                            <span class="badge bg-label-success">Baik</span>
                        @elseif($r->status_kondisi == 'rusak_ringan')
                            <span class="badge bg-label-warning">Rusak Ringan</span>
                        @elseif($r->status_kondisi == 'rusak_berat')
                            <span class="badge bg-label-danger">Rusak Berat</span>
                        @else
                            <span class="badge bg-label-info">Dalam Perbaikan</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-1">
                            <!-- DETAIL MAPPING INVENTARIS BARANG -->
                            <button type="button" class="btn btn-icon btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#modalMapping{{ $r->id }}" title="Lihat Inventaris Ruangan Ini">
                                <i class="bx bx-box"></i>
                            </button>

                            <!-- TOMBOL EDIT -->
                            <button type="button" class="btn btn-icon btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#modalEditRuangan{{ $r->id }}" title="Edit Ruangan">
                                <i class="bx bx-edit-alt"></i>
                            </button>

                            <!-- TOMBOL HAPUS -->
                            <form action="{{ route('admin.ruangan.destroy', $r->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus ruangan ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-icon btn-sm btn-outline-danger" title="Hapus Ruangan">
                                    <i class="bx bx-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-5 text-muted">
                        <i class="bx bx-building-house fs-1 mb-2 d-block"></i>
                        Belum ada data ruangan terdaftar.
                    </td>
                </tr>
                @endforelse

                <tr id="noSearchResultRow" style="display: none;">
                    <td colspan="8" class="text-center py-5 text-muted">
                        <i class="bx bx-search-alt fs-1 mb-2 d-block"></i>
                        Ruangan yang Anda cari tidak ditemukan.
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- MODAL TAMBAH RUANGAN BARU -->
<div class="modal fade" id="modalTambahRuangan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom bg-light py-3">
                <h5 class="modal-title fw-bold text-dark"><i class="bx bx-plus-circle text-primary me-2"></i>Tambah Ruangan Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.ruangan.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Kode Ruangan</label>
                            <input type="text" name="kode_ruangan" class="form-control" placeholder="Contoh: R-LAB-01" required />
                        </div>
                        <div class="col-md-8">
                            <label class="form-label small fw-bold">Nama Ruangan</label>
                            <input type="text" name="nama_ruangan" class="form-control" placeholder="Contoh: Laboratorium Komputer 1" required />
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Kategori Ruangan</label>
                            <select name="kategori_ruangan" class="form-select" required>
                                <option value="kelas">Ruang Kelas</option>
                                <option value="laboratorium">Laboratorium</option>
                                <option value="perpustakaan">Perpustakaan</option>
                                <option value="kantor">Kantor / Ruang Guru</option>
                                <option value="aula">Aula Utama</option>
                                <option value="lapangan">Lapangan</option>
                                <option value="lainnya">Lainnya</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold">Kapasitas (Orang)</label>
                            <input type="number" name="kapasitas" class="form-control" value="36" min="0" required />
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold">Luas Ruangan (m²)</label>
                            <input type="number" name="luas_ruangan" class="form-control" placeholder="Contoh: 72" />
                        </div>
                        <div class="col-md-7">
                            <label class="form-label small fw-bold">Penanggung Jawab (Guru)</label>
                            <select name="penanggung_jawab_id" class="form-select select2-modal">
                                <option value="">-- Tanpa Penanggung Jawab --</option>
                                @foreach($gurus as $g)
                                    <option value="{{ $g->id }}">{{ $g->nama_lengkap }} ({{ $g->nip ?? 'NIP -' }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label small fw-bold">Kondisi Ruangan</label>
                            <select name="status_kondisi" class="form-select" required>
                                <option value="baik">Baik / Layak Pakai</option>
                                <option value="rusak_ringan">Rusak Ringan</option>
                                <option value="rusak_berat">Rusak Berat</option>
                                <option value="dalam_perbaikan">Dalam Perbaikan</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label small fw-bold">Catatan / Keterangan Fasilitas</label>
                            <textarea name="keterangan" class="form-control" rows="2" placeholder="Contoh: Dilengkapi 36 Unit PC, AC 2 PK, Projector Epson"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top bg-light py-2">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary fw-bold">Simpan Ruangan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- CONTAINER MODAL EDIT & MAPPING INVENTARIS PER RUANGAN -->
@foreach($ruangans as $r)
<!-- MODAL EDIT RUANGAN -->
<div class="modal fade" id="modalEditRuangan{{ $r->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom bg-light py-3">
                <h5 class="modal-title fw-bold text-dark"><i class="bx bx-edit text-warning me-2"></i>Edit Data Ruangan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.ruangan.update', $r->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Kode Ruangan</label>
                            <input type="text" name="kode_ruangan" class="form-control" value="{{ $r->kode_ruangan }}" required />
                        </div>
                        <div class="col-md-8">
                            <label class="form-label small fw-bold">Nama Ruangan</label>
                            <input type="text" name="nama_ruangan" class="form-control" value="{{ $r->nama_ruangan }}" required />
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Kategori Ruangan</label>
                            <select name="kategori_ruangan" class="form-select" required>
                                <option value="kelas" {{ $r->kategori_ruangan == 'kelas' ? 'selected' : '' }}>Ruang Kelas</option>
                                <option value="laboratorium" {{ $r->kategori_ruangan == 'laboratorium' ? 'selected' : '' }}>Laboratorium</option>
                                <option value="perpustakaan" {{ $r->kategori_ruangan == 'perpustakaan' ? 'selected' : '' }}>Perpustakaan</option>
                                <option value="kantor" {{ $r->kategori_ruangan == 'kantor' ? 'selected' : '' }}>Kantor / Ruang Guru</option>
                                <option value="aula" {{ $r->kategori_ruangan == 'aula' ? 'selected' : '' }}>Aula Utama</option>
                                <option value="lapangan" {{ $r->kategori_ruangan == 'lapangan' ? 'selected' : '' }}>Lapangan</option>
                                <option value="lainnya" {{ $r->kategori_ruangan == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold">Kapasitas (Orang)</label>
                            <input type="number" name="kapasitas" class="form-control" value="{{ $r->kapasitas }}" min="0" required />
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold">Luas Ruangan (m²)</label>
                            <input type="number" name="luas_ruangan" class="form-control" value="{{ $r->luas_ruangan }}" />
                        </div>
                        <div class="col-md-7">
                            <label class="form-label small fw-bold">Penanggung Jawab (Guru)</label>
                            <select name="penanggung_jawab_id" class="form-select select2-modal">
                                <option value="">-- Tanpa Penanggung Jawab --</option>
                                @foreach($gurus as $g)
                                    <option value="{{ $g->id }}" {{ $r->penanggung_jawab_id == $g->id ? 'selected' : '' }}>{{ $g->nama_lengkap }} ({{ $g->nip ?? 'NIP -' }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label small fw-bold">Kondisi Ruangan</label>
                            <select name="status_kondisi" class="form-select" required>
                                <option value="baik" {{ $r->status_kondisi == 'baik' ? 'selected' : '' }}>Baik / Layak Pakai</option>
                                <option value="rusak_ringan" {{ $r->status_kondisi == 'rusak_ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                                <option value="rusak_berat" {{ $r->status_kondisi == 'rusak_berat' ? 'selected' : '' }}>Rusak Berat</option>
                                <option value="dalam_perbaikan" {{ $r->status_kondisi == 'dalam_perbaikan' ? 'selected' : '' }}>Dalam Perbaikan</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label small fw-bold">Catatan / Keterangan Fasilitas</label>
                            <textarea name="keterangan" class="form-control" rows="2">{{ $r->keterangan }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top bg-light py-2">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary fw-bold">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL MAPPING INVENTARIS BARANG DI RUANGAN INI -->
<div class="modal fade" id="modalMapping{{ $r->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-bottom bg-light py-3">
                <h5 class="modal-title fw-bold text-dark">
                    <i class="bx bx-box text-info me-2 fs-4"></i> Inventaris Barang di: <span class="text-primary">{{ $r->nama_ruangan }}</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="table-responsive">
                    <table class="table table-bordered table-sm align-middle mb-0">
                        <thead class="table-light text-uppercase small">
                            <tr>
                                <th style="width: 5%;">No</th>
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th class="text-center">Jumlah Unit</th>
                                <th class="text-center">Kondisi (Baik / RR / RB)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($r->barangs as $idx => $b)
                            <tr>
                                <td class="text-center">{{ $idx + 1 }}</td>
                                <td class="fw-bold">{{ $b->kode_barang }}</td>
                                <td>{{ $b->nama_barang }}</td>
                                <td class="text-center fw-bold">{{ $b->jumlah }}</td>
                                <td class="text-center small">
                                    <span class="badge bg-label-success">{{ $b->kondisi_baik }}</span> /
                                    <span class="badge bg-label-warning">{{ $b->kondisi_rusak_ringan }}</span> /
                                    <span class="badge bg-label-danger">{{ $b->kondisi_rusak_berat }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted small">
                                    Belum ada aset/barang yang ditempatkan di ruangan ini.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer border-top bg-light py-2">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endforeach

@endsection

@push('page-scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Inisialisasi Live Search Ruangan
        const searchInput = document.getElementById('searchInputRuangan');
        const rows = document.querySelectorAll('.ruangan-row');
        const noResultRow = document.getElementById('noSearchResultRow');

        searchInput.addEventListener('input', function () {
            const query = this.value.toLowerCase().trim();
            let matchesCount = 0;

            rows.forEach(row => {
                const kode = row.querySelector('.search-target-kode')?.textContent.toLowerCase() || '';
                const nama = row.querySelector('.search-target-nama')?.textContent.toLowerCase() || '';
                const pj   = row.querySelector('.search-target-pj')?.textContent.toLowerCase() || '';

                if (kode.includes(query) || nama.includes(query) || pj.includes(query)) {
                    row.style.display = '';
                    matchesCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            noResultRow.style.display = (matchesCount === 0 && rows.length > 0) ? '' : 'none';
        });

        // Inisialisasi Select2 di dalam Modal
        $('.modal').on('shown.bs.modal', function () {
            $(this).find('.select2-modal').select2({
                theme: 'bootstrap-5',
                dropdownParent: $(this),
                width: '100%'
            });
        });
    });
</script>
@endpush