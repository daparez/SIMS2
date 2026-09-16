@extends('layouts.app')

@section('title', 'Master Jadwal Pelajaran Keseluruhan - SIMAK')

@push('page-styles')
<!-- Select2 CSS untuk Live Search Dropdown -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
<style>
    .table-jadwal th, .table-jadwal td {
        font-size: 11px;
        padding: 4px 5px !important;
        vertical-align: middle;
        text-align: center;
        border: 1px solid #c4c4c4 !important;
    }
    .slot-pelajaran {
        background-color: #d1e7dd;
        font-weight: bold;
        color: #0f5132;
        cursor: pointer;
    }
    .slot-istirahat {
        background-color: #d1e7dd;
        font-weight: bold;
        color: #1b4332;
        letter-spacing: 2px;
    }
    .header-kelas {
        font-weight: bold;
        color: #000;
    }
    /* Memastikan dropdown Select2 tampil sempurna di dalam modal */
    .select2-container {
        width: 100% !important;
    }
</style>
@endpush

@section('content')

@php
    // Helper Angka Romawi
    function getRomawi($number) {
        $map = [1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V', 6 => 'VI', 7 => 'VII', 8 => 'VIII', 9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII'];
        return $map[$number] ?? $number;
    }
@endphp

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h4 class="fw-bold py-1 mb-0"><span class="text-muted fw-light">Admin /</span> Master Jadwal Pelajaran Keseluruhan</h4>
        <small class="text-muted">Tahun Ajaran: {{ $taAktif->nama_tahun_ajaran ?? '-' }} ({{ ucfirst($taAktif->semester ?? '') }})</small>
    </div>
    <div class="d-flex gap-2">
        <!-- TOMBOL KELOLA SLOT JAM -->
        <a href="{{ route('admin.jadwal.jam.index') }}" class="btn btn-primary fw-bold">
            <i class="bx bx-time me-1"></i> Kelola Slot Jam
        </a>

        <!-- TOMBOL CETAK PDF -->
        <a href="{{ route('admin.jadwal.cetak') }}" target="_blank" class="btn btn-success fw-bold">
            <i class="bx bx-printer me-1"></i> Cetak Jadwal (PDF)
        </a>

        <!-- TOMBOL RESET JADWAL PELAJARAN -->
        <button type="button" class="btn btn-outline-danger fw-bold" data-bs-toggle="modal" data-bs-target="#modalResetJadwal">
            <i class="bx bx-trash me-1"></i> Reset Jadwal
        </button>
    </div>
</div>

<!-- MODAL KONFIRMASI RESET JADWAL -->
<div class="modal fade" id="modalResetJadwal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-bottom bg-danger text-white">
                <h5 class="modal-title fw-bold text-white">
                    <i class="bx bx-error-circle me-1"></i> Konfirmasi Reset Seluruh Jadwal
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-4 text-center">
                <i class="bx bx-error-alt text-danger display-3 mb-2"></i>
                <h5 class="fw-bold text-dark mb-2">Apakah Anda yakin ingin mengosongkan seluruh jadwal?</h5>
                <p class="text-muted small mb-0">
                    Tindakan ini akan <strong>menghapus seluruh plot jadwal pelajaran</strong> pada Tahun Ajaran 
                    <span class="text-primary fw-bold">{{ $taAktif->nama_tahun_ajaran ?? '-' }} ({{ ucfirst($taAktif->semester ?? '') }})</span>. 
                    Data yang sudah dihapus tidak dapat dikembalikan.
                </p>
            </div>
            <div class="modal-footer border-top d-flex justify-content-between">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('admin.jadwal.reset') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger font-weight-bold">
                        <i class="bx bx-trash me-1"></i> Ya, Reset Semua Jadwal
                    </button>
                </form>
            </div>
        </div>
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
    <i class="bx bx-error-circle me-1"></i> {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<!-- TABEL MATRIKS JADWAL PRESISI PER HARINYA -->
<div class="card border-0 shadow-sm">
    <div class="table-responsive text-nowrap">
        <table class="table table-bordered table-jadwal mb-0">
            @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'] as $hari)
                @if(isset($jamPelajarans[$hari]))
                <thead class="table-light">
                    <!-- BARIS HARI -->
                    <tr>
                        <th colspan="{{ count($kelases) + 2 }}" class="bg-dark text-white fw-bold text-center py-2" style="letter-spacing: 3px;">
                            {{ strtoupper($hari) }}
                        </th>
                    </tr>
                    <!-- BARIS HEADER KELAS -->
                    <tr>
                        <th style="width: 45px;" class="bg-light text-dark fw-bold">JAM</th>
                        <th style="width: 100px;" class="bg-light text-dark fw-bold">WAKTU</th>
                        @foreach($kelases as $k)
                            <th style="min-width: 80px;" class="header-kelas bg-warning">
                                {{ $k->nama_kelas }}
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @php $romawiCount = 1; @endphp
                    @foreach($jamPelajarans[$hari] as $jam)
                    <tr>
                        @if($jam->is_istirahat)
                            <td class="fw-bold bg-light">-</td>
                            <td class="small bg-light fw-bold">
                                {{ \Carbon\Carbon::parse($jam->jam_mulai)->format('H.i') }} - {{ \Carbon\Carbon::parse($jam->jam_selesai)->format('H.i') }}
                            </td>
                            <!-- BARIS ISTIRAHAT MELEBAR KE KANAN -->
                            <td colspan="{{ count($kelases) }}" class="slot-istirahat text-center">
                                ISTIRAHAT
                            </td>
                        @else
                            <td class="fw-bold bg-light">{{ getRomawi($romawiCount++) }}</td>
                            <td class="small bg-light">
                                {{ \Carbon\Carbon::parse($jam->jam_mulai)->format('H.i') }} - {{ \Carbon\Carbon::parse($jam->jam_selesai)->format('H:i') }}
                            </td>

                            @foreach($kelases as $k)
                                @php
                                    $jadwal = $jadwals[$jam->id][$k->id] ?? null;
                                @endphp

                                @if($jadwal)
                                    <!-- TERISI: KODE_MAPEL(KODE_GURU) -->
                                    <td class="slot-pelajaran" data-bs-toggle="modal" data-bs-target="#modalEditSlot{{ $jam->id }}_{{ $k->id }}">
                                        @php
                                            $kodeMapel = $jadwal->pengampu->mapel->kode_mapel ?? strtoupper(substr($jadwal->pengampu->mapel->nama_mapel, 0, 4));
                                            $kodeGuru  = $jadwal->pengampu->guru->kode_guru ?? $jadwal->pengampu->guru->id;
                                        @endphp
                                        {{ strtoupper($kodeMapel) }}({{ $kodeGuru }})
                                    </td>
                                @else
                                    <!-- KOSONG: BISA DIISI -->
                                    <td>
                                        <button class="btn btn-xs btn-light text-muted p-0 w-100" data-bs-toggle="modal" data-bs-target="#modalEditSlot{{ $jam->id }}_{{ $k->id }}">
                                            +
                                        </button>
                                    </td>
                                @endif

                                <!-- MODAL FORM INPUT/EDIT SLOT JADWAL -->
                                <div class="modal fade text-start" id="modalEditSlot{{ $jam->id }}_{{ $k->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header border-bottom">
                                                <h6 class="modal-title fw-bold">
                                                    Atur Jadwal: {{ $hari }} (Jam {{ getRomawi($romawiCount - 1) }}) - Kelas {{ $k->nama_kelas }}
                                                </h6>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="{{ route('admin.jadwal.store') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="kelas_id" value="{{ $k->id }}">
                                                <input type="hidden" name="jam_pelajaran_id" value="{{ $jam->id }}">

                                                <div class="modal-body">
                                                    <!-- MATA PELAJARAN WITH SEARCH -->
                                                    <div class="mb-3 text-start">
                                                        <label class="form-label small fw-bold">Pilih Mata Pelajaran (Cari Mapel)</label>
                                                        <select name="mapel_id" class="form-select select2-search" data-placeholder="Cari atau pilih mata pelajaran..." required>
                                                            <option value=""></option>
                                                            @foreach($mapels as $mp)
                                                                <option value="{{ $mp->id }}" {{ ($jadwal && $jadwal->pengampu->mapel_id == $mp->id) ? 'selected' : '' }}>
                                                                    [{{ $mp->kode_mapel ?? strtoupper(substr($mp->nama_mapel, 0, 4)) }}] {{ $mp->nama_mapel }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <!-- GURU PENGAMPU WITH SEARCH -->
                                                    <div class="mb-3 text-start">
                                                        <label class="form-label small fw-bold">Pilih Guru Pengampu (Cari Nama / Kode Guru)</label>
                                                        <select name="guru_id" class="form-select select2-search" data-placeholder="Cari nama atau kode guru..." required>
                                                            <option value=""></option>
                                                            @foreach($gurus as $g)
                                                                <option value="{{ $g->id }}" {{ ($jadwal && $jadwal->pengampu->guru_id == $g->id) ? 'selected' : '' }}>
                                                                    [{{ $g->kode_guru ?? $g->id }}] {{ $g->nama_lengkap ?? $g->nama_guru }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="mb-3 text-start">
                                                        <label class="form-label small fw-bold">Ruangan</label>
                                                        <input type="text" name="ruangan" class="form-control form-control-sm" value="{{ $jadwal->ruangan ?? 'Ruang Kelas' }}">
                                                    </div>
                                                </div>
                                                
                                                <div class="modal-footer border-top d-flex justify-content-between">
                                                    @if($jadwal)
                                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="document.getElementById('delete-jadwal-{{ $jadwal->id }}').submit();">
                                                        Kosongkan Slot
                                                    </button>
                                                    @else
                                                    <div></div>
                                                    @endif

                                                    <div>
                                                        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
                                                    </div>
                                                </div>
                                            </form>

                                            @if($jadwal)
                                            <form id="delete-jadwal-{{ $jadwal->id }}" action="{{ route('admin.jadwal.destroy', $jadwal->id) }}" method="POST" class="d-none">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </tr>
                    @endforeach
                </tbody>
                @endif
            @endforeach
        </table>
    </div>
</div>
@endsection

@push('page-scripts')
<!-- Select2 JS & Inisialisasi Auto-Focus Search dalam Modal -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function () {
        // Inisialisasi Select2 setiap kali Modal dibuka agar field pencarian bekerja 100% presisi
        $('.modal').on('shown.bs.modal', function () {
            $(this).find('.select2-search').select2({
                theme: 'bootstrap-5',
                dropdownParent: $(this),
                width: '100%',
                placeholder: $(this).find('.select2-search').data('placeholder')
            });
        });
    });
</script>
@endpush