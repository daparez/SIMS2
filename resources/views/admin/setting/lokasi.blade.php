@extends('layouts.app')

@section('title', 'Pengaturan Lokasi & Jam Absensi - Admin')

<!-- Import Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold py-1 mb-0"><span class="text-muted fw-light">Pengaturan /</span> Lokasi & Jam Operasional Absensi</h4>
    <button type="button" id="btn-detect-location" class="btn btn-outline-primary">
        <i class="bx bx-target-lock me-1"></i> Deteksi Lokasi Saya Sekarang
    </button>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
    <i class="bx bx-check-circle me-1"></i> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="row">
    <!-- Peta Interaktif -->
    <div class="col-lg-7 mb-4">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-header bg-white border-bottom d-flex align-items-center justify-content-between">
                <h5 class="card-title m-0 fw-bold text-dark"><i class="bx bx-map-pin text-primary me-2"></i>Peta Lokasi Sekolah</h5>
                <small class="text-muted">Klik/Geser Pin Marker untuk Menentukan Titik</small>
            </div>
            <div class="card-body p-0">
                <div id="map" style="height: 580px; width: 100%; border-radius: 0 0 8px 8px;"></div>
            </div>
        </div>
    </div>

    <!-- Form Pengaturan -->
    <div class="col-lg-5 mb-4">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-header bg-white border-bottom">
                <h5 class="card-title m-0 fw-bold text-dark"><i class="bx bx-cog text-info me-2"></i>Atur Titik & Jam Operasional</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.setting.lokasi.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Nama Sekolah -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Sekolah / Instansi</label>
                        <input type="text" name="nama_sekolah" class="form-control" value="{{ $setting->nama_sekolah ?? 'Sekolah SIMAK' }}" required />
                    </div>

                    <hr class="my-3">
                    <h6 class="fw-bold text-primary mb-3"><i class="bx bx-time-five me-1"></i> Pengaturan Jam Operasional Absensi</h6>

                    <!-- Jam Masuk & Toleransi -->
                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Jam Masuk</label>
                            <input type="time" name="jam_masuk" class="form-control" value="{{ isset($setting->jam_masuk) ? \Carbon\Carbon::parse($setting->jam_masuk)->format('H:i') : '07:30' }}" required />
                            <small class="text-muted" style="font-size: 11px;">Batas jam tepat waktu.</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Toleransi (Menit)</label>
                            <input type="number" name="toleransi_terlambat_menit" class="form-control" value="{{ $setting->toleransi_terlambat_menit ?? 10 }}" min="0" required />
                            <small class="text-muted" style="font-size: 11px;">Keterangan: Terlambat.</small>
                        </div>
                    </div>

                    <!-- Jam Pulang -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Jam Pulang Sekolah</label>
                        <input type="time" name="jam_pulang" class="form-control" value="{{ isset($setting->jam_pulang) ? \Carbon\Carbon::parse($setting->jam_pulang)->format('H:i') : '14:30' }}" required />
                        <small class="text-muted" style="font-size: 11px;">Mulai diizinkan scan absen pulang.</small>
                    </div>

                    <hr class="my-3">
                    <h6 class="fw-bold text-primary mb-3"><i class="bx bx-map-alt me-1"></i> Titik Lokasi & Radius Geofencing</h6>

                    <div class="mb-3">
                        <label class="form-label">Latitude</label>
                        <input type="text" id="latitude" name="latitude" class="form-control bg-light" value="{{ $setting->latitude ?? '-6.917464' }}" readonly required />
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Longitude</label>
                        <input type="text" id="longitude" name="longitude" class="form-control bg-light" value="{{ $setting->longitude ?? '107.619123' }}" readonly required />
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Radius Jangkauan Presensi (Meter)</label>
                        <div class="input-group">
                            <input type="number" id="radius_meter" name="radius_meter" class="form-control" value="{{ $setting->radius_meter ?? 100 }}" min="10" required />
                            <span class="input-group-text">Meter</span>
                        </div>
                        <small class="text-muted">Jarak maksimal siswa dapat melakukan scan QR.</small>
                    </div>

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-primary fw-bold">
                            <i class="bx bx-save me-1"></i> Simpan Seluruh Pengaturan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Import Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        let defaultLat = {{ $setting->latitude ?? -6.917464 }};
        let defaultLng = {{ $setting->longitude ?? 107.619123 }};
        let defaultRadius = {{ $setting->radius_meter ?? 100 }};

        // Inisialisasi Peta Leaflet
        let map = L.map('map').setView([defaultLat, defaultLng], 17);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap'
        }).addTo(map);

        let marker = L.marker([defaultLat, defaultLng], { draggable: true }).addTo(map);
        let circle = L.circle([defaultLat, defaultLng], {
            color: '#696cff',
            fillColor: '#696cff',
            fillOpacity: 0.25,
            radius: defaultRadius
        }).addTo(map);

        function updatePosition(lat, lng) {
            marker.setLatLng([lat, lng]);
            circle.setLatLng([lat, lng]);
            document.getElementById('latitude').value = lat.toFixed(8);
            document.getElementById('longitude').value = lng.toFixed(8);
        }

        marker.on('dragend', function (e) {
            let position = marker.getLatLng();
            updatePosition(position.lat, position.lng);
        });

        map.on('click', function (e) {
            updatePosition(e.latlng.lat, e.latlng.lng);
        });

        document.getElementById('radius_meter').addEventListener('input', function (e) {
            let newRadius = parseFloat(e.target.value) || 0;
            circle.setRadius(newRadius);
        });

        function getCurrentLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function (position) {
                        let currentLat = position.coords.latitude;
                        let currentLng = position.coords.longitude;
                        map.flyTo([currentLat, currentLng], 18, { animate: true, duration: 1.5 });
                        updatePosition(currentLat, currentLng);
                    },
                    function (error) {
                        console.warn("Akses lokasi GPS tidak diizinkan.");
                    },
                    { enableHighAccuracy: true }
                );
            }
        }

        document.getElementById('btn-detect-location').addEventListener('click', function () {
            getCurrentLocation();
        });
    });
</script>
@endsection