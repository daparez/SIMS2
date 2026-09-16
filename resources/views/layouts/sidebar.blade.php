<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme shadow-sm">
    <!-- Brand / Logo Sekolah -->
    <div class="app-brand demo py-3">
        <a href="{{ url('/') }}" class="app-brand-link gap-2">
            <span class="app-brand-logo demo bg-primary rounded-3 p-2 d-flex align-items-center justify-content-center shadow-sm">
                <i class="bx bxs-school fs-3 text-white"></i>
            </span>
            <div class="d-flex flex-column">
                <span class="app-brand-text demo menu-text fw-bolder text-uppercase fs-5 lh-1 text-primary">SIMAK</span>
                <small class="text-muted fw-semibold" style="font-size: 10px; letter-spacing: 0.5px;">SISTEM AKADEMIK</small>
            </div>
        </a>

        <!-- TOMBOL TOGGLE BUKA-TUTUP SIDEBAR -->
        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-2">

        <!-- ========================================== -->
        <!-- MENU KHUSUS ADMIN                          -->
        <!-- ========================================== -->
        @if(Auth::check() && Auth::user()->role === 'admin')
            <!-- Dashboard Admin -->
            <li class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <a href="{{ route('admin.dashboard') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-home-circle"></i>
                    <div>Dashboard</div>
                </a>
            </li>

            <li class="menu-header small text-uppercase">
                <span class="menu-header-text">Master Data</span>
            </li>

            <!-- Sub-menu Master Akademik -->
            <li class="menu-item {{ request()->is('admin/tahun-ajaran*', 'admin/jurusan*', 'admin/kelas*', 'admin/mapel*') ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-collection"></i>
                    <div>Master Akademik</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item {{ request()->routeIs('admin.tahun-ajaran.*') ? 'active' : '' }}">
                        <a href="{{ route('admin.tahun-ajaran.index') }}" class="menu-link">
                            <div>Tahun Ajaran</div>
                        </a>
                    </li>
                    <li class="menu-item {{ request()->routeIs('admin.jurusan.*') ? 'active' : '' }}">
                        <a href="{{ route('admin.jurusan.index') }}" class="menu-link">
                            <div>Data Jurusan</div>
                        </a>
                    </li>
                    <li class="menu-item {{ request()->routeIs('admin.kelas.*') ? 'active' : '' }}">
                        <a href="{{ route('admin.kelas.index') }}" class="menu-link">
                            <div>Data Kelas</div>
                        </a>
                    </li>
                    <li class="menu-item {{ request()->routeIs('admin.mapel.*') ? 'active' : '' }}">
                        <a href="{{ route('admin.mapel.index') }}" class="menu-link">
                            <div>Mata Pelajaran</div>
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Data Siswa -->
            <li class="menu-item {{ request()->routeIs('admin.siswa.*') ? 'active' : '' }}">
                <a href="{{ route('admin.siswa.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-user"></i>
                    <div>Data Siswa</div>
                </a>
            </li>

            <!-- Data Guru -->
            <li class="menu-item {{ request()->routeIs('admin.guru.*') ? 'active' : '' }}">
                <a href="{{ route('admin.guru.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-user-voice"></i>
                    <div>Data Guru</div>
                </a>
            </li>

            <li class="menu-header small text-uppercase">
                <span class="menu-header-text">Operasional & Informasi</span>
            </li>

            <!-- Modul Jadwal Pelajaran -->
            <li class="menu-item {{ request()->is('admin/jadwal*') ? 'active' : '' }}">
                <a href="{{ route('admin.jadwal.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-calendar-event"></i>
                    <div>Jadwal Pelajaran</div>
                </a>
            </li>

            <!-- Modul Sarana & Prasarana (Inventaris) -->
            <li class="menu-item {{ request()->is('admin/ruangan*', 'admin/barang*') ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-building-house"></i>
                    <div>Sarana & Prasarana</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item {{ request()->routeIs('admin.ruangan.*') ? 'active' : '' }}">
                        <a href="{{ route('admin.ruangan.index') }}" class="menu-link">
                            <div>Master Ruangan</div>
                        </a>
                    </li>
                    <li class="menu-item {{ request()->routeIs('admin.barang.*') ? 'active' : '' }}">
                        <a href="{{ route('admin.barang.index') }}" class="menu-link">
                            <div>Master Barang & Aset</div>
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Modul Absensi QR Code -->
            <li class="menu-item {{ request()->is('admin/absensi*') ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-qr-scan"></i>
                    <div>Absensi QR Code</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item {{ request()->routeIs('admin.absensi.scan') ? 'active' : '' }}">
                        <a href="{{ route('admin.absensi.scan') }}" class="menu-link">
                            <div>Scan QR Code</div>
                        </a>
                    </li>
                    <li class="menu-item {{ request()->routeIs('admin.absensi.index') ? 'active' : '' }}">
                        <a href="{{ route('admin.absensi.index') }}" class="menu-link">
                            <div>Rekap Harian</div>
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Menu Keuangan & SPP -->
            <li class="menu-item {{ request()->is('admin/keuangan*') ? 'active' : '' }}">
                <a href="{{ route('admin.keuangan.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-wallet"></i>
                    <div>Keuangan & SPP</div>
                </a>
            </li>

            <!-- Menu Pengumuman Sekolah -->
            <li class="menu-item {{ request()->is('admin/pengumuman*') ? 'active' : '' }}">
                <a href="{{ route('admin.pengumuman.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-megaphone"></i>
                    <div>Pengumuman</div>
                </a>
            </li>

            <!-- Cetak Kartu Pelajar -->
            <li class="menu-item {{ request()->is('admin/kartu-pelajar*') ? 'active' : '' }}">
                <a href="{{ route('admin.kartu.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-id-card"></i>
                    <div>Cetak Kartu Pelajar</div>
                </a>
            </li>

            <li class="menu-header small text-uppercase">
                <span class="menu-header-text">Pengaturan Sistem</span>
            </li>

            <!-- Menu Kelola Landing Page (Welcome) -->
            <li class="menu-item {{ request()->routeIs('admin.setting.landing') ? 'active' : '' }}">
                <a href="{{ route('admin.setting.landing') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-globe text-primary"></i>
                    <div class="fw-bold text-primary">Kelola Landing Page</div>
                </a>
            </li>

            <!-- Menu Kelola Hak Akses User -->
            <li class="menu-item {{ request()->is('admin/hak-akses*') ? 'active' : '' }}">
                <a href="{{ route('admin.hak-akses.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-shield-quarter text-warning"></i>
                    <div class="fw-bold">Hak Akses User</div>
                </a>
            </li>

            <li class="menu-item {{ request()->is('admin/setting-lokasi*') ? 'active' : '' }}">
                <a href="{{ route('admin.setting.lokasi') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-map-alt"></i>
                    <div>Atur Lokasi Sekolah</div>
                </a>
            </li>

            <li class="menu-item {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                <a href="{{ route('profile.edit') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-cog"></i>
                    <div>Pengaturan Profil</div>
                </a>
            </li>
        @endif

        <!-- ========================================== -->
        <!-- MENU KHUSUS GURU                          -->
        <!-- ========================================== -->
        @if(Auth::check() && Auth::user()->role === 'guru')
            <li class="menu-item {{ request()->routeIs('guru.dashboard') ? 'active' : '' }}">
                <a href="{{ route('guru.dashboard') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-home-circle"></i>
                    <div>Dashboard Guru</div>
                </a>
            </li>

            <li class="menu-header small text-uppercase">
                <span class="menu-header-text">Akademik & Kelas</span>
            </li>
            <li class="menu-item">
                <a href="#" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-calendar-event"></i>
                    <div>Jadwal Mengajar</div>
                </a>
            </li>
            <li class="menu-item">
                <a href="#" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-edit-alt"></i>
                    <div>Input Nilai Siswa</div>
                </a>
            </li>

            <!-- HAK AKSES DINAMIS TAMBAHAN UNTUK GURU -->
            @if(Auth::user()->hasPermission('scan_absensi') || Auth::user()->hasPermission('rekap_absensi') || Auth::user()->hasPermission('kelola_pengumuman') || Auth::user()->hasPermission('kelola_jadwal') || Auth::user()->hasPermission('kelola_sarpras') || Auth::user()->hasPermission('kelola_landing'))
                <li class="menu-header small text-uppercase">
                    <span class="menu-header-text">Akses Khusus / Tambahan</span>
                </li>

                @if(Auth::user()->hasPermission('kelola_landing'))
                <li class="menu-item {{ request()->routeIs('admin.setting.landing') ? 'active' : '' }}">
                    <a href="{{ route('admin.setting.landing') }}" class="menu-link">
                        <i class="menu-icon tf-icons bx bx-globe text-primary"></i>
                        <div class="fw-bold text-primary">Kelola Landing Page</div>
                    </a>
                </li>
                @endif

                @if(Auth::user()->hasPermission('kelola_sarpras'))
                <li class="menu-item {{ request()->is('admin/ruangan*', 'admin/barang*') ? 'active open' : '' }}">
                    <a href="javascript:void(0);" class="menu-link menu-toggle">
                        <i class="menu-icon tf-icons bx bx-building-house text-primary"></i>
                        <div class="fw-bold text-primary">Kelola Sarpras</div>
                    </a>
                    <ul class="menu-sub">
                        <li class="menu-item {{ request()->routeIs('admin.ruangan.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.ruangan.index') }}" class="menu-link">
                                <div>Master Ruangan</div>
                            </a>
                        </li>
                        <li class="menu-item {{ request()->routeIs('admin.barang.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.barang.index') }}" class="menu-link">
                                <div>Master Barang & Aset</div>
                            </a>
                        </li>
                    </ul>
                </li>
                @endif

                @if(Auth::user()->hasPermission('kelola_jadwal'))
                <li class="menu-item {{ request()->is('admin/jadwal*') ? 'active' : '' }}">
                    <a href="{{ route('admin.jadwal.index') }}" class="menu-link">
                        <i class="menu-icon tf-icons bx bx-calendar-event text-primary"></i>
                        <div class="fw-bold text-primary">Kelola Jadwal</div>
                    </a>
                </li>
                @endif

                @if(Auth::user()->hasPermission('scan_absensi'))
                <li class="menu-item {{ request()->routeIs('admin.absensi.scan') ? 'active' : '' }}">
                    <a href="{{ route('admin.absensi.scan') }}" class="menu-link">
                        <i class="menu-icon tf-icons bx bx-qr-scan text-primary"></i>
                        <div class="fw-bold text-primary">Scan QR Absensi</div>
                    </a>
                </li>
                @endif

                @if(Auth::user()->hasPermission('rekap_absensi'))
                <li class="menu-item {{ request()->routeIs('admin.absensi.index') ? 'active' : '' }}">
                    <a href="{{ route('admin.absensi.index') }}" class="menu-link">
                        <i class="menu-icon tf-icons bx bx-list-check"></i>
                        <div>Rekap Absensi</div>
                    </a>
                </li>
                @endif

                @if(Auth::user()->hasPermission('kelola_pengumuman'))
                <li class="menu-item {{ request()->is('admin/pengumuman*') ? 'active' : '' }}">
                    <a href="{{ route('admin.pengumuman.index') }}" class="menu-link">
                        <i class="menu-icon tf-icons bx bx-megaphone"></i>
                        <div>Pengumuman Sekolah</div>
                    </a>
                </li>
                @endif
            @endif

            <li class="menu-header small text-uppercase">
                <span class="menu-header-text">Pengaturan</span>
            </li>
            <li class="menu-item {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                <a href="{{ route('profile.edit') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-user-circle"></i>
                    <div>Pengaturan Profil</div>
                </a>
            </li>
        @endif

        <!-- ========================================== -->
        <!-- MENU KHUSUS SISWA                          -->
        <!-- ========================================== -->
        @if(Auth::check() && Auth::user()->role === 'siswa')
            <li class="menu-item {{ request()->routeIs('siswa.dashboard') ? 'active' : '' }}">
                <a href="{{ route('siswa.dashboard') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-grid-alt"></i>
                    <div>Dashboard Saya</div>
                </a>
            </li>

            <li class="menu-header small text-uppercase">
                <span class="menu-header-text">Informasi Akademik</span>
            </li>
            <li class="menu-item">
                <a href="#" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-file"></i>
                    <div>Nilai & Rapor</div>
                </a>
            </li>

            <!-- HAK AKSES DINAMIS TAMBAHAN UNTUK SISWA -->
            @if(Auth::user()->hasPermission('scan_absensi') || Auth::user()->hasPermission('cetak_kartu'))
                <li class="menu-header small text-uppercase">
                    <span class="menu-header-text">Akses Khusus Petugas</span>
                </li>

                @if(Auth::user()->hasPermission('scan_absensi'))
                <li class="menu-item {{ request()->routeIs('admin.absensi.scan') ? 'active' : '' }}">
                    <a href="{{ route('admin.absensi.scan') }}" class="menu-link">
                        <i class="menu-icon tf-icons bx bx-qr-scan text-primary"></i>
                        <div class="fw-bold text-primary">Scan QR Absensi</div>
                    </a>
                </li>
                @endif

                @if(Auth::user()->hasPermission('cetak_kartu'))
                <li class="menu-item {{ request()->is('admin/kartu-pelajar*') ? 'active' : '' }}">
                    <a href="{{ route('admin.kartu.index') }}" class="menu-link">
                        <i class="menu-icon tf-icons bx bx-id-card"></i>
                        <div>Cetak Kartu Pelajar</div>
                    </a>
                </li>
                @endif
            @endif

            <li class="menu-header small text-uppercase">
                <span class="menu-header-text">Pengaturan</span>
            </li>
            <li class="menu-item {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                <a href="{{ route('profile.edit') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-user-circle"></i>
                    <div>Pengaturan Profil</div>
                </a>
            </li>
            
        @endif

    </ul>

    <!-- ========================================== -->
    <!-- CARD TOMBOL LOGOUT (STICKY AT BOTTOM)      -->
    <!-- ========================================== -->
    <div class="p-3 border-top mt-auto">
        <div class="card bg-label-danger border-0 shadow-none mb-0">
            <div class="card-body p-2 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <div class="avatar avatar-xs me-2">
                        <span class="avatar-initial rounded bg-danger text-white">
                            <i class="bx bx-power-off fs-6"></i>
                        </span>
                    </div>
                    <div>
                        <small class="fw-bold d-block text-danger" style="font-size: 11px; line-height: 1.2;">Sesi Login</small>
                        <small class="text-muted" style="font-size: 10px;">{{ ucfirst(Auth::user()->role ?? 'User') }}</small>
                    </div>
                </div>
                <a href="javascript:void(0);" 
                   class="btn btn-sm btn-danger p-1 px-2 text-white" 
                   title="Keluar dari Sistem"
                   onclick="event.preventDefault(); document.getElementById('logout-form-sidebar').submit();">
                    <i class="bx bx-log-out fs-6 align-middle me-1"></i> Keluar
                </a>
            </div>
        </div>
        <form id="logout-form-sidebar" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>
    </div>
</aside>