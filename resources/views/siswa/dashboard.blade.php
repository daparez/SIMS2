@extends('layouts.app')

@section('title', 'Dashboard Saya - SIM Sekolah')

@section('content')
<style>
    .nav-pill-custom .nav-link {
        color: #566a7f;
        font-weight: 600;
        border-radius: 8px;
        padding: 10px 16px;
        transition: all 0.2s ease;
    }
    .nav-pill-custom .nav-link.active {
        background-color: #696cff !important;
        color: #ffffff !important;
        box-shadow: 0 4px 12px rgba(105, 108, 255, 0.35);
    }
    .card-pelajar-mini {
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        border: 1.5px solid #696cff;
        border-radius: 12px;
        position: relative;
        overflow: hidden;
    }
    .card-pelajar-mini::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 6px;
        background: linear-gradient(90deg, #696cff 0%, #00d2ff 100%);
    }
</style>

<!-- BANNER PROFIL UTAMA -->
<div class="card bg-primary text-white border-0 shadow-sm mb-4" style="background: linear-gradient(135deg, #696cff 0%, #393bbf 100%); border-radius: 14px;">
    <div class="card-body py-4">
        <div class="d-flex align-items-center justify-content-between row">
            <div class="col-md-8 d-flex align-items-center mb-3 mb-md-0">
                @if($siswa->foto)
                    <img src="{{ asset('storage/' . $siswa->foto) }}" class="rounded-circle border border-3 border-white me-3 shadow-sm" width="70" height="70" style="object-fit: cover;">
                @else
                    <div class="avatar avatar-xl me-3 shadow-sm" style="width: 70px; height: 70px;">
                        <span class="avatar-initial rounded-circle bg-white text-primary fs-2 fw-bold">
                            {{ strtoupper(substr($siswa->nama_lengkap, 0, 2)) }}
                        </span>
                    </div>
                @endif
                <div>
                    <h4 class="card-title text-white fw-bold mb-1">{{ $siswa->nama_lengkap }}</h4>
                    <p class="mb-0 text-white-50 small">
                        Kelas: <span class="text-white fw-bold">{{ $siswa->kelas }} {{ $siswa->jurusan ? '('.$siswa->jurusan.')' : '' }}</span> | 
                        NISN: <span class="text-white fw-bold">{{ $siswa->nisn }}</span>
                    </p>
                </div>
            </div>
            
            <div class="col-md-4 text-md-end">
                @if($absensiHariIni)
                    <span class="badge bg-success p-2 fs-6 shadow-sm"><i class="bx bx-check-circle me-1"></i> Sudah Absen Hari Ini ({{ $absensiHariIni->jam_masuk }})</span>
                @else
                    <span class="badge bg-warning text-dark p-2 fs-6 shadow-sm"><i class="bx bx-time-five me-1"></i> Belum Absen Hari Ini</span>
                @endif
            </div>
        </div>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show rounded-3 mb-4" role="alert">
    <i class="bx bx-check-circle me-1"></i> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<!-- NAVIGASI TOMBOL HALAMAN (TAB CONTROL) -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-2">
        <ul class="nav nav-pills nav-pill-custom nav-fill gap-1" role="tablist">
            <li class="nav-item">
                <button type="button" class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-ringkasan">
                    <i class="bx bx-home-alt me-1"></i> Beranda
                </button>
            </li>
            <li class="nav-item">
                <button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-kartu">
                    <i class="bx bx-id-card me-1"></i> Kartu Pelajar
                </button>
            </li>
            <li class="nav-item">
                <button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-jadwal">
                    <i class="bx bx-calendar me-1"></i> Jadwal Pelajaran
                </button>
            </li>
            <li class="nav-item">
                <button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-biodata">
                    <i class="bx bx-user-detail me-1"></i> Lengkapi Data
                </button>
            </li>
            <li class="nav-item">
                <button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-keuangan">
                    <i class="bx bx-wallet me-1"></i> Keuangan
                </button>
            </li>
        </ul>
    </div>
</div>

<!-- KONTEN TAB HASIL NAVIGASI -->
<div class="tab-content bg-transparent p-0">
    
    <!-- ================= 1. TAB BERANDA & PENGUMUMAN ================= -->
    <div class="tab-pane fade show active" id="tab-ringkasan">
        
        <!-- Pengumuman Sekolah -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <h5 class="fw-bold text-primary mb-3"><i class="bx bx-megaphone me-2"></i>Pengumuman & Informasi Sekolah</h5>
                @forelse($pengumumans as $p)
                <div class="p-3 bg-light rounded-3 mb-2 border-start border-primary border-4 shadow-sm">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <strong class="text-dark">{{ $p->judul }}</strong>
                        <small class="text-muted"><i class="bx bx-time me-1"></i>{{ \Carbon\Carbon::parse($p->created_at)->diffForHumans() }}</small>
                    </div>
                    <p class="mb-0 text-muted small">{{ $p->isi }}</p>
                </div>
                @empty
                <p class="text-muted mb-0 small">Belum ada pengumuman terbaru saat ini.</p>
                @endforelse
            </div>
        </div>

        <!-- Statistik Kehadiran Saya -->
        <h5 class="fw-bold mb-3"><i class="bx bx-calendar-check text-primary me-2"></i>Statistik Kehadiran Saya</h5>
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm text-center py-3 bg-white">
                    <span class="text-muted small">Hadir</span>
                    <h3 class="fw-bold text-success mb-0">{{ $totalHadir }}</h3>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm text-center py-3 bg-white">
                    <span class="text-muted small">Izin</span>
                    <h3 class="fw-bold text-info mb-0">{{ $totalIzin }}</h3>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm text-center py-3 bg-white">
                    <span class="text-muted small">Sakit</span>
                    <h3 class="fw-bold text-warning mb-0">{{ $totalSakit }}</h3>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm text-center py-3 bg-white">
                    <span class="text-muted small">Alpha</span>
                    <h3 class="fw-bold text-danger mb-0">{{ $totalAlpha }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- ================= 2. TAB KARTU PELAJAR & QR CODE (DESAIN RAPI) ================= -->
    <div class="tab-pane fade" id="tab-kartu">
        <div class="row g-4 justify-content-center">
            
            <!-- QR Scanner Absensi -->
            <div class="col-md-6">
                <div class="card border-0 shadow-sm text-center p-4">
                    <h5 class="fw-bold mb-2"><i class="bx bx-qr-scan text-primary me-2"></i>QR Code Presensi Harian</h5>
                    <p class="text-muted small mb-3">Arahkan QR Code ini ke depan kamera scanner sekolah saat presensi masuk & pulang.</p>
                    
                    <div class="p-3 bg-white rounded-3 d-inline-block border mx-auto mb-3 shadow-sm">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data={{ $siswa->nisn }}" alt="QR" class="img-fluid" />
                    </div>
                    
                    <h6 class="fw-bold text-primary mb-0">{{ $siswa->nama_lengkap }}</h6>
                    <small class="text-muted">NISN: {{ $siswa->nisn }}</small>
                </div>
            </div>

            <!-- Pratinjau Kartu Pelajar Digital -->
            <div class="col-md-6">
                <div class="card border-0 shadow-sm p-4">
                    <h5 class="fw-bold mb-3 text-center"><i class="bx bx-id-card text-primary me-2"></i>Kartu Pelajar Digital</h5>
                    
                    <!-- Kartu Mini Model Fisik -->
                    <div class="card-pelajar-mini p-3 mb-3 shadow-sm">
                        <div class="d-flex align-items-center mb-3 pb-2 border-bottom">
                            <div class="avatar avatar-xs me-2">
                                <span class="avatar-initial rounded-circle bg-primary text-white"><i class="bx bxs-school"></i></span>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-0 text-uppercase text-primary" style="font-size: 11px;">{{ $setting->nama_sekolah ?? 'SMA / SMK NEGERI SEKOLAH' }}</h6>
                                <small class="text-muted d-block" style="font-size: 8px; letter-spacing: 0.5px;">KARTU TANDA PELAJAR DIGITAL</small>
                            </div>
                        </div>

                        <div class="d-flex align-items-center">
                            @if($siswa->foto)
                                <img src="{{ asset('storage/' . $siswa->foto) }}" class="rounded me-3 border border-2 border-primary" width="65" height="85" style="object-fit: cover;">
                            @else
                                <div class="bg-light rounded me-3 border d-flex align-items-center justify-content-center text-muted fw-bold small" style="width: 65px; height: 85px; font-size: 8px;">FOTO 3x4</div>
                            @endif
                            <div style="font-size: 11px; line-height: 1.4;">
                                <div class="fw-bold text-dark fs-6">{{ $siswa->nama_lengkap }}</div>
                                <div><strong>NISN:</strong> {{ $siswa->nisn }}</div>
                                <div><strong>Kelas:</strong> {{ $siswa->kelas }} {{ $siswa->jurusan ? '('.$siswa->jurusan.')' : '' }}</div>
                                <div class="mt-1"><span class="badge bg-label-success">Siswa Aktif</span></div>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid">
                        <button onclick="window.print()" class="btn btn-primary fw-bold">
                            <i class="bx bx-printer me-1"></i> Cetak Kartu Pelajar
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- ================= 3. TAB JADWAL PELAJARAN (NEW MODUL) ================= -->
    <div class="tab-pane fade" id="tab-jadwal">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                <h5 class="card-title m-0 fw-bold"><i class="bx bx-calendar-event text-primary me-2"></i>Jadwal Pelajaran Kelas {{ $siswa->kelas }}</h5>
                <span class="badge bg-label-info">Semester Aktif</span>
            </div>
            <div class="card-body py-4 text-center">
                <i class="bx bx-time-five display-3 text-muted mb-3"></i>
                <h6 class="fw-bold text-dark">Jadwal Pelajaran Kelas {{ $siswa->kelas }} Akan Diberitahukan</h6>
                <p class="text-muted small mb-0">Admin/Wali Kelas sedang menyusun jadwal mingguan untuk kelas kamu.</p>
            </div>
        </div>
    </div>

    <!-- ================= 4. TAB LENGKAPI DATA & DOKUMEN ================= -->
    <div class="tab-pane fade" id="tab-biodata">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom py-3">
                <h5 class="card-title m-0 fw-bold"><i class="bx bx-edit text-primary me-2"></i>Kelengkapi Data Diri & Upload Dokumen Digital</h5>
            </div>
            <div class="card-body py-4">
                <form action="{{ route('siswa.biodata.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- IDENTITAS SISWA -->
                    <h6 class="fw-bold text-dark mb-3"><i class="bx bx-user me-1 text-primary"></i> Data Identitas Diri</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label small">NIK Siswa (16 Digit)</label>
                            <input type="text" name="nik" class="form-control" value="{{ old('nik', $siswa->nik) }}" placeholder="16 Digit NIK sesuai KK/KTP" />
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small">Agama</label>
                            <select name="agama" class="form-select">
                                <option value="">-- Pilih Agama --</option>
                                @foreach(['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Khonghucu'] as $ag)
                                    <option value="{{ $ag }}" {{ $siswa->agama == $ag ? 'selected' : '' }}>{{ $ag }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small">Tempat Lahir</label>
                            <input type="text" name="tempat_lahir" class="form-control" value="{{ old('tempat_lahir', $siswa->tempat_lahir) }}" />
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small">Tanggal Lahir</label>
                            <input type="date" name="tgl_lahir" class="form-control" value="{{ old('tgl_lahir', $siswa->tgl_lahir) }}" />
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small">No. HP / WhatsApp Siswa</label>
                            <input type="text" name="no_hp" class="form-control" value="{{ old('no_hp', $siswa->no_hp) }}" placeholder="08xxxxxxxxxx" />
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small">Alamat Lengkap Domisili</label>
                            <input type="text" name="alamat" class="form-control" value="{{ old('alamat', $siswa->alamat) }}" />
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- DATA ORANG TUA -->
                    <h6 class="fw-bold text-dark mb-3"><i class="bx bx-group me-1 text-primary"></i> Data Orang Tua / Wali</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label small">Nama Orang Tua / Wali</label>
                            <input type="text" name="nama_ortu" class="form-control" value="{{ old('nama_ortu', $siswa->nama_ortu) }}" />
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small">No. HP / WhatsApp Orang Tua</label>
                            <input type="text" name="no_hp_ortu" class="form-control" value="{{ old('no_hp_ortu', $siswa->no_hp_ortu) }}" />
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- UPLOAD DOKUMEN DIGITAL -->
                    <h6 class="fw-bold text-dark mb-3"><i class="bx bx-file me-1 text-primary"></i> Upload Berkas Dokumen (Format PDF/JPG, Max 2MB)</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Pas Foto (3x4)</label>
                            <input type="file" name="foto" class="form-control form-control-sm" accept="image/*" />
                            @if($siswa->foto)
                                <small class="text-success d-block mt-1"><i class="bx bx-check me-1"></i> Terunggah</small>
                            @endif
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Kartu Keluarga (KK)</label>
                            <input type="file" name="file_kk" class="form-control form-control-sm" accept=".pdf,.jpg,.jpeg,.png" />
                            @if($siswa->file_kk)
                                <small class="text-success d-block mt-1"><i class="bx bx-check me-1"></i> <a href="{{ asset('storage/' . $siswa->file_kk) }}" target="_blank">Lihat KK</a></small>
                            @endif
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Ijazah / SKL</label>
                            <input type="file" name="file_ijazah" class="form-control form-control-sm" accept=".pdf,.jpg,.jpeg,.png" />
                            @if($siswa->file_ijazah)
                                <small class="text-success d-block mt-1"><i class="bx bx-check me-1"></i> <a href="{{ asset('storage/' . $siswa->file_ijazah) }}" target="_blank">Lihat Ijazah</a></small>
                            @endif
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Akta Kelahiran</label>
                            <input type="file" name="file_akta" class="form-control form-control-sm" accept=".pdf,.jpg,.jpeg,.png" />
                            @if($siswa->file_akta)
                                <small class="text-success d-block mt-1"><i class="bx bx-check me-1"></i> <a href="{{ asset('storage/' . $siswa->file_akta) }}" target="_blank">Lihat Akta</a></small>
                            @endif
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">KTP Ayah</label>
                            <input type="file" name="file_ktp_ayah" class="form-control form-control-sm" accept=".pdf,.jpg,.jpeg,.png" />
                            @if($siswa->file_ktp_ayah)
                                <small class="text-success d-block mt-1"><i class="bx bx-check me-1"></i> <a href="{{ asset('storage/' . $siswa->file_ktp_ayah) }}" target="_blank">Lihat KTP Ayah</a></small>
                            @endif
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">KTP Ibu</label>
                            <input type="file" name="file_ktp_ibu" class="form-control form-control-sm" accept=".pdf,.jpg,.jpeg,.png" />
                            @if($siswa->file_ktp_ibu)
                                <small class="text-success d-block mt-1"><i class="bx bx-check me-1"></i> <a href="{{ asset('storage/' . $siswa->file_ktp_ibu) }}" target="_blank">Lihat KTP Ibu</a></small>
                            @endif
                        </div>
                    </div>

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-primary fw-bold py-2">
                            <i class="bx bx-save me-1"></i> Simpan Perubahan Biodata
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ================= 5. TAB KEUANGAN / SPP ================= -->
    <div class="tab-pane fade" id="tab-keuangan">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom d-flex align-items-center justify-content-between py-3">
                <h5 class="card-title m-0 fw-bold"><i class="bx bx-wallet text-primary me-2"></i>Riwayat Pembayaran Keuangan</h5>
                <span class="badge bg-label-primary fs-6">Total Bayar: Rp {{ number_format($totalDibayar, 0, ',', '.') }}</span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Kode TRX</th>
                            <th>Pembayaran</th>
                            <th>Tanggal</th>
                            <th>Nominal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($riwayatPembayaran as $r)
                        <tr>
                            <td><span class="badge bg-label-primary">{{ $r->kode_transaksi }}</span></td>
                            <td><strong>{{ $r->jenisPembayaran->nama_pembayaran }}</strong></td>
                            <td>{{ \Carbon\Carbon::parse($r->tgl_bayar)->translatedFormat('d F Y') }}</td>
                            <td><strong class="text-success">Rp {{ number_format($r->jumlah_bayar, 0, ',', '.') }}</strong></td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center py-4 text-muted">Belum ada riwayat transaksi pembayaran.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection