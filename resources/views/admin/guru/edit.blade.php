@extends('layouts.app')

@section('title', 'Edit Guru - Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold py-1 mb-0"><span class="text-muted fw-light">Guru /</span> Edit Data Guru</h4>
    <a href="{{ route('admin.guru.index') }}" class="btn btn-secondary">
        <i class="bx bx-arrow-back me-1"></i> Batal
    </a>
</div>

<form action="{{ route('admin.guru.update', $guru->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

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
                        <input type="text" name="nip" class="form-control @error('nip') is-invalid @enderror" value="{{ old('nip', $guru->nip) }}" required />
                        @error('nip') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">NIK (Sesuai KTP)</label>
                        <input type="text" name="nik" class="form-control @error('nik') is-invalid @enderror" value="{{ old('nik', $guru->nik) }}" />
                        @error('nik') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-8">
                        <label class="form-label">Nama Lengkap & Gelar <span class="text-danger">*</span></label>
                        <input type="text" name="nama_lengkap" class="form-control @error('nama_lengkap') is-invalid @enderror" value="{{ old('nama_lengkap', $guru->nama_lengkap) }}" required />
                        @error('nama_lengkap') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                        <select name="jenis_kelamin" class="form-select" required>
                            <option value="L" {{ old('jenis_kelamin', $guru->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('jenis_kelamin', $guru->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Tempat Lahir <span class="text-danger">*</span></label>
                        <input type="text" name="tempat_lahir" class="form-control" value="{{ old('tempat_lahir', $guru->tempat_lahir) }}" required />
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                        <input type="date" name="tgl_lahir" class="form-control" value="{{ old('tgl_lahir', $guru->tgl_lahir) }}" required />
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Agama</label>
                        <input type="text" name="agama" class="form-control" value="{{ old('agama', $guru->agama) }}" />
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">No. HP / WhatsApp</label>
                        <input type="text" name="no_hp" class="form-control" value="{{ old('no_hp', $guru->no_hp) }}" />
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Email Resmi</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $guru->user->email) }}" />
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Ganti Foto Profil</label>
                        <input type="file" name="foto" class="form-control" accept="image/*" />
                        @if($guru->foto)
                            <small class="text-muted">Foto saat ini: <a href="{{ asset('storage/' . $guru->foto) }}" target="_blank">Lihat Foto</a></small>
                        @endif
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Alamat Domisili</label>
                        <textarea name="alamat" class="form-control" rows="2">{{ old('alamat', $guru->alamat) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- TAB 2: KEPEGAWAIAN -->
            <div class="tab-pane fade" id="navs-kepegawaian" role="tabpanel">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Status Kepegawaian <span class="text-danger">*</span></label>
                        <select name="status_kepegawaian" class="form-select" required>
                            <option value="PNS" {{ old('status_kepegawaian', $guru->status_kepegawaian) == 'PNS' ? 'selected' : '' }}>PNS</option>
                            <option value="PPPK" {{ old('status_kepegawaian', $guru->status_kepegawaian) == 'PPPK' ? 'selected' : '' }}>PPPK</option>
                            <option value="GTY" {{ old('status_kepegawaian', $guru->status_kepegawaian) == 'GTY' ? 'selected' : '' }}>GTY (Guru Tetap Yayasan)</option>
                            <option value="GTT" {{ old('status_kepegawaian', $guru->status_kepegawaian) == 'GTT' ? 'selected' : '' }}>GTT (Guru Tidak Tetap)</option>
                            <option value="Honorer" {{ old('status_kepegawaian', $guru->status_kepegawaian) == 'Honorer' ? 'selected' : '' }}>Honorer</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Jenis PTK <span class="text-danger">*</span></label>
                        <select name="jenis_ptk" class="form-select" required>
                            <option value="Guru Mata Pelajaran" {{ old('jenis_ptk', $guru->jenis_ptk) == 'Guru Mata Pelajaran' ? 'selected' : '' }}>Guru Mata Pelajaran</option>
                            <option value="Guru Kelas" {{ old('jenis_ptk', $guru->jenis_ptk) == 'Guru Kelas' ? 'selected' : '' }}>Guru Kelas</option>
                            <option value="Guru BK" {{ old('jenis_ptk', $guru->jenis_ptk) == 'Guru BK' ? 'selected' : '' }}>Guru BK / Konselor</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Pangkat / Golongan</label>
                        <input type="text" name="pangkat_golongan" class="form-control" value="{{ old('pangkat_golongan', $guru->pangkat_golongan) }}" />
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Tanggal Masuk / TMT</label>
                        <input type="date" name="tgl_masuk" class="form-control" value="{{ old('tgl_masuk', $guru->tgl_masuk) }}" />
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Penugasan Wali Kelas</label>
                        <select name="wali_kelas_id" class="form-select">
                            <option value="">-- Bukan Wali Kelas --</option>
                            @foreach($kelases as $k)
                                <option value="{{ $k->id }}" {{ old('wali_kelas_id', $guru->wali_kelas_id) == $k->id ? 'selected' : '' }}>
                                    {{ $k->nama_kelas }}
                                </option>
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
                            <option value="S1" {{ old('pendidikan_terakhir', $guru->pendidikan_terakhir) == 'S1' ? 'selected' : '' }}>S1 / D4</option>
                            <option value="S2" {{ old('pendidikan_terakhir', $guru->pendidikan_terakhir) == 'S2' ? 'selected' : '' }}>S2</option>
                            <option value="S3" {{ old('pendidikan_terakhir', $guru->pendidikan_terakhir) == 'S3' ? 'selected' : '' }}>S3</option>
                            <option value="D3" {{ old('pendidikan_terakhir', $guru->pendidikan_terakhir) == 'D3' ? 'selected' : '' }}>D3</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Program Studi / Jurusan</label>
                        <input type="text" name="jurusan_pendidikan" class="form-control" value="{{ old('jurusan_pendidikan', $guru->jurusan_pendidikan) }}" />
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Nama Universitas / Kampus</label>
                        <input type="text" name="nama_kampus" class="form-control" value="{{ old('nama_kampus', $guru->nama_kampus) }}" />
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">NUPTK / NRG</label>
                        <input type="text" name="nuptk" class="form-control" value="{{ old('nuptk', $guru->nuptk) }}" />
                    </div>
                    <div class="col-md-6 d-flex align-items-center pt-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_sertifikasi" id="is_sertifikasi" value="1" {{ $guru->is_sertifikasi ? 'checked' : '' }} />
                            <label class="form-check-label fw-bold" for="is_sertifikasi">Sudah Memiliki Sertifikasi Pendidik</label>
                        </div>
                    </div>
                </div>

                <div class="mt-4 pt-2 border-top text-end">
                    <button type="submit" class="btn btn-warning">
                        <i class="bx bx-edit me-1"></i> Perbarui Data Guru
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection