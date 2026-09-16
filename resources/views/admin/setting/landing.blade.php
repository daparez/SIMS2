@extends('layouts.app')

@section('title', 'Kelola Halaman Landing Page Sekolah - Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold py-1 mb-0"><span class="text-muted fw-light">Pengaturan /</span> Kelola Halaman Welcome</h4>
        <small class="text-muted">Atur profil publik, foto kepala sekolah, dan galeri kegiatan sekolah.</small>
    </div>
    <a href="{{ route('welcome') }}" target="_blank" class="btn btn-outline-primary fw-bold shadow-sm">
        <i class="bx bx-show me-1"></i> Lihat Halaman Depan
    </a>
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

<!-- NAV TABS PENGATURAN -->
<ul class="nav nav-pills mb-4" id="pills-tab" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active fw-bold" id="pills-profil-tab" data-bs-toggle="pill" data-bs-target="#pills-profil" type="button" role="tab"><i class="bx bx-building-house me-1"></i> Informasi & Profil Sekolah</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link fw-bold" id="pills-galeri-tab" data-bs-toggle="pill" data-bs-target="#pills-galeri" type="button" role="tab"><i class="bx bx-images me-1"></i> Kelola Galeri Foto Kegiatan ({{ $galeris->count() }})</button>
    </li>
</ul>

<div class="tab-content" id="pills-tabContent" style="padding: 0; background: transparent; shadow: none;">
    <!-- TAB 1: INFORMASI PROFIL & FOTO KEPSEK -->
    <div class="tab-pane fade show active" id="pills-profil" role="tabpanel">
        <form action="{{ route('admin.setting.landing.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white border-bottom py-3">
                            <h5 class="fw-bold mb-0 text-dark"><i class="bx bx-info-circle me-2 text-primary"></i>Identitas & Visi Misi</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Nama Sekolah</label>
                                    <input type="text" name="nama_sekolah" class="form-control" value="{{ $profile->nama_sekolah ?? '' }}" required />
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Slogan / Tagline</label>
                                    <input type="text" name="slogan" class="form-control" value="{{ $profile->slogan ?? '' }}" />
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label small fw-bold">Sejarah Singkat Sekolah</label>
                                    <textarea name="sejarah_sekolah" class="form-control" rows="3">{{ $profile->sejarah_sekolah ?? '' }}</textarea>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Visi Sekolah</label>
                                    <textarea name="visi" class="form-control" rows="3">{{ $profile->visi ?? '' }}</textarea>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Misi Sekolah</label>
                                    <textarea name="misi" class="form-control" rows="3">{{ $profile->misi ?? '' }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- KEPALA SEKOLAH -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-bottom py-3">
                            <h5 class="fw-bold mb-0 text-dark"><i class="bx bx-user-voice me-2 text-primary"></i>Sambutan Kepala Sekolah</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Nama Kepala Sekolah</label>
                                    <input type="text" name="kepala_sekolah_nama" class="form-control" value="{{ $profile->kepala_sekolah_nama ?? '' }}" placeholder="Contoh: Drs. H. Ahmad Wijaya, M.Pd" />
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Foto Kepala Sekolah</label>
                                    @if(!empty($profile->foto_kepala_sekolah))
                                        <div class="mb-2">
                                            <img src="{{ asset('storage/' . $profile->foto_kepala_sekolah) }}" class="rounded img-thumbnail" style="height: 80px; width: 80px; object-fit: cover;">
                                        </div>
                                    @endif
                                    <input type="file" name="foto_kepala_sekolah" class="form-control" accept="image/*" />
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label small fw-bold">Teks Sambutan</label>
                                    <textarea name="sambutan_kepala_sekolah" class="form-control" rows="4" placeholder="Tulis sambutan kepala sekolah di sini...">{{ $profile->sambutan_kepala_sekolah ?? '' }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white border-bottom py-3">
                            <h5 class="fw-bold mb-0 text-dark"><i class="bx bx-image me-2 text-primary"></i>Foto Banner Utama</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Foto Hero Banner (Slide Utama)</label>
                                @if(!empty($profile->foto_hero))
                                    <img src="{{ asset('storage/' . $profile->foto_hero) }}" class="img-fluid rounded mb-2 border d-block" style="max-height: 100px;">
                                @endif
                                <input type="file" name="foto_hero" class="form-control" accept="image/*" />
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold">Foto Gedung / Lingkungan</label>
                                @if(!empty($profile->foto_gedung))
                                    <img src="{{ asset('storage/' . $profile->foto_gedung) }}" class="img-fluid rounded mb-2 border d-block" style="max-height: 100px;">
                                @endif
                                <input type="file" name="foto_gedung" class="form-control" accept="image/*" />
                            </div>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white border-bottom py-3">
                            <h5 class="fw-bold mb-0 text-dark"><i class="bx bx-phone me-2 text-primary"></i>Kontak & Sosmed</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="mb-2">
                                <label class="form-label small fw-bold">Alamat Lengkap</label>
                                <input type="text" name="alamat_lengkap" class="form-control" value="{{ $profile->alamat_lengkap ?? '' }}" />
                            </div>
                            <div class="mb-2">
                                <label class="form-label small fw-bold">No. Telepon / WA</label>
                                <input type="text" name="no_telepon" class="form-control" value="{{ $profile->no_telepon ?? '' }}" />
                            </div>
                            <div class="mb-2">
                                <label class="form-label small fw-bold">Email Sekolah</label>
                                <input type="email" name="email_sekolah" class="form-control" value="{{ $profile->email_sekolah ?? '' }}" placeholder="info@sekolah.sch.id" />
                            </div>
                            <div class="mb-2">
                                <label class="form-label small fw-bold">Instagram</label>
                                <input type="url" name="link_instagram" class="form-control" value="{{ $profile->link_instagram ?? '' }}" placeholder="https://instagram.com/..." />
                            </div>
                        </div>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg fw-bold shadow-sm">
                            <i class="bx bx-save me-1"></i> Simpan Profil Sekolah
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- TAB 2: KELOLA GALERI FOTO KEGIATAN -->
    <div class="tab-pane fade" id="pills-galeri" role="tabpanel">
        <div class="row g-4">
            <!-- Form Upload Galeri Baru -->
            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom py-3">
                        <h5 class="fw-bold mb-0 text-dark"><i class="bx bx-plus-circle text-primary me-2"></i>Tambah Foto Galeri</h5>
                    </div>
                    <form action="{{ route('admin.setting.landing.galeri.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Judul / Nama Kegiatan</label>
                                <input type="text" name="judul" class="form-control" placeholder="Contoh: Lomba Kepramukaan 2026" required />
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Kategori</label>
                                <select name="kategori" class="form-select" required>
                                    <option value="Sosial">Sosial</option>
                                    <option value="Olahraga">Olahraga</option>
                                    <option value="Akademik">Akademik</option>
                                    <option value="Kebudayaan">Kebudayaan</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold">File Foto</label>
                                <input type="file" name="foto" class="form-control" accept="image/*" required />
                            </div>
                        </div>
                        <div class="card-footer bg-light border-top text-end py-2">
                            <button type="submit" class="btn btn-primary fw-bold"><i class="bx bx-upload me-1"></i> Upload Foto</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Daftar Foto Galeri -->
            <div class="col-md-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom py-3">
                        <h5 class="fw-bold mb-0 text-dark"><i class="bx bx-images text-primary me-2"></i>Daftar Galeri Kegiatan</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            @forelse($galeris as $g)
                            <div class="col-md-6 col-lg-4">
                                <div class="card h-100 border shadow-none position-relative overflow-hidden">
                                    <img src="{{ asset('storage/' . $g->foto) }}" class="card-img-top" style="height: 140px; object-fit: cover;">
                                    <div class="card-body p-2 text-center">
                                        <span class="badge bg-label-success mb-1">{{ $g->kategori }}</span>
                                        <h6 class="fw-bold mb-0 text-dark small">{{ $g->judul }}</h6>
                                    </div>
                                    <div class="card-footer p-2 text-center bg-light border-top">
                                        <form action="{{ route('admin.setting.landing.galeri.destroy', $g->id) }}" method="POST" onsubmit="return confirm('Hapus foto galeri ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm w-100 py-1"><i class="bx bx-trash me-1"></i> Hapus</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="col-12 text-center py-5 text-muted">
                                <i class="bx bx-images fs-1 mb-2 d-block"></i>
                                Belum ada foto galeri terdaftar.
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection