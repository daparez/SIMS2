<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $profile->nama_sekolah ?? 'SMA 1 CONTOH SMARTSCHOOL' }}</title>
    
    <!-- Bootstrap 5 CSS & Boxicons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --navy-dark: #0a1128;
            --navy-header: #070d1f;
            --green-primary: #00e676;
            --green-hover: #00c853;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #1e293b;
            background-color: #ffffff;
        }

        .navbar-custom {
            background-color: var(--navy-header);
            padding: 15px 0;
        }
        .nav-link-custom {
            color: #ffffff !important;
            font-weight: 600;
            font-size: 14px;
            padding: 8px 16px !important;
        }
        .nav-link-custom:hover, .nav-link-custom.active {
            color: var(--green-primary) !important;
        }
        .btn-login-green {
            background-color: var(--green-primary);
            color: #000000 !important;
            font-weight: 700;
            border-radius: 50px;
            padding: 10px 24px;
        }

        .hero-banner {
            position: relative;
            background-size: cover;
            background-position: center;
            min-height: 520px;
            display: flex;
            align-items: center;
            color: #ffffff;
        }
        .hero-overlay {
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: linear-gradient(90deg, rgba(10, 17, 40, 0.85) 0%, rgba(10, 17, 40, 0.4) 100%);
        }
        .text-green-highlight {
            color: var(--green-primary);
        }
        .badge-sub-green {
            color: var(--green-primary);
            font-weight: 800;
            font-size: 12px;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }

        .card-custom-rounded {
            border: none;
            border-radius: 20px;
            background: #ffffff;
        }
        .card-green-light {
            background-color: #f1f8f5;
            border-radius: 20px;
            border: none;
        }
        .icon-box-stat {
            width: 50px;
            height: 50px;
            background-color: #d1f7c4;
            color: #00a843;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        .footer-navy {
            background-color: var(--navy-dark);
            color: #a0aec0;
            padding-top: 60px;
            padding-bottom: 30px;
        }
    </style>
</head>
<body>

    <!-- NAVBAR DARK THEME -->
    <nav class="navbar navbar-expand-lg navbar-custom sticky-top shadow-sm">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="#">
                <span class="bg-primary rounded-circle p-2 text-white d-flex align-items-center justify-content-center" style="width:40px; height:40px;">
                    <i class="bx bxs-school fs-4"></i>
                </span>
                <span class="fw-extrabold text-white fs-5 text-uppercase">{{ $profile->nama_sekolah ?? 'SMA 1 CONTOH SMARTSCHOOL' }}</span>
            </a>
            
            <button class="navbar-toggler border-0 text-white" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <i class="bx bx-menu fs-1 text-white"></i>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item"><a class="nav-link nav-link-custom active" href="#beranda">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link nav-link-custom" href="#sambutan">Profil</a></li>
                    <li class="nav-item"><a class="nav-link nav-link-custom" href="#program">Program Keahlian</a></li>
                    <li class="nav-item"><a class="nav-link nav-link-custom" href="#info">Pengumuman</a></li>
                    <li class="nav-item"><a class="nav-link nav-link-custom" href="#galeri">Galeri</a></li>
                    <li class="nav-item"><a class="nav-link nav-link-custom" href="#kontak">Hubungi Kami</a></li>
                </ul>

                @auth
                    <a href="{{ Auth::user()->role == 'admin' ? route('admin.dashboard') : (Auth::user()->role == 'guru' ? route('guru.dashboard') : route('siswa.dashboard')) }}" class="btn btn-login-green fw-bold">
                        Dashboard <i class="bx bx-right-arrow-alt ms-1"></i>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-login-green fw-bold">
                        Login <i class="bx bx-right-arrow-alt ms-1"></i>
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- SECTION 1: HERO BANNER -->
    <section id="beranda" class="hero-banner" style="background-image: url('{{ isset($profile->foto_hero) ? asset('storage/' . $profile->foto_hero) : 'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?q=80&w=1200&auto=format&fit=crop' }}');">
        <div class="hero-overlay"></div>
        <div class="container position-relative py-5">
            <div class="row">
                <div class="col-lg-8">
                    <h1 class="display-3 fw-extrabold mb-3 text-white">
                        Contoh Web Sekolah <br>
                        <span class="text-green-highlight">{{ strtoupper($profile->nama_sekolah ?? 'SEKOLAHKITA.NET') }}</span>
                    </h1>
                    <p class="fs-5 text-light opacity-90 mb-4">
                        {{ $profile->slogan ?? 'Solusi digital komprehensif untuk sekolah modern, didukung oleh platform SIMAK.' }}
                    </p>
                    <div class="d-flex flex-wrap gap-3">
                        <a href="#sambutan" class="btn btn-success fw-bold rounded-pill px-4 py-3 shadow text-dark">
                            Jelajahi Profil <i class="bx bx-right-arrow-alt"></i>
                        </a>
                        <a href="#kontak" class="btn btn-outline-light fw-bold rounded-pill px-4 py-3">
                            Hubungi Kami <i class="bx bx-up-arrow-alt fs-5" style="transform: rotate(45deg);"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION 2: SAMBUTAN KEPALA SEKOLAH -->
    <section id="sambutan" class="py-5 bg-white">
        <div class="container py-4">
            <div class="row align-items-center g-5">
                <div class="col-md-4 text-center">
                    @if(isset($profile->foto_kepala_sekolah))
                        <img src="{{ asset('storage/' . $profile->foto_kepala_sekolah) }}" alt="Kepala Sekolah" class="img-fluid rounded-4 shadow-sm" style="max-height: 380px; object-fit: cover;">
                    @else
                        <img src="https://images.unsplash.com/photo-1560250097-0b93528c311a?q=80&w=400&auto=format&fit=crop" alt="Kepala Sekolah" class="img-fluid rounded-4 shadow-sm" style="max-height: 380px; object-fit: cover;">
                    @endif
                </div>
                <div class="col-md-8">
                    <div class="badge-sub-green mb-2"><i class="bx bx-plus me-1"></i> SAMBUTAN KEPALA SEKOLAH</div>
                    <h3 class="fw-extrabold text-dark lh-base mb-4 fs-3">
                        "{!! nl2br(e($profile->sambutan_kepala_sekolah ?? 'Salam Kreatifitas...!!! Website ini kami buat sebagai media komunikasi yang efektif dan efisien untuk menyampaikan berbagai informasi penting mengenai kegiatan akademik, ekstrakurikuler, pengumuman, serta berbagai prestasi yang telah diraih oleh sekolah kita tercinta.')) !!}"
                    </h3>
                    <div class="border-start border-4 border-success ps-3">
                        <h5 class="fw-bold mb-0 text-dark">{{ $profile->kepala_sekolah_nama ?? 'ERIK SANTOSA, M.cs, Phd' }}</h5>
                        <small class="text-green-highlight fw-bold text-uppercase">KEPALA SEKOLAH {{ $profile->nama_sekolah ?? 'SMA 1 CONTOH' }}</small>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION 3: PROFIL SINGKAT / DALAM ANGKA -->
    <section id="profil" class="py-5 bg-light">
        <div class="container py-4">
            <div class="row align-items-center g-5">
                <div class="col-lg-5">
                    <div class="badge-sub-green mb-2"><i class="bx bx-check me-1"></i> PROFIL SINGKAT</div>
                    <h2 class="fw-extrabold display-6 text-dark mb-3">
                        {{ $profile->nama_sekolah ?? 'SMA 1 CONTOH' }} <span class="text-green-highlight">Dalam Angka.</span>
                    </h2>
                    <p class="text-muted mb-4">
                        {{ $profile->sejarah_sekolah ?? 'Hadir sebagai pelopor pendidikan digital. Kami mengintegrasikan sistem pembelajaran modern yang efektif, transparan, dan real-time.' }}
                    </p>
                </div>

                <div class="col-lg-7">
                    <div class="row g-4">
                        <div class="col-6 col-md-6">
                            <div class="card card-custom-rounded p-4 shadow-sm d-flex flex-row align-items-center gap-3">
                                <div class="icon-box-stat"><i class="bx bx-user-voice"></i></div>
                                <div>
                                    <h2 class="fw-extrabold text-dark mb-0">{{ $totalSiswa }}</h2>
                                    <small class="text-muted fw-semibold">Total Siswa</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-md-6">
                            <div class="card card-custom-rounded p-4 shadow-sm d-flex flex-row align-items-center gap-3">
                                <div class="icon-box-stat"><i class="bx bx-chalkboard"></i></div>
                                <div>
                                    <h2 class="fw-extrabold text-dark mb-0">{{ $totalGuru }}</h2>
                                    <small class="text-muted fw-semibold">Guru & Tendik</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-md-6">
                            <div class="card card-custom-rounded p-4 shadow-sm d-flex flex-row align-items-center gap-3">
                                <div class="icon-box-stat"><i class="bx bx-book-open"></i></div>
                                <div>
                                    <h2 class="fw-extrabold text-dark mb-0">{{ $totalJurusan }}</h2>
                                    <small class="text-muted fw-semibold">Program Studi / Jurusan</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-md-6">
                            <div class="card card-custom-rounded p-4 shadow-sm d-flex flex-row align-items-center gap-3">
                                <div class="icon-box-stat"><i class="bx bx-grid-alt"></i></div>
                                <div>
                                    <h2 class="fw-extrabold text-dark mb-0">{{ $totalKelas }}</h2>
                                    <small class="text-muted fw-semibold">Rombel / Kelas</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION 4: PROGRAM KEAHLIAN / JURUSAN DINAMIS -->
    <section id="program" class="py-5 bg-white text-center">
        <div class="container py-4">
            <div class="badge-sub-green mb-2"><i class="bx bx-plus me-1"></i> KOMPETENSI KEAHLIAN</div>
            <h2 class="fw-extrabold text-dark display-6 mb-5">
                Program Keahlian yang Bisa <br><span class="text-green-highlight">Kamu Pilih</span>
            </h2>

            <div class="row justify-content-center g-4">
                @forelse($jurusans as $index => $j)
                <div class="col-md-4">
                    <div class="card card-green-light p-4 text-start position-relative shadow-sm h-100">
                        <div class="display-1 fw-extrabold text-green-highlight mb-3">{{ $index + 1 }}</div>
                        <span class="badge bg-success position-absolute top-0 end-0 m-4 rounded-circle p-2 d-flex align-items-center justify-content-center" style="width:35px; height:35px;">0{{ $index + 1 }}</span>
                        <h5 class="fw-bold text-dark">{{ $j->nama_jurusan }} ({{ $j->kode_jurusan }})</h5>
                        <p class="small text-muted mb-0">Selamat Datang di Program Keahlian {{ $j->nama_jurusan }} unggulan sekolah kami.</p>
                    </div>
                </div>
                @empty
                <div class="col-12 text-muted">Belum ada jurusan terdaftar.</div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- SECTION 5: PENGUMUMAN -->
    <section id="info" class="py-5 bg-light">
        <div class="container py-4">
            <h3 class="fw-extrabold text-dark mb-4">Pengumuman Terbaru</h3>
            <div class="row g-4">
                @forelse($pengumumans as $p)
                <div class="col-md-4">
                    <div class="card card-custom-rounded p-4 shadow-sm h-100">
                        <small class="text-success fw-bold mb-2 d-block"><i class="bx bx-calendar me-1"></i>{{ \Carbon\Carbon::parse($p->created_at)->format('d F Y') }}</small>
                        <h5 class="fw-bold text-dark mb-2">{{ $p->judul }}</h5>
                        <p class="small text-muted mb-0">{{ Str::limit($p->isi, 90) }}</p>
                    </div>
                </div>
                @empty
                <div class="col-12 text-muted">Belum ada pengumuman terbaru.</div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- SECTION 6: GALERI FOTO KEGIATAN DINAMIS -->
    <section id="galeri" class="py-5 bg-white">
        <div class="container py-4 text-center">
            <div class="badge-sub-green mb-2"><i class="bx bx-plus me-1"></i> KABAR SEKOLAH</div>
            <h2 class="fw-extrabold text-dark display-6 mb-5">
                Kabar dan <span class="text-green-highlight">Berita Terbaru</span> dari Sekolah.
            </h2>

            <div class="row g-4 text-start">
                @forelse($galeris as $g)
                <div class="col-md-4">
                    <div class="card card-custom-rounded border-0 shadow-sm overflow-hidden h-100">
                        <img src="{{ asset('storage/' . $g->foto) }}" class="card-img-top" style="height: 220px; object-fit: cover;">
                        <div class="card-body p-4">
                            <span class="badge bg-success mb-2">{{ $g->kategori }}</span>
                            <h6 class="fw-bold text-dark mb-0">{{ $g->judul }}</h6>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center text-muted">Belum ada foto galeri terdaftar. Tambahkan di dashboard Admin!</div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- FOOTER NAVY THEME -->
    <footer id="kontak" class="footer-navy">
        <div class="container">
            <div class="row g-4 mb-5">
                <div class="col-lg-4">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <span class="bg-primary rounded-circle p-2 text-white d-flex align-items-center justify-content-center" style="width:36px; height:36px;">
                            <i class="bx bxs-school fs-5"></i>
                        </span>
                        <span class="fw-extrabold text-white fs-5 text-uppercase">{{ $profile->nama_sekolah ?? 'SMA 1 CONTOH' }}</span>
                    </div>
                    <p class="small text-muted mb-4">Aplikasi Digitalisasi Akademik Terintegrasi.</p>
                </div>

                <div class="col-lg-4">
                    <h5 class="text-white fw-bold mb-3">Info Kontak</h5>
                    <p class="small mb-2"><i class="bx bx-phone me-2 text-success"></i> Telepon: <strong>{{ $profile->no_telepon ?? '1234567890' }}</strong></p>
                    <p class="small mb-2"><i class="bx bx-envelope me-2 text-success"></i> Email: <strong>{{ $profile->email_sekolah ?? 'admin@example.com' }}</strong></p>
                    <p class="small mb-2"><i class="bx bx-map me-2 text-success"></i> Alamat: {{ $profile->alamat_lengkap ?? 'Jl. Pendidikan Utama' }}</p>
                </div>

                <div class="col-lg-4">
                    <h5 class="text-white fw-bold mb-3">Portal Akses</h5>
                    <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm fw-bold">Login Sistem SIMAK</a>
                </div>
            </div>

            <hr class="border-secondary opacity-25 my-4">

            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center small text-muted">
                <div>© {{ date('Y') }} {{ $profile->nama_sekolah ?? 'SIMAK' }}. Hak cipta dilindungi.</div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>