@extends('layouts.app')

@section('title', 'Tambah Guru - Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold py-1 mb-0"><span class="text-muted fw-light">Guru /</span> Tambah Guru Baru</h4>
    <a href="{{ route('admin.guru.index') }}" class="btn btn-secondary">
        <i class="bx bx-arrow-back me-1"></i> Kembali
    </a>
</div>

<form action="{{ route('admin.guru.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="nav-align-top mb-4">
        <ul class="nav nav-tabs nav-fill" role="tablist">
            <li class="nav-item">
                <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-pribadi">
                    <i class="tf-icons bx bx-user me-1"></i> 1. Identitas Pribadi
                </button>
            </li>
            <li class="nav-item">
                <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-kepegawaian">
                    <i class="tf-icons bx bx-briefcase me-1"></i> 2. Kepegawaian & Jabatan
                </button>
            </li>
            <li class="nav-item">
                <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-pendidikan">
                    <i class="tf-icons bx bx-certification me-1"></i> 3. Kualifikasi & Sertifikasi
                </button>
            </li>
        </ul>

        <div class="tab-content card">
            <!-- TAB 1: IDENTITAS PRIBADI -->
            <div class="tab-pane fade show active" id="navs-pribadi" role="tabpanel">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">NIP / NIY (Username Login) <span class="text-danger">*</span></label>
                        <input type="text" name="nip" class="form-control @error('nip') is-invalid @enderror" value="{{ old('nip') }}" placeholder="Nomor Induk Pegawai" required />
                        @error('nip') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">NIK (Sesuai KTP)</label>
                        <input type="text" name="nik" class="form-control @error('nik') is-invalid @enderror" value="{{ old('nik') }}" placeholder="16 Digit NIK" />
                        @error('nik') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-8">
                        <label class="form-label">Nama Lengkap & Gelar <span class="text-danger">*</span></label>
                        <input type="text" name="nama_lengkap" class="form-control @error('nama_lengkap') is-invalid @enderror" value="{{ old('nama_lengkap') }}" placeholder="Drs. Ahmad, M.Pd" required />
                        @error('nama_lengkap') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                        <select name="jenis_kelamin" class="form-select" required>
                            <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Tempat Lahir <span class="text-danger">*</span></label>
                        <input type="text" name="tempat_lahir" class="form-control" value="{{ old('tempat_lahir') }}" required />
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Tanggal Lahir (Pass Default) <span class="text-danger">*</span></label>
                        <input type="date" name="tgl_lahir" class="form-control" value="{{ old('tgl_lahir') }}" required />
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Agama</label>
                        <input type="text" name="agama" class="form-control" value="{{ old('agama') }}" placeholder="Islam" />
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">No. HP / WhatsApp</label>
                        <input type="text" name="no_hp" class="form-control" value="{{ old('no_hp') }}" placeholder="08xxxxxxxx" />
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Email Resmi</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="guru@sekolah.sch.id" />
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Foto Profil</label>
                        <input type="file" name="foto" class="form-control" accept="image/*" />
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Alamat Domisili</label>
                        <textarea name="alamat" class="form-control" rows="2">{{ old('alamat') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- TAB 2: KEPEGAWAIAN -->
            <div class="tab-pane fade" id="navs-kepegawaian" role="tabpanel">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Status Kepegawaian <span class="text-danger">*</span></label>
                        <select name="status_kepegawaian" class="form-select" required>
                            <option value="PNS">PNS</option>
                            <option value="PPPK">PPPK</option>
                            <option value="GTY">GTY (Guru Tetap Yayasan)</option>
                            <option value="GTT">GTT (Guru Tidak Tetap)</option>
                            <option value="Honorer">Honorer</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Jenis PTK <span class="text-danger">*</span></label>
                        <select name="jenis_ptk" class="form-select" required>
                            <option value="Guru Mata Pelajaran">Guru Mata Pelajaran</option>
                            <option value="Guru Kelas">Guru Kelas</option>
                            <option value="Guru BK">Guru BK / Konselor</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Pangkat / Golongan (Khusus PNS)</label>
                        <input type="text" name="pangkat_golongan" class="form-control" value="{{ old('pangkat_golongan') }}" placeholder="Penata Muda / III/a" />
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Tanggal Masuk / TMT</label>
                        <input type="date" name="tgl_masuk" class="form-control" value="{{ old('tgl_masuk') }}" />
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Penugasan Wali Kelas (Opsional)</label>
                        <select name="wali_kelas_id" class="form-select">
                            <option value="">-- Bukan Wali Kelas --</option>
                            @foreach($kelases as $k)
                                <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- TAB 3: PENDIDIKAN & SERTIFIKASI -->
            <div class="tab-pane fade" id="navs-pendidikan" role="tabpanel">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Pendidikan Terakhir</label>
                        <select name="pendidikan_terakhir" class="form-select">
                            <option value="S1">S1 / D4</option>
                            <option value="S2">S2</option>
                            <option value="S3">S3</option>
                            <option value="D3">D3</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Program Studi / Jurusan</label>
                        <input type="text" name="jurusan_pendidikan" class="form-control" value="{{ old('jurusan_pendidikan') }}" placeholder="Pendidikan Matematika" />
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Nama Universitas / Kampus</label>
                        <input type="text" name="nama_kampus" class="form-control" value="{{ old('nama_kampus') }}" placeholder="Universitas Negeri ..." />
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">NUPTK / NRG</label>
                        <input type="text" name="nuptk" class="form-control" value="{{ old('nuptk') }}" placeholder="Nomor Unik Pendidik" />
                    </div>
                    <div class="col-md-6 d-flex align-items-center pt-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_sertifikasi" id="is_sertifikasi" value="1" />
                            <label class="form-check-label fw-bold" for="is_sertifikasi">Sudah Memiliki Sertifikasi Pendidik</label>
                        </div>
                    </div>
                </div>

                <div class="mt-4 pt-2 border-top text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="bx bx-save me-1"></i> Simpan Guru & Buat Akun
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection