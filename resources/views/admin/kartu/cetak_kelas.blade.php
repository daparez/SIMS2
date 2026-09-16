<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Kartu Pelajar Kelas {{ $kelas }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css">
    <style>
        body {
            background-color: #eef2f6;
            font-family: 'Public Sans', -apple-system, Roboto, sans-serif;
            padding-bottom: 50px;
        }

        /* Ukuran Standar Kartu Pelajar CR80 (85.6mm x 53.9mm) */
        .id-card-wrapper {
            width: 85.6mm;
            height: 53.9mm;
            border-radius: 10px;
            overflow: hidden;
            background: #ffffff;
            position: relative;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            border: 1px solid #dcdfe6;
            page-break-inside: avoid;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        /* ================= DESAIN SISI DEPAN ================= */
        .card-front-header {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: white;
            padding: 6px 10px;
            display: flex;
            align-items: center;
            border-bottom: 2px solid #f39c12;
        }

        .card-front-header img {
            width: 26px;
            height: 26px;
            object-fit: contain;
            margin-right: 8px;
        }

        .card-front-header .school-title {
            line-height: 1.1;
        }

        .card-front-header .school-title h6 {
            margin: 0;
            font-size: 9.5px;
            font-weight: 800;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .card-front-header .school-title small {
            font-size: 7px;
            opacity: 0.9;
            display: block;
        }

        .card-front-body {
            padding: 8px 10px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: relative;
            flex-grow: 1;
            background: radial-gradient(circle at center, #ffffff 60%, #f4f7fa 100%);
        }

        .photo-box {
            width: 22mm;
            height: 29mm;
            border-radius: 4px;
            border: 2px solid #2a5298;
            object-fit: cover;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
            background: #e9ecef;
        }

        .student-details {
            font-size: 8.5px;
            line-height: 1.35;
            flex-grow: 1;
            padding-left: 10px;
            color: #2c3e50;
        }

        .student-details .name-tag {
            font-size: 10.5px;
            font-weight: 800;
            color: #1e3c72;
            margin-bottom: 3px;
            text-transform: uppercase;
            border-bottom: 1px solid #1e3c72;
            padding-bottom: 1px;
            max-width: 130px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .qr-box {
            text-align: center;
            margin-left: 4px;
        }

        .qr-box img {
            width: 18mm;
            height: 18mm;
            border: 1px solid #ddd;
            padding: 2px;
            background: #fff;
            border-radius: 4px;
        }

        .card-front-footer {
            background: #1e3c72;
            color: #fff;
            font-size: 6.5px;
            text-align: center;
            padding: 2px 0;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        /* ================= DESAIN SISI BELAKANG ================= */
        .card-back-header {
            background: #1e3c72;
            color: #fff;
            padding: 4px 8px;
            text-align: center;
            font-size: 8px;
            font-weight: bold;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #f39c12;
        }

        .card-back-body {
            padding: 6px 10px;
            font-size: 6.5px;
            color: #333;
            line-height: 1.3;
        }

        .rules-list {
            padding-left: 12px;
            margin-bottom: 4px;
        }

        .rules-list li {
            margin-bottom: 2px;
        }

        .signature-box {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-top: 2px;
            padding-top: 2px;
        }

        .stamp-area {
            font-size: 6px;
            text-align: center;
            color: #555;
        }

        .ttd-title {
            font-size: 6.5px;
            text-align: center;
        }

        .ttd-space {
            height: 12px;
        }

        .ttd-name {
            font-weight: bold;
            text-decoration: underline;
            font-size: 6.5px;
            text-align: center;
        }

        /* STYLING KHUSUS TAMPILAN CETAK / PRINT */
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background: white !important;
                padding: 0 !important;
            }
            .container-print {
                width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
            }
            .id-card-wrapper {
                box-shadow: none !important;
                border: 1px solid #aaa !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            .modal-backdrop, .modal {
                display: none !important;
            }
        }
    </style>
</head>
<body>

    <!-- NAVIGASI KONTROL (TIDAK TERCETAK) -->
    <div class="no-print bg-white border-bottom p-3 mb-4 sticky-top shadow-sm">
        <div class="container d-flex justify-content-between align-items-center">
            <div>
                <h5 class="fw-bold mb-0 text-primary"><i class="bx bx-id-card me-2"></i>Kartu Pelajar - Kelas {{ $kelas }}</h5>
                <small class="text-muted">Total {{ $siswas->count() }} Siswa Terdaftar</small>
            </div>
            <div class="d-flex gap-2">
                <button onclick="window.print()" class="btn btn-primary fw-bold">
                    <i class="bx bx-printer me-1"></i> Cetak Massal (Depan)
                </button>
                <button onclick="window.close()" class="btn btn-outline-secondary">
                    <i class="bx bx-x me-1"></i> Tutup
                </button>
            </div>
        </div>
    </div>

    <!-- KONTEN KARTU SISWA -->
    <div class="container container-print">
        <div class="row g-4 justify-content-start">
            @foreach($siswas as $s)
            <div class="col-auto">
                <div class="d-flex flex-column align-items-center">
                    
                    <!-- KARTU SISI DEPAN -->
                    <div class="id-card-wrapper" id="card-front-{{ $s->id }}">
                        <!-- Header Kartu -->
                        <div class="card-front-header">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/9/9c/Logo_Tut_Wuri_Handayani.png" alt="Logo">
                            <div class="school-title">
                                <h6>{{ $setting->nama_sekolah ?? 'SMA / SMK NEGERI SEKOLAH' }}</h6>
                                <small>KARTU TANDA PELAJAR DIGITAL</small>
                            </div>
                        </div>

                        <!-- Body Kartu -->
                        <div class="card-front-body">
                            <!-- Foto 3x4 -->
                            @if($s->foto)
                                <img src="{{ asset('storage/' . $s->foto) }}" class="photo-box" alt="Foto">
                            @else
                                <div class="photo-box d-flex align-items-center justify-content-center text-muted fw-bold" style="font-size: 8px;">
                                    FOTO 3x4
                                </div>
                            @endif

                            <!-- Biodata Siswa -->
                            <div class="student-details">
                                <div class="name-tag" title="{{ $s->nama_lengkap }}">{{ $s->nama_lengkap }}</div>
                                <div><strong>NISN:</strong> {{ $s->nisn }}</div>
                                <div><strong>NIS:</strong> {{ $s->nis ?? '-' }}</div>
                                <div><strong>Kelas:</strong> {{ $s->kelas }} {{ $s->jurusan ? '('.$s->jurusan.')' : '' }}</div>
                                <div><strong>L/P:</strong> {{ $s->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</div>
                            </div>

                            <!-- QR Code Absensi -->
                            <div class="qr-box">
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data={{ $s->nisn }}" alt="QR">
                                <div style="font-size: 6px; font-weight: bold; margin-top: 2px;" class="text-primary">SCAN ABSEN</div>
                            </div>
                        </div>

                        <!-- Footer Kartu -->
                        <div class="card-front-footer">
                            KARTU INI SAH SEBAGAI BUKTI IDENTITAS SISWA AKTIF
                        </div>
                    </div>

                    <!-- TOMBOL DETAIL 2 SISI (NO PRINT) -->
                    <div class="no-print mt-2">
                        <button type="button" class="btn btn-xs btn-sm btn-outline-primary fw-semibold" data-bs-toggle="modal" data-bs-target="#modalCardDetail{{ $s->id }}">
                            <i class="bx bx-show me-1"></i> Detail 2 Sisi
                        </button>
                    </div>

                </div>
            </div>

            <!-- MODAL POPUP PRATINJAU & CETAK 2 SISI (DEPAN & BELAKANG) -->
            <div class="modal fade no-print" id="modalCardDetail{{ $s->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-header border-bottom">
                            <h6 class="modal-title fw-bold"><i class="bx bx-id-card text-primary me-2"></i>Kartu Pelajar 2 Sisi - {{ $s->nama_lengkap }}</h6>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body bg-light py-4">
                            <div class="row g-4 justify-content-center">
                                
                                <!-- SISI DEPAN -->
                                <div class="col-auto text-center">
                                    <span class="badge bg-primary mb-2">Tampak Depan</span>
                                    <div class="id-card-wrapper mx-auto">
                                        <div class="card-front-header">
                                            <img src="https://upload.wikimedia.org/wikipedia/commons/9/9c/Logo_Tut_Wuri_Handayani.png" alt="Logo">
                                            <div class="school-title">
                                                <h6>{{ $setting->nama_sekolah ?? 'SMA / SMK NEGERI SEKOLAH' }}</h6>
                                                <small>KARTU TANDA PELAJAR DIGITAL</small>
                                            </div>
                                        </div>
                                        <div class="card-front-body">
                                            @if($s->foto)
                                                <img src="{{ asset('storage/' . $s->foto) }}" class="photo-box" alt="Foto">
                                            @else
                                                <div class="photo-box d-flex align-items-center justify-content-center text-muted fw-bold" style="font-size: 8px;">FOTO 3x4</div>
                                            @endif
                                            <div class="student-details text-start">
                                                <div class="name-tag">{{ $s->nama_lengkap }}</div>
                                                <div><strong>NISN:</strong> {{ $s->nisn }}</div>
                                                <div><strong>NIS:</strong> {{ $s->nis ?? '-' }}</div>
                                                <div><strong>Kelas:</strong> {{ $s->kelas }}</div>
                                                <div><strong>L/P:</strong> {{ $s->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</div>
                                            </div>
                                            <div class="qr-box">
                                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data={{ $s->nisn }}" alt="QR">
                                                <div style="font-size: 6px; font-weight: bold;" class="text-primary mt-1">SCAN ABSEN</div>
                                            </div>
                                        </div>
                                        <div class="card-front-footer">
                                            KARTU INI SAH SEBAGAI BUKTI IDENTITAS SISWA AKTIF
                                        </div>
                                    </div>
                                </div>

                                <!-- SISI BELAKANG -->
                                <div class="col-auto text-center">
                                    <span class="badge bg-secondary mb-2">Tampak Belakang (Tata Tertib)</span>
                                    <div class="id-card-wrapper mx-auto text-start">
                                        <div class="card-back-header">
                                            TATA TERTIB & KETENTUAN KARTU
                                        </div>
                                        <div class="card-back-body d-flex flex-column justify-content-between h-100">
                                            <ol class="rules-list">
                                                <li>Kartu ini wajib dibawa setiap hari selama kegiatan sekolah berlangsung.</li>
                                                <li>Gunakan QR Code pada kartu untuk melakukan scan presensi harian.</li>
                                                <li>Kartu ini tidak boleh dipindahtangankan kepada orang lain.</li>
                                                <li>Apabila kartu hilang/rusak, segera lapor ke bagian Tata Usaha Sekolah.</li>
                                            </ol>

                                            <div class="signature-box mt-1">
                                                <div class="stamp-area">
                                                    <small>Cap Stempel Sekolah</small>
                                                </div>
                                                <div>
                                                    <div class="ttd-title">Kepala Sekolah,</div>
                                                    <div class="ttd-space"></div>
                                                    <div class="ttd-name">Dr. H. Kepala Sekolah, M.Pd</div>
                                                    <div style="font-size: 5.5px; text-align: center;">NIP. 19780101 200501 1 002</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-front-footer" style="background: #333;">
                                            WWW.SEKOLAH.SCH.ID
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="modal-footer border-top">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>