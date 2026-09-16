@extends('layouts.app')

@section('title', 'Keuangan & SPP - Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold py-1 mb-0"><span class="text-muted fw-light">Keuangan /</span> Riwayat Transaksi</h4>
    <a href="{{ route('admin.keuangan.create') }}" class="btn btn-primary">
        <i class="bx bx-plus me-1"></i> Transaksi Pembayaran Baru
    </a>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="bx bx-check-circle me-1"></i> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="card">
    <h5 class="card-header">Laporan Transaksi Pembayaran Masuk</h5>
    <div class="table-responsive text-nowrap">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode Transaksi</th>
                    <th>Siswa</th>
                    <th>Pos Keuangan</th>
                    <th>Periode</th>
                    <th>Nominal</th>
                    <th>Petugas</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                @forelse($pembayarans as $index => $p)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td><span class="badge bg-label-primary fw-bold">{{ $p->kode_transaksi }}</span></td>
                    <td>
                        <strong>{{ $p->siswa->nama_lengkap }}</strong>
                        <br><small class="text-muted">NISN: {{ $p->siswa->nisn }} | {{ $p->siswa->kelas }}</small>
                    </td>
                    <td><span class="badge bg-label-info">{{ $p->jenisPembayaran->nama_pembayaran }}</span></td>
                    <td>{{ $p->bulan_dibayar ? $p->bulan_dibayar . ' ' . $p->tahun_dibayar : 'Bebas / Non-Bulanan' }}</td>
                    <td><strong class="text-success">Rp {{ number_format($p->jumlah_bayar, 0, ',', '.') }}</strong></td>
                    <td><small class="text-muted">{{ $p->admin->name }}</small></td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('admin.keuangan.kuitansi', $p->id) }}" target="_blank" class="btn btn-icon btn-sm btn-outline-secondary" title="Cetak Kuitansi">
                                <i class="bx bx-printer"></i>
                            </a>
                            <form action="{{ route('admin.keuangan.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Yakin ingin membatalkan transaksi ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-icon btn-sm btn-outline-danger" title="Batalkan/Hapus">
                                    <i class="bx bx-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-4">Belum ada data transaksi pembayaran recorded.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection