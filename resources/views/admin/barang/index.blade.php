@extends('layouts.app')

@section('title', 'Master Barang & Inventaris - SIMAK')

@push('page-styles')
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
        <h4 class="fw-bold py-1 mb-0"><span class="text-muted fw-light">Sarpras /</span> Master Data Barang & Aset</h4>
        <small class="text-muted">Kelola siklus pengadaan barang, sumber dana, alokasi ruangan, dan kelayakan unit.</small>
    </div>
    <div class="d-flex flex-wrap gap-2">
        <button type="button" class="btn btn-outline-secondary fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#modalKategori">
            <i class="bx bx-category me-1"></i> Kelola Kategori
        </button>
        <button type="button" class="btn btn-primary fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahBarang">
            <i class="bx bx-plus me-1"></i> Tambah Barang Baru
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

<!-- TABEL UTAMA BARANG & ASET -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom py-3">
        <div class="row align-items-center g-3">
            <div class="col-md-6">
                <h5 class="fw-bold mb-0 text-dark"><i class="bx bx-box me-2 text-primary"></i>Daftar Aset & Perlengkapan Sekolah</h5>
            </div>
            <!-- INPUT LIVE SEARCH -->
            <div class="col-md-6">
                <div class="input-group input-group-merge">
                    <span class="input-group-text bg-light border-end-0"><i class="bx bx-search fs-5 text-muted"></i></span>
                    <input type="text" id="searchInputBarang" class="form-control bg-light border-start-0 ps-0" placeholder="Cari kode, nama barang, spesifikasi, atau ruangan..." />
                </div>
            </div>
        </div>
    </div>

    <div class="table-responsive text-nowrap">
        <table class="table table-hover align-middle mb-0" id="tableBarang">
            <thead class="table-light text-uppercase small">
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 15%;">Kode Barang</th>
                    <th style="width: 23%;">Nama & Spesifikasi</th>
                    <th style="width: 12%;">Kategori</th>
                    <th style="width: 15%;">Lokasi Ruangan</th>
                    <th style="width: 10%;">Sumber Dana</th>
                    <th style="width: 12%;" class="text-center">Kondisi (B/RR/RB)</th>
                    <th style="width: 8%;" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                @forelse($barangs as $index => $b)
                <tr class="barang-row">
                    <td class="fw-bold text-center">{{ $index + 1 }}</td>
                    <td>
                        <span class="badge bg-label-dark fw-bold search-target-kode">{{ $b->kode_barang }}</span>
                        <br><small class="text-muted">Thn: {{ $b->tahun_pengadaan ?? '-' }}</small>
                    </td>
                    <td>
                        <h6 class="mb-0 fw-bold text-dark search-target-nama">{{ $b->nama_barang }}</h6>
                        <small class="text-muted search-target-spek">{{ Str::limit($b->merk_spesifikasi, 35) ?? '-' }}</small>
                    </td>
                    <td>
                        <span class="badge bg-label-info">{{ $b->kategori->nama_kategori ?? '-' }}</span>
                    </td>
                    <td>
                        <span class="fw-semibold text-dark search-target-ruangan"><i class="bx bx-building-house me-1"></i>{{ $b->ruangan->nama_ruangan ?? 'Belum Dialokasikan' }}</span>
                    </td>
                    <td>
                        <span class="badge bg-label-success fw-bold">{{ $b->sumber_dana }}</span>
                        <br><small class="text-muted">Rp {{ number_format($b->harga_perolehan, 0, ',', '.') }}</small>
                    </td>
                    <td class="text-center">
                        <span class="fw-bold fs-6 text-dark d-block mb-1">Total: {{ $b->jumlah }}</span>
                        <span class="badge bg-label-success" title="Kondisi Baik">{{ $b->kondisi_baik }}</span> /
                        <span class="badge bg-label-warning" title="Rusak Ringan">{{ $b->kondisi_rusak_ringan }}</span> /
                        <span class="badge bg-label-danger" title="Rusak Berat">{{ $b->kondisi_rusak_berat }}</span>
                    </td>
                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-1">
                            <!-- TOMBOL EDIT -->
                            <button type="button" class="btn btn-icon btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#modalEditBarang{{ $b->id }}" title="Edit Barang">
                                <i class="bx bx-edit-alt"></i>
                            </button>

                            <!-- TOMBOL HAPUS -->
                            <form action="{{ route('admin.barang.destroy', $b->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus barang ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-icon btn-sm btn-outline-danger" title="Hapus Barang">
                                    <i class="bx bx-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-5 text-muted">
                        <i class="bx bx-box fs-1 mb-2 d-block"></i>
                        Belum ada data barang/aset terdaftar.
                    </td>
                </tr>
                @endforelse

                <tr id="noSearchResultRow" style="display: none;">
                    <td colspan="8" class="text-center py-5 text-muted">
                        <i class="bx bx-search-alt fs-1 mb-2 d-block"></i>
                        Barang yang Anda cari tidak ditemukan.
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- MODAL TAMBAH BARANG BARU (DENGAN AUTO GENERATE KODE) -->
<div class="modal fade" id="modalTambahBarang" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom bg-light py-3">
                <h5 class="modal-title fw-bold text-dark"><i class="bx bx-plus-circle text-primary me-2"></i>Tambah Data Barang / Aset</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.barang.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-5">
                            <label class="form-label small fw-bold">Kode / Barcode Barang (Otamatis)</label>
                            <div class="input-group">
                                <input type="text" id="inputKodeBarang" name="kode_barang" class="form-control fw-bold text-primary" value="{{ $autoKode ?? 'INV-'.date('Y').'-'.strtoupper(Str::random(4)) }}" required />
                                <button type="button" class="btn btn-outline-primary" id="btnGenerateKode" title="Acak Kode Baru">
                                    <i class="bx bx-refresh"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Nama Barang</label>
                            <input type="text" name="nama_barang" class="form-control" placeholder="Contoh: PC Desktop Lenovo" required />
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold">Kategori Barang</label>
                            <select name="kategori_barang_id" class="form-select select2-modal" required>
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($kategoris as $k)
                                    <option value="{{ $k->id }}">{{ $k->nama_kategori }} ({{ $k->kode_kategori }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Penempatan Ruangan</label>
                            <select name="ruangan_id" class="form-select select2-modal">
                                <option value="">-- Gudang / Belum Dialokasikan --</option>
                                @foreach($ruangans as $r)
                                    <option value="{{ $r->id }}">{{ $r->nama_ruangan }} ({{ $r->kode_ruangan }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold">Sumber Dana</label>
                            <select name="sumber_dana" class="form-select" required>
                                <option value="BOS">BOS</option>
                                <option value="Yayasan">Yayasan</option>
                                <option value="Hibah">Hibah</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold">Tahun Pengadaan</label>
                            <input type="number" name="tahun_pengadaan" class="form-control" value="{{ date('Y') }}" required />
                        </div>

                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Kondisi Baik (Unit)</label>
                            <input type="number" name="kondisi_baik" class="form-control" value="1" min="0" required />
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Kondisi Rusak Ringan (Unit)</label>
                            <input type="number" name="kondisi_rusak_ringan" class="form-control" value="0" min="0" required />
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Kondisi Rusak Berat (Unit)</label>
                            <input type="number" name="kondisi_rusak_berat" class="form-control" value="0" min="0" required />
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Harga Perolehan (Rp)</label>
                            <input type="number" name="harga_perolehan" class="form-control" placeholder="Contoh: 8500000" />
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Status Aset</label>
                            <select name="status_aset" class="form-select" required>
                                <option value="aktif">Aktif / Tersedia</option>
                                <option value="dipinjam">Sedang Dipinjam</option>
                                <option value="dalam_perbaikan">Dalam Perbaikan</option>
                                <option value="dihapuskan">Dihapuskan</option>
                            </select>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label small fw-bold">Merk & Spesifikasi Teknis</label>
                            <textarea name="merk_spesifikasi" class="form-control" rows="2" placeholder="Contoh: Intel Core i5 Gen 10, RAM 8GB, SSD 512GB, Monitor 21.5 Inch"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top bg-light py-2">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary fw-bold">Simpan Barang</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL QUICK ADD KATEGORI -->
<div class="modal fade" id="modalKategori" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom bg-light py-3">
                <h5 class="modal-title fw-bold text-dark"><i class="bx bx-category text-primary me-2"></i>Tambah Kategori Barang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.kategori.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Kode Kategori</label>
                        <input type="text" name="kode_kategori" class="form-control" placeholder="Contoh: ELK, MBL, LAB" required />
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Nama Kategori</label>
                        <input type="text" name="nama_kategori" class="form-control" placeholder="Contoh: Elektronik, Mebel, Alat Laboratorium" required />
                    </div>
                </div>
                <div class="modal-footer border-top bg-light py-2">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary fw-bold">Simpan Kategori</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL EDIT BARANG -->
@foreach($barangs as $b)
<div class="modal fade" id="modalEditBarang{{ $b->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom bg-light py-3">
                <h5 class="modal-title fw-bold text-dark"><i class="bx bx-edit text-warning me-2"></i>Edit Data Barang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.barang.update', $b->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Kode Barang</label>
                            <input type="text" name="kode_barang" class="form-control fw-bold" value="{{ $b->kode_barang }}" required />
                        </div>
                        <div class="col-md-5">
                            <label class="form-label small fw-bold">Nama Barang</label>
                            <input type="text" name="nama_barang" class="form-control" value="{{ $b->nama_barang }}" required />
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold">Kategori Barang</label>
                            <select name="kategori_barang_id" class="form-select select2-modal" required>
                                @foreach($kategoris as $k)
                                    <option value="{{ $k->id }}" {{ $b->kategori_barang_id == $k->id ? 'selected' : '' }}>{{ $k->nama_kategori }} ({{ $k->kode_kategori }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Penempatan Ruangan</label>
                            <select name="ruangan_id" class="form-select select2-modal">
                                <option value="">-- Gudang / Belum Dialokasikan --</option>
                                @foreach($ruangans as $r)
                                    <option value="{{ $r->id }}" {{ $b->ruangan_id == $r->id ? 'selected' : '' }}>{{ $r->nama_ruangan }} ({{ $r->kode_ruangan }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold">Sumber Dana</label>
                            <select name="sumber_dana" class="form-select" required>
                                <option value="BOS" {{ $b->sumber_dana == 'BOS' ? 'selected' : '' }}>BOS</option>
                                <option value="Yayasan" {{ $b->sumber_dana == 'Yayasan' ? 'selected' : '' }}>Yayasan</option>
                                <option value="Hibah" {{ $b->sumber_dana == 'Hibah' ? 'selected' : '' }}>Hibah</option>
                                <option value="Lainnya" {{ $b->sumber_dana == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold">Tahun Pengadaan</label>
                            <input type="number" name="tahun_pengadaan" class="form-control" value="{{ $b->tahun_pengadaan }}" required />
                        </div>

                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Kondisi Baik (Unit)</label>
                            <input type="number" name="kondisi_baik" class="form-control" value="{{ $b->kondisi_baik }}" min="0" required />
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Kondisi Rusak Ringan (Unit)</label>
                            <input type="number" name="kondisi_rusak_ringan" class="form-control" value="{{ $b->kondisi_rusak_ringan }}" min="0" required />
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Kondisi Rusak Berat (Unit)</label>
                            <input type="number" name="kondisi_rusak_berat" class="form-control" value="{{ $b->kondisi_rusak_berat }}" min="0" required />
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Harga Perolehan (Rp)</label>
                            <input type="number" name="harga_perolehan" class="form-control" value="{{ $b->harga_perolehan }}" />
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Status Aset</label>
                            <select name="status_aset" class="form-select" required>
                                <option value="aktif" {{ $b->status_aset == 'aktif' ? 'selected' : '' }}>Aktif / Tersedia</option>
                                <option value="dipinjam" {{ $b->status_aset == 'dipinjam' ? 'selected' : '' }}>Sedang Dipinjam</option>
                                <option value="dalam_perbaikan" {{ $b->status_aset == 'dalam_perbaikan' ? 'selected' : '' }}>Dalam Perbaikan</option>
                                <option value="dihapuskan" {{ $b->status_aset == 'dihapuskan' ? 'selected' : '' }}>Dihapuskan</option>
                            </select>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label small fw-bold">Merk & Spesifikasi Teknis</label>
                            <textarea name="merk_spesifikasi" class="form-control" rows="2">{{ $b->merk_spesifikasi }}</textarea>
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
@endforeach

@endsection

@push('page-scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Live Search Barang
        const searchInput = document.getElementById('searchInputBarang');
        const rows = document.querySelectorAll('.barang-row');
        const noResultRow = document.getElementById('noSearchResultRow');

        searchInput.addEventListener('input', function () {
            const query = this.value.toLowerCase().trim();
            let matchesCount = 0;

            rows.forEach(row => {
                const kode = row.querySelector('.search-target-kode')?.textContent.toLowerCase() || '';
                const nama = row.querySelector('.search-target-nama')?.textContent.toLowerCase() || '';
                const spek = row.querySelector('.search-target-spek')?.textContent.toLowerCase() || '';
                const ruang = row.querySelector('.search-target-ruangan')?.textContent.toLowerCase() || '';

                if (kode.includes(query) || nama.includes(query) || spek.includes(query) || ruang.includes(query)) {
                    row.style.display = '';
                    matchesCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            noResultRow.style.display = (matchesCount === 0 && rows.length > 0) ? '' : 'none';
        });

        // Select2 Inisialisasi di Modal
        $('.modal').on('shown.bs.modal', function () {
            $(this).find('.select2-modal').select2({
                theme: 'bootstrap-5',
                dropdownParent: $(this),
                width: '100%'
            });
        });

        // JavaScript Client-side instant generator untuk tombol Refresh Kode
        const btnGenerate = document.getElementById('btnGenerateKode');
        const inputKode = document.getElementById('inputKodeBarang');

        if(btnGenerate && inputKode) {
            btnGenerate.addEventListener('click', function() {
                const year = new Date().getFullYear();
                const randomStr = Math.random().toString(36).substring(2, 6).toUpperCase();
                inputKode.value = 'INV-' + year + '-' + randomStr;
            });
        }
    });
</script>
@endpush