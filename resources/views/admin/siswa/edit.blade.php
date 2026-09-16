@extends('layouts.app')

@section('title', 'Edit Siswa - Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold py-1 mb-0"><span class="text-muted fw-light">Siswa /</span> Edit Data Siswa</h4>
    <a href="{{ route('admin.siswa.index') }}" class="btn btn-secondary">
        <i class="bx bx-arrow-back me-1"></i> Batal
    </a>
</div>

<form action="{{ route('admin.siswa.update', $siswa->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="nav-align-top mb-4">
        <ul class="nav nav-tabs nav-fill" role="tablist">
            <li class="nav-item">
                <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-identitas">
                    <i class="tf-icons bx bx-user me-1"></i> 1. Identitas Inti & Pribadi
                </button>
            </li>
            <li class="nav-item">
                <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-akademik">
                    <i class="tf-icons bx bx-book-bookmark me-1"></i> 2. Akademik & Akun
                </button>
            </li>
            <li class="nav-item">
                <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-ortu">
                    <i class="tf-icons bx bx-group me-1"></i> 3. Data Orang Tua / Wali
                </button>
            </li>
        </ul>

        <div class="tab-content card">
            <!-- TAB 1: IDENTITAS INTI & PRIBADI -->
            <div class="tab-pane fade show active" id="navs-identitas" role="tabpanel">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label" for="nisn">NISN (Username Login) <span class="text-danger">*</span></label>
                        <input type="text" id="nisn" name="nisn" class="form-control @error('nisn') is-invalid @enderror" value="{{ old('nisn', $siswa->nisn) }}" required />
                        @error('nisn') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="nis">NIS Lokal Sekolah</label>
                        <input type="text" id="nis" name="nis" class="form-control @error('nis') is-invalid @enderror" value="{{ old('nis', $siswa->nis) }}" />
                        @error('nis') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-8">
                        <label class="form-label" for="nama_lengkap">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" id="nama_lengkap" name="nama_lengkap" class="form-control @error('nama_lengkap') is-invalid @enderror" value="{{ old('nama_lengkap', $siswa->nama_lengkap) }}" required />
                        @error('nama_lengkap') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" for="nama_panggilan">Nama Panggilan</label>
                        <input type="text" id="nama_panggilan" name="nama_panggilan" class="form-control" value="{{ old('nama_panggilan', $siswa->nama_panggilan) }}" />
                    </div>

                    <div class="col-md-4">
                        <label class="form-label" for="tempat_lahir">Tempat Lahir <span class="text-danger">*</span></label>
                        <input type="text" id="tempat_lahir" name="tempat_lahir" class="form-control @error('tempat_lahir') is-invalid @enderror" value="{{ old('tempat_lahir', $siswa->tempat_lahir) }}" required />
                        @error('tempat_lahir') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" for="tgl_lahir">Tanggal Lahir <span class="text-danger">*</span></label>
                        <input type="date" id="tgl_lahir" name="tgl_lahir" class="form-control @error('tgl_lahir') is-invalid @enderror" value="{{ old('tgl_lahir', $siswa->tgl_lahir) }}" required />
                        @error('tgl_lahir') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" for="jenis_kelamin">Jenis Kelamin <span class="text-danger">*</span></label>
                        <select id="jenis_kelamin" name="jenis_kelamin" class="form-select @error('jenis_kelamin') is-invalid @enderror" required>
                            <option value="L" {{ old('jenis_kelamin', $siswa->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('jenis_kelamin', $siswa->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        @error('jenis_kelamin') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label" for="agama">Agama</label>
                        <input type="text" id="agama" name="agama" class="form-control" value="{{ old('agama', $siswa->agama) }}" />
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" for="no_hp">No. HP / WhatsApp Siswa</label>
                        <input type="text" id="no_hp" name="no_hp" class="form-control" value="{{ old('no_hp', $siswa->no_hp) }}" />
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" for="foto">Ganti Foto Profil (Opsional)</label>
                        <input type="file" id="foto" name="foto" class="form-control @error('foto') is-invalid @enderror" accept="image/*" />
                        @if($siswa->foto)
                            <small class="text-muted">Foto saat ini: <a href="{{ asset('storage/' . $siswa->foto) }}" target="_blank">Lihat Foto</a></small>
                        @endif
                        @error('foto') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-12">
                        <label class="form-label" for="alamat">Alamat Tempat Tinggal</label>
                        <textarea id="alamat" name="alamat" class="form-control" rows="2">{{ old('alamat', $siswa->alamat) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- TAB 2: DATA AKADEMIK & AKUN -->
            <div class="tab-pane fade" id="navs-akademik" role="tabpanel">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label" for="email">Email Aktif</label>
                        <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $siswa->user->email) }}" />
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="status_siswa">Status Siswa <span class="text-danger">*</span></label>
                        <select id="status_siswa" name="status_siswa" class="form-select @error('status_siswa') is-invalid @enderror" required>
                            <option value="aktif" {{ old('status_siswa', $siswa->status_siswa) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="lulus" {{ old('status_siswa', $siswa->status_siswa) == 'lulus' ? 'selected' : '' }}>Lulus</option>
                            <option value="mutasi" {{ old('status_siswa', $siswa->status_siswa) == 'mutasi' ? 'selected' : '' }}>Mutasi</option>
                            <option value="keluar" {{ old('status_siswa', $siswa->status_siswa) == 'keluar' ? 'selected' : '' }}>Keluar</option>
                        </select>
                        @error('status_siswa') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label" for="kelas">Kelas <span class="text-danger">*</span></label>
                        <input type="text" id="kelas" name="kelas" class="form-control @error('kelas') is-invalid @enderror" value="{{ old('kelas', $siswa->kelas) }}" required />
                        @error('kelas') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" for="jurusan">Jurusan</label>
                        <input type="text" id="jurusan" name="jurusan" class="form-control" value="{{ old('jurusan', $siswa->jurusan) }}" />
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" for="angkatan">Tahun Angkatan <span class="text-danger">*</span></label>
                        <input type="number" id="angkatan" name="angkatan" class="form-control @error('angkatan') is-invalid @enderror" value="{{ old('angkatan', $siswa->angkatan) }}" required />
                        @error('angkatan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" for="tgl_diterima">Tanggal Resmi Diterima</label>
                        <input type="date" id="tgl_diterima" name="tgl_diterima" class="form-control" value="{{ old('tgl_diterima', $siswa->tgl_diterima) }}" />
                    </div>
                </div>
            </div>

            <!-- TAB 3: DATA ORANG TUA / WALI -->
            <div class="tab-pane fade" id="navs-ortu" role="tabpanel">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label" for="nama_ayah">Nama Ayah Kandung</label>
                        <input type="text" id="nama_ayah" name="nama_ayah" class="form-control" value="{{ old('nama_ayah', $siswa->nama_ayah) }}" />
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="nama_ibu">Nama Ibu Kandung</label>
                        <input type="text" id="nama_ibu" name="nama_ibu" class="form-control" value="{{ old('nama_ibu', $siswa->nama_ibu) }}" />
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" for="nama_wali">Nama Wali</label>
                        <input type="text" id="nama_wali" name="nama_wali" class="form-control" value="{{ old('nama_wali', $siswa->nama_wali) }}" />
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="no_hp_ortu">No. HP / WhatsApp Orang Tua</label>
                        <input type="text" id="no_hp_ortu" name="no_hp_ortu" class="form-control" value="{{ old('no_hp_ortu', $siswa->no_hp_ortu) }}" />
                    </div>

                    <div class="col-md-12">
                        <label class="form-label" for="pekerjaan_ortu">Pekerjaan Orang Tua / Wali</label>
                        <input type="text" id="pekerjaan_ortu" name="pekerjaan_ortu" class="form-control" value="{{ old('pekerjaan_ortu', $siswa->pekerjaan_ortu) }}" />
                    </div>
                </div>

                <div class="mt-4 pt-2 border-top text-end">
                    <button type="submit" class="btn btn-warning">
                        <i class="bx bx-edit me-1"></i> Perbarui Data Siswa
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection