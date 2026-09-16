<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\JamPelajaran;
use App\Models\Pengampu;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use App\Models\MataPelajaran;
use App\Models\Guru;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    /**
     * Tampilkan Matriks Master Jadwal Pelajaran Keseluruhan
     */
    public function index()
    {
        $taAktif = TahunAjaran::where('is_active', true)->first();
        $kelases = Kelas::with('jurusan')->orderBy('nama_kelas')->get();
        
        // Slot Jam Pelajaran per Hari
        $jamPelajarans = JamPelajaran::orderBy('jam_ke')->get()->groupBy('hari');

        // Seluruh Jadwal Terpasang
        $jadwals = [];
        if ($taAktif) {
            $rawJadwals = Jadwal::with(['pengampu.guru', 'pengampu.mapel'])
                ->where('tahun_ajaran_id', $taAktif->id)
                ->get();

            foreach ($rawJadwals as $j) {
                $jadwals[$j->jam_pelajaran_id][$j->kelas_id] = $j;
            }
        }

        // Panggil Model Mapel & Guru Langsung dari Database
        $mapels = MataPelajaran::orderBy('nama_mapel')->get();
        $gurus  = Guru::orderBy('nama_lengkap')->get();

        return view('admin.jadwal.index', compact('kelases', 'jamPelajarans', 'jadwals', 'mapels', 'gurus', 'taAktif'));
    }

    /**
     * Simpan / Update Slot Jadwal Pelajaran (Anti-Bentrok Real-Time)
     */
    public function store(Request $request)
    {
        $request->validate([
            'kelas_id'         => 'required|exists:kelas,id',
            'jam_pelajaran_id' => 'required|exists:jam_pelajarans,id',
            'mapel_id'         => 'required|exists:mata_pelajarans,id',
            'guru_id'          => 'required|exists:gurus,id',
            'ruangan'          => 'nullable|string',
        ]);

        $taAktif = TahunAjaran::where('is_active', true)->firstOrFail();
        $jamSlot = JamPelajaran::findOrFail($request->jam_pelajaran_id);
        $guru    = Guru::findOrFail($request->guru_id);

        // 1. Cek Apakah Jam Istirahat
        if ($jamSlot->is_istirahat) {
            return redirect()->back()->with('error', 'Gagal! Jam Istirahat tidak bisa diisi pelajaran.');
        }

        // 2. Cek Bentrok Guru di Jam & Hari yang sama pada kelas lain
        $bentrokGuru = Jadwal::where('tahun_ajaran_id', $taAktif->id)
            ->where('jam_pelajaran_id', $request->jam_pelajaran_id)
            ->whereHas('pengampu', function ($q) use ($request) {
                $q->where('guru_id', $request->guru_id);
            })
            ->where('kelas_id', '!=', $request->kelas_id)
            ->first();

        if ($bentrokGuru) {
            $kelasBentrok = $bentrokGuru->kelas->nama_kelas ?? 'Kelas Lain';
            $namaGuru     = $guru->nama_lengkap ?? 'Guru';
            return redirect()->back()->with('error', "BENTROK GURU! {$namaGuru} sudah mengajar di {$kelasBentrok} pada Hari {$jamSlot->hari} Jam Ke-{$jamSlot->jam_ke}.");
        }

        // 3. Buat / Ambil Record Pengampu secara otomatis (On-The-Fly)
        $pengampu = Pengampu::firstOrCreate([
            'guru_id'         => $request->guru_id,
            'mapel_id'        => $request->mapel_id,
            'kelas_id'        => $request->kelas_id,
            'tahun_ajaran_id' => $taAktif->id,
        ]);

        // 4. Simpan / Update Slot Jadwal Pelajaran
        Jadwal::updateOrCreate(
            [
                'tahun_ajaran_id'  => $taAktif->id,
                'kelas_id'         => $request->kelas_id,
                'jam_pelajaran_id' => $request->jam_pelajaran_id,
            ],
            [
                'pengampu_id' => $pengampu->id,
                'ruangan'     => $request->ruangan ?? 'Ruang Kelas',
            ]
        );

        return redirect()->back()->with('success', 'Jadwal pelajaran berhasil disimpan!');
    }

    /**
     * Kosongkan Slot Jadwal
     */
    public function destroy($id)
    {
        $jadwal = Jadwal::findOrFail($id);
        $jadwal->delete();

        return redirect()->back()->with('success', 'Slot jadwal berhasil dikosongkan.');
    }

    /**
     * Tampilan Khusus Cetak Master Schedule (PDF / Print-Friendly)
     */
    public function cetak()
    {
        $taAktif = TahunAjaran::where('is_active', true)->first();
        $kelases = Kelas::with('jurusan')->orderBy('nama_kelas')->get();
        $jamPelajarans = JamPelajaran::orderBy('jam_ke')->get()->groupBy('hari');

        $jadwals = [];
        if ($taAktif) {
            $rawJadwals = Jadwal::with(['pengampu.guru', 'pengampu.mapel'])
                ->where('tahun_ajaran_id', $taAktif->id)
                ->get();

            foreach ($rawJadwals as $j) {
                $jadwals[$j->jam_pelajaran_id][$j->kelas_id] = $j;
            }
        }

        return view('admin.jadwal.cetak', compact('kelases', 'jamPelajarans', 'jadwals', 'taAktif'));
    }


    /**
 * Reset / Hapus Seluruh Jadwal Pelajaran pada Tahun Ajaran Aktif
 */
public function reset()
{
    $taAktif = TahunAjaran::where('is_active', true)->first();

    if ($taAktif) {
        Jadwal::where('tahun_ajaran_id', $taAktif->id)->delete();
        return redirect()->back()->with('success', 'Seluruh jadwal pelajaran pada tahun ajaran aktif berhasil di-reset!');
    }

    return redirect()->back()->with('error', 'Gagal reset! Tidak ada Tahun Ajaran aktif.');
}

    /**
     * Tampilkan Halaman Kelola Master Jam Pelajaran (Fitur Non-Seeder)
     */
    public function jamIndex()
    {
        $jamPelajarans = JamPelajaran::orderBy('hari')->orderBy('jam_ke')->get()->groupBy('hari');
        return view('admin.jadwal.jam', compact('jamPelajarans'));
    }

    /**
     * Simpan Slot Jam Pelajaran Baru dari Form UI Admin
     */
    public function jamStore(Request $request)
    {
        $request->validate([
            'hari'         => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'jam_ke'       => 'required|numeric',
            'jam_mulai'    => 'required',
            'jam_selesai'  => 'required',
            'is_istirahat' => 'nullable|boolean',
        ]);

        JamPelajaran::create([
            'hari'         => $request->hari,
            'jam_ke'       => $request->jam_ke,
            'jam_mulai'    => $request->jam_mulai,
            'jam_selesai'  => $request->jam_selesai,
            'is_istirahat' => $request->has('is_istirahat'),
        ]);

        return redirect()->back()->with('success', 'Slot Jam Pelajaran berhasil ditambahkan!');
    }

    /**
     * Hapus Slot Jam Pelajaran
     */
    public function jamDestroy($id)
    {
        $jam = JamPelajaran::findOrFail($id);
        $jam->delete();

        return redirect()->back()->with('success', 'Slot Jam Pelajaran berhasil dihapus!');
    }
}