<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AbsensiController extends Controller
{
    /**
     * Hitung jarak antara 2 koordinat (Latitude & Longitude) dalam satuan Meter (Haversine Formula)
     */
    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000; // Radius bumi dalam meter

        $latFrom = deg2rad($lat1);
        $lonFrom = deg2rad($lon1);
        $latTo   = deg2rad($lat2);
        $lonTo   = deg2rad($lon2);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
            cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));

        return $earthRadius * $angle; // Output dalam meter
    }

    /**
     * Menampilkan Halaman Scanner QR Code.
     */
    public function scan()
    {
        $setting = DB::table('sekolah_settings')->first();
        return view('admin.absensi.scan', compact('setting'));
    }

    /**
     * Memproses QR Code yang dipindai kamera beserta Validasi Koordinat GPS & Jam Operasional (AJAX Request).
     */
    public function scanProcess(Request $request)
    {
        $request->validate([
            'qr_code'   => 'required|string',
            'latitude'  => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $qrCode   = $request->input('qr_code');
        $latUser  = $request->input('latitude');
        $lonUser  = $request->input('longitude');

        // Pastikan Zona Waktu Indonesia (WIB / Asia/Jakarta)
        $now = Carbon::now('Asia/Jakarta');
        $todayStr = $now->format('Y-m-d');
        $timeStr  = $now->format('H:i:s');

        // 1. Ambil Pengaturan Lokasi & Jam Operasional Sekolah
        $setting = DB::table('sekolah_settings')->first();
        
        $latSekolah   = $setting->latitude ?? -6.917464;
        $lonSekolah   = $setting->longitude ?? 107.619123;
        $radiusMaxMtr = $setting->radius_meter ?? 100;

        // 2. Validasi Geofencing GPS Radius Lokasi Sekolah
        $jarakUserMeter = $this->calculateDistance($latUser, $lonUser, $latSekolah, $lonSekolah);

        if ($jarakUserMeter > $radiusMaxMtr) {
            return response()->json([
                'status'  => 'error',
                'message' => "ABSENSI DITOLAK! Anda berada di luar area sekolah. Terdeteksi berjarak " . round($jarakUserMeter) . " meter dari titik sekolah (Batas Maksimal: {$radiusMaxMtr}m)."
            ], 400);
        }

        // 3. Cari Data Siswa Berdasarkan NISN atau UUID
        $siswa = Siswa::where('nisn', $qrCode)
                      ->orWhere('uuid', $qrCode)
                      ->first();

        if (!$siswa) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Siswa tidak ditemukan dalam sistem!'
            ], 404);
        }

        // 4. Ambil Tahun Ajaran Aktif
        $taAktif = TahunAjaran::where('is_active', true)->first();

        if (!$taAktif) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Tahun Ajaran Aktif belum diatur oleh Admin!'
            ], 400);
        }

        // 5. Setup Objek Waktu Operasional (Format 24 Jam)
        $strJamMasuk  = substr($setting->jam_masuk ?? '07:30:00', 0, 8);
        $toleransiMnt = (int) ($setting->toleransi_terlambat_menit ?? 10);
        $strJamPulang = substr($setting->jam_pulang ?? '14:30:00', 0, 8);

        // Buat Objek Carbon Hari Ini dengan Jam Operasional
        $jamMasuk          = Carbon::createFromFormat('Y-m-d H:i:s', $todayStr . ' ' . $strJamMasuk, 'Asia/Jakarta');
        $jamBatasToleransi = $jamMasuk->copy()->addMinutes($toleransiMnt);
        $jamPulang         = Carbon::createFromFormat('Y-m-d H:i:s', $todayStr . ' ' . $strJamPulang, 'Asia/Jakarta');

        // Cek Record Absensi Siswa Hari Ini
        $absensi = Absensi::where('siswa_id', $siswa->id)
                          ->where('tanggal', $todayStr)
                          ->first();

        // =========================================================================
        // LOGIKA ABSEN MASUK (Belum pernah absen hari ini)
        // =========================================================================
        if (!$absensi) {

            // Evaluasi Ketepatan Waktu Datang
            if ($now->greaterThan($jamBatasToleransi)) {
                // Lewat dari batas toleransi (Tetap dicatat, tetapi status Terlambat)
                $statusAbsen = 'Terlambat';
                $pesanStatus = "ABSEN MASUK TERLAMBAT";
            } elseif ($now->greaterThan($jamMasuk)) {
                // Berada di rentang jam masuk s/d toleransi
                $statusAbsen = 'Terlambat';
                $pesanStatus = "ABSEN MASUK TERLAMBAT (MASIH TOLERANSI)";
            } else {
                // Tepat waktu (<= jam masuk)
                $statusAbsen = 'Hadir';
                $pesanStatus = "ABSEN MASUK BERHASIL";
            }

            // Simpan Data Absen Masuk
            Absensi::create([
                'siswa_id'        => $siswa->id,
                'tahun_ajaran_id' => $taAktif->id,
                'tanggal'         => $todayStr,
                'jam_masuk'       => $timeStr,
                'status'          => $statusAbsen,
            ]);

            return response()->json([
                'status'  => $statusAbsen === 'Hadir' ? 'success' : 'warning',
                'type'    => 'masuk',
                'message' => "{$pesanStatus}: {$siswa->nama_lengkap} ({$siswa->kelas}) pada jam {$now->format('H:i:s')} WIB",
                'siswa'   => $siswa
            ]);
        } 
        
        // =========================================================================
        // LOGIKA ABSEN PULANG (Sudah absen masuk sebelumnya)
        // =========================================================================
        else {
            // Jika sudah pernah absen pulang hari ini
            if ($absensi->jam_keluar != null) {
                return response()->json([
                    'status'  => 'warning',
                    'message' => "Siswa {$siswa->nama_lengkap} sudah melakukan absen masuk dan pulang hari ini!"
                ]);
            }

            // Belum memasuki jam pulang (Misal jam sekarang < 14:30)
            if ($now->lessThan($jamPulang)) {
                return response()->json([
                    'status'  => 'error',
                    'message' => "BELUM WAKTUNYA PULANG! Jam pulang sekolah diatur pukul {$jamPulang->format('H:i')} WIB. Saat ini masih pukul {$now->format('H:i:s')} WIB."
                ], 400);
            }

            // Simpan Data Absen Pulang
            $absensi->update([
                'jam_keluar' => $timeStr
            ]);

            return response()->json([
                'status'  => 'success',
                'type'    => 'keluar',
                'message' => "ABSEN PULANG BERHASIL: {$siswa->nama_lengkap} ({$siswa->kelas}) pada jam {$now->format('H:i:s')} WIB",
                'siswa'   => $siswa
            ]);
        }
    }

    /**
     * Menampilkan Rekapitulasi Absensi Harian (Urutan Terlama di Atas).
     */
    public function index()
    {
        $today = Carbon::now('Asia/Jakarta')->format('Y-m-d');
        $absensis = Absensi::with('siswa')
            ->where('tanggal', $today)
            ->orderBy('id', 'asc')
            ->get();

        return view('admin.absensi.index', compact('absensis'));
    }
}