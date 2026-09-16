@extends('layouts.app')

@section('title', 'Scan QR Code Absensi - SIMAK')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold py-1 mb-0"><span class="text-muted fw-light">Absensi /</span> Scan QR Code Harian</h4>
    <a href="{{ route('admin.absensi.index') }}" class="btn btn-secondary fw-bold shadow-sm">
        <i class="bx bx-list-ul me-1"></i> Rekap Absensi
    </a>
</div>

<!-- Widget Informasi Jam Masuk & Jam Pulang (Dinamis Sesuai Setting Lokasi) -->
<div class="row mb-4">
    <div class="col-md-6 mb-2">
        <div class="card bg-label-primary border-0 shadow-sm">
            <div class="card-body py-3 d-flex align-items-center">
                <div class="avatar avatar-md me-3">
                    <span class="avatar-initial rounded-circle bg-primary text-white"><i class="bx bx-time-five fs-4"></i></span>
                </div>
                <div>
                    <small class="text-muted d-block fw-semibold">Batas Jam Masuk</small>
                    <h5 class="mb-0 fw-bold text-primary">
                        {{ isset($setting->jam_masuk) ? \Carbon\Carbon::parse($setting->jam_masuk)->format('H:i') : '07:30' }} WIB 
                        <span class="badge bg-primary ms-1" style="font-size: 10px;">Toleransi: +{{ $setting->toleransi_terlambat_menit ?? 10 }} Mnt</span>
                    </h5>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-2">
        <div class="card bg-label-success border-0 shadow-sm">
            <div class="card-body py-3 d-flex align-items-center">
                <div class="avatar avatar-md me-3">
                    <span class="avatar-initial rounded-circle bg-success text-white"><i class="bx bx-log-out-circle fs-4"></i></span>
                </div>
                <div>
                    <small class="text-muted d-block fw-semibold">Mulai Jam Pulang</small>
                    <h5 class="mb-0 fw-bold text-success">
                        {{ isset($setting->jam_pulang) ? \Carbon\Carbon::parse($setting->jam_pulang)->format('H:i') : '14:30' }} WIB
                    </h5>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Area Scanner Kamera -->
    <div class="col-md-6 mb-4">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-header d-flex align-items-center justify-content-between bg-white border-bottom">
                <h5 class="card-title m-0 text-dark fw-bold"><i class="bx bx-qr-scan text-primary me-2 fs-4"></i>Kamera Scanner</h5>
                <span class="badge bg-label-primary" id="scan-status">Mendeteksi Lokasi...</span>
            </div>
            <div class="card-body text-center p-4">
                <!-- Status GPS Indicator -->
                <div id="gps-info" class="alert alert-info py-2 px-3 mb-3 small d-flex align-items-center justify-content-center border-0 shadow-none">
                    <i class="bx bx-map-pin me-2 fs-5"></i>
                    <span id="gps-text">Sedang mengambil titik koordinat GPS...</span>
                </div>

                <!-- Preview Kamera -->
                <div id="reader" style="width: 100%; max-width: 450px; margin: 0 auto; border-radius: 12px; overflow: hidden; background: #f8f9fa;"></div>
                <p class="text-muted small mt-3 mb-0">
                    Arahkan QR Code Kartu Pelajar Siswa ke depan kamera scanner.
                </p>
            </div>
        </div>
    </div>

    <!-- Area Status & Hasil Scan -->
    <div class="col-md-6 mb-4">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-header bg-white border-bottom">
                <h5 class="card-title m-0 text-dark fw-bold"><i class="bx bx-info-circle text-info me-2 fs-4"></i>Status Hasil Pemindaian</h5>
            </div>
            <div class="card-body d-flex flex-column justify-content-center align-items-center text-center p-4" id="result-box">
                
                <!-- Tampilan Default Sebelum Scan -->
                <div id="default-state" class="py-5">
                    <i class="bx bx-barcode-reader display-1 text-muted mb-3 d-block"></i>
                    <h5 class="text-muted fw-bold">Menunggu QR Code Dipindai...</h5>
                    <small class="text-muted">Hasil konfirmasi presensi akan muncul otomatis di sini.</small>
                </div>

                <!-- Tampilan Hasil Sukses/Gagal (Hidden Default) -->
                <div id="scan-result" class="d-none w-100 p-4 rounded shadow-sm">
                    <div id="result-icon" class="mb-2"></div>
                    <h4 id="result-title" class="fw-bold mb-1"></h4>
                    <p id="result-message" class="mb-3 fs-6"></p>

                    <div id="siswa-detail" class="card bg-white border-0 d-none text-start p-3 shadow-sm">
                        <div class="d-flex align-items-center">
                            <div class="avatar avatar-md me-3">
                                <span class="avatar-initial rounded-circle bg-primary fs-5" id="siswa-initial">--</span>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold text-dark" id="siswa-nama">-</h6>
                                <small class="text-muted d-block" id="siswa-kelas">-</small>
                                <small class="text-muted d-block" id="siswa-nisn">-</small>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Import Library HTML5 QR Code -->
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

<script>
    let html5QrcodeScanner;
    let isProcessing = false;
    let userLat = null;
    let userLon = null;

    // 1. Ambil Titik Koordinat GPS Browser/HP
    function getGPSLocation() {
        const gpsText = document.getElementById('gps-text');
        const gpsInfo = document.getElementById('gps-info');
        const scanStatus = document.getElementById('scan-status');

        if (navigator.geolocation) {
            navigator.geolocation.watchPosition(
                function (position) {
                    userLat = position.coords.latitude;
                    userLon = position.coords.longitude;

                    gpsInfo.className = "alert alert-success py-2 px-3 mb-3 small d-flex align-items-center justify-content-center border-0 shadow-none";
                    gpsText.innerHTML = `<strong>GPS Aktif:</strong> Lat: ${userLat.toFixed(5)}, Lon: ${userLon.toFixed(5)}`;
                    scanStatus.innerText = 'Kamera Siap';
                    scanStatus.className = 'badge bg-label-primary';
                },
                function (error) {
                    gpsInfo.className = "alert alert-danger py-2 px-3 mb-3 small d-flex align-items-center justify-content-center border-0 shadow-none";
                    gpsText.innerText = "Gagal mengambil lokasi! Harap izinkan akses lokasi (GPS) di browser Anda.";
                    scanStatus.innerText = 'GPS Ditolak';
                    scanStatus.className = 'badge bg-label-danger';
                },
                {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 0
                }
            );
        } else {
            gpsInfo.className = "alert alert-danger py-2 px-3 mb-3 small d-flex align-items-center justify-content-center border-0 shadow-none";
            gpsText.innerText = "Browser Anda tidak mendukung fitur Geolocation/GPS.";
        }
    }

    // 2. Callback Fungsi saat Kamera QR Scanner Berhasil Membaca QR Code
    function onScanSuccess(decodedText, decodedResult) {
        if (isProcessing) return;

        // Validasi Ketersediaan GPS
        if (!userLat || !userLon) {
            alert("Akses lokasi GPS belum terdeteksi. Izinkan izin lokasi di browser Anda lalu coba scan kembali!");
            return;
        }

        isProcessing = true;
        document.getElementById('scan-status').innerText = 'Memproses...';
        document.getElementById('scan-status').className = 'badge bg-label-warning';

        // Kirim NISN + Koordinat GPS ke Server
        fetch("{{ route('admin.absensi.scan-process') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({
                qr_code: decodedText,
                latitude: userLat,
                longitude: userLon
            })
        })
        .then(response => response.json())
        .then(data => {
            showResult(data);
        })
        .catch(error => {
            showResult({
                status: 'error',
                message: 'Terjadi kesalahan sistem atau Anda berada di luar jangkauan area sekolah.'
            });
        })
        .finally(() => {
            setTimeout(() => {
                isProcessing = false;
                document.getElementById('scan-status').innerText = 'Kamera Siap';
                document.getElementById('scan-status').className = 'badge bg-label-primary';
            }, 3000);
        });
    }

    // 3. Tampilkan Notifikasi Hasil Pemindaian
    function showResult(data) {
        document.getElementById('default-state').classList.add('d-none');
        const resultBox = document.getElementById('scan-result');
        const resultIcon = document.getElementById('result-icon');
        const resultTitle = document.getElementById('result-title');
        const resultMessage = document.getElementById('result-message');
        const siswaDetail = document.getElementById('siswa-detail');

        resultBox.classList.remove('d-none');

        if (data.status === 'success') {
            resultBox.className = 'w-100 p-4 rounded bg-label-success shadow-sm';
            resultIcon.innerHTML = '<i class="bx bx-check-circle display-3 text-success"></i>';
            resultTitle.className = 'fw-bold mb-1 text-success';
            resultTitle.innerText = data.type === 'masuk' ? 'Absen Masuk Berhasil!' : 'Absen Pulang Berhasil!';
            resultMessage.innerText = data.message;

            if (data.siswa) {
                siswaDetail.classList.remove('d-none');
                document.getElementById('siswa-nama').innerText = data.siswa.nama_lengkap;
                document.getElementById('siswa-kelas').innerText = "Kelas: " + data.siswa.kelas;
                document.getElementById('siswa-nisn').innerText = "NISN: " + data.siswa.nisn;
                document.getElementById('siswa-initial').innerText = data.siswa.nama_lengkap.substring(0, 2).toUpperCase();
            }
        } else if (data.status === 'warning') {
            resultBox.className = 'w-100 p-4 rounded bg-label-warning shadow-sm';
            resultIcon.innerHTML = '<i class="bx bx-error-circle display-3 text-warning"></i>';
            resultTitle.className = 'fw-bold mb-1 text-warning';
            resultTitle.innerText = 'Peringatan!';
            resultMessage.innerText = data.message;
            siswaDetail.classList.add('d-none');
        } else {
            resultBox.className = 'w-100 p-4 rounded bg-label-danger shadow-sm';
            resultIcon.innerHTML = '<i class="bx bx-x-circle display-3 text-danger"></i>';
            resultTitle.className = 'fw-bold mb-1 text-danger';
            resultTitle.innerText = 'Absensi Ditolak!';
            resultMessage.innerText = data.message;
            siswaDetail.classList.add('d-none');
        }
    }

    // Initialize saat halaman dimuat
    document.addEventListener("DOMContentLoaded", function () {
        getGPSLocation();

        html5QrcodeScanner = new Html5QrcodeScanner(
            "reader", 
            { 
                fps: 10, 
                qrbox: { width: 250, height: 250 } 
            },
            false
        );
        html5QrcodeScanner.render(onScanSuccess);
    });
</script>
@endsection