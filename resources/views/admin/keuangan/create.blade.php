@extends('layouts.app')

@section('title', 'Input Transaksi - Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold py-1 mb-0"><span class="text-muted fw-light">Keuangan /</span> Input Transaksi Pembayaran</h4>
    <a href="{{ route('admin.keuangan.index') }}" class="btn btn-secondary">
        <i class="bx bx-arrow-back me-1"></i> Kembali
    </a>
</div>

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="bx bx-error me-1"></i> {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="card">
    <h5 class="card-header">Formulir Pembayaran Siswa</h5>
    <div class="card-body">
        <form action="{{ route('admin.keuangan.store') }}" method="POST">
            @csrf

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Pilih Siswa <span class="text-danger">*</span></label>
                    <select name="siswa_id" class="form-select" required>
                        <option value="">-- Pilih Siswa --</option>
                        @foreach($siswas as $s)
                            <option value="{{ $s->id }}">{{ $s->nama_lengkap }} (NISN: {{ $s->nisn }}) - {{ $s->kelas }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Pos Keuangan / Jenis Pembayaran <span class="text-danger">*</span></label>
                    <select name="jenis_pembayaran_id" class="form-select" required>
                        @foreach($jenisPembayarans as $j)
                            <option value="{{ $j->id }}">{{ $j->nama_pembayaran }} (Tipe: {{ ucfirst($j->tipe) }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Bulan Tagihan (Khusus SPP Bulanan)</label>
                    <select name="bulan_dibayar" class="form-select">
                        <option value="">-- Non-Bulanan --</option>
                        @foreach($bulans as $b)
                            <option value="{{ $b }}">{{ $b }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Tahun Tagihan</label>
                    <input type="number" name="tahun_dibayar" class="form-control" value="{{ date('Y') }}" />
                </div>

                <div class="col-md-4">
                    <label class="form-label">Tanggal Bayar <span class="text-danger">*</span></label>
                    <input type="date" name="tgl_bayar" class="form-control" value="{{ date('Y-m-d') }}" required />
                </div>

                <div class="col-md-12">
                    <label class="form-label">Nominal Bayar (Rp) <span class="text-danger">*</span></label>
                    <input type="number" name="jumlah_bayar" class="form-control" placeholder="Contoh: 300000" min="0" required />
                </div>
            </div>

            <div class="mt-4 text-end">
                <button type="submit" class="btn btn-primary">
                    <i class="bx bx-save me-1"></i> Simpan Transaksi
                </button>
            </div>
        </form>
    </div>
</div>
@endsection