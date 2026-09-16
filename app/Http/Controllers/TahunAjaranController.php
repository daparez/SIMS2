<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class TahunAjaranController extends Controller
{
    /**
     * Menampilkan daftar tahun ajaran.
     */
    public function index()
    {
        $tahunAjarans = TahunAjaran::latest()->get();
        return view('admin.tahun-ajaran.index', compact('tahunAjarans'));
    }

    /**
     * Menyimpan tahun ajaran baru secara manual.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tahun'    => 'required|string|max:20',
            'semester' => 'required|in:ganjil,genap',
        ]);

        TahunAjaran::create([
            'tahun'     => $request->tahun,
            'semester'  => $request->semester,
            'is_active' => false, // Default tidak aktif saat baru dibuat
        ]);

        return redirect()->route('admin.tahun-ajaran.index')
            ->with('success', 'Tahun Ajaran berhasil ditambahkan!');
    }

    /**
     * Memperbarui data tahun ajaran.
     */
    public function update(Request $request, TahunAjaran $tahunAjaran)
    {
        $request->validate([
            'tahun'    => 'required|string|max:20',
            'semester' => 'required|in:ganjil,genap',
        ]);

        $tahunAjaran->update([
            'tahun'    => $request->tahun,
            'semester' => $request->semester,
        ]);

        return redirect()->route('admin.tahun-ajaran.index')
            ->with('success', 'Tahun Ajaran berhasil diperbarui!');
    }

    /**
     * Mengubah status tahun ajaran menjadi AKTIF (Satu-satunya yang aktif).
     */
    public function activate(TahunAjaran $tahunAjaran)
    {
        // 1. Nonaktifkan semua tahun ajaran lain
        TahunAjaran::query()->update(['is_active' => false]);

        // 2. Aktifkan tahun ajaran yang dipilih
        $tahunAjaran->update(['is_active' => true]);

        return redirect()->route('admin.tahun-ajaran.index')
            ->with('success', "Tahun Ajaran {$tahunAjaran->tahun} ({$tahunAjaran->semester}) berhasil diaktifkan!");
    }

    /**
     * Otomatisasi Buat Tahun Ajaran Baru + Kenaikan Kelas + Kelulusan Siswa
     */
    public function autoRollover(Request $request)
    {
        // 1. Ambil tahun ajaran aktif saat ini
        $activeTA = TahunAjaran::where('is_active', true)->first();

        if (!$activeTA) {
            return redirect()->back()->with('error', 'Belum ada Tahun Ajaran aktif saat ini.');
        }

        // 2. Extrak tahun dari format "2025/2026"
        $years = explode('/', $activeTA->tahun); // ['2025', '2026']
        $nextStart = (int) $years[0] + 1; // 2026
        $nextEnd   = (int) $years[1] + 1; // 2027
        $nextTahunString = "{$nextStart}/{$nextEnd}";

        // Tentukan semester berikutnya (jika ganjil -> genap pada tahun sama, jika genap -> ganjil pada tahun baru)
        $nextSemester = $activeTA->semester === 'ganjil' ? 'genap' : 'ganjil';
        if ($activeTA->semester === 'genap') {
            $nextTahun = $nextTahunString;
        } else {
            $nextTahun = $activeTA->tahun;
        }

        // 3. Nonaktifkan semua Tahun Ajaran lama
        TahunAjaran::query()->update(['is_active' => false]);

        // 4. Buat dan Aktifkan Tahun Ajaran Baru
        $newTA = TahunAjaran::create([
            'tahun'     => $nextTahun,
            'semester'  => $nextSemester,
            'is_active' => true,
        ]);

        // 5. PROSES SISWA (Hanya diproses saat pergantian ke Tahun Ajaran Baru / Semester Ganjil)
        if ($nextSemester === 'ganjil') {
            
            // A. Luluskan Siswa Kelas 12 (Tingkat Akhir)
            Siswa::where('status_siswa', 'aktif')
                ->where('kelas', 'LIKE', '12%') // Sesuaikan '9%' jika SMP atau '6%' jika SD
                ->update(['status_siswa' => 'lulus']);

            // B. Naikkan Kelas Siswa Kelas 11 -> Kelas 12
            $siswaKelas11 = Siswa::where('status_siswa', 'aktif')->where('kelas', 'LIKE', '11%')->get();
            foreach ($siswaKelas11 as $siswa) {
                $kelasBaru = preg_replace('/^11/', '12', $siswa->kelas);
                $siswa->update(['kelas' => $kelasBaru]);
            }

            // C. Naikkan Kelas Siswa Kelas 10 -> Kelas 11
            $siswaKelas10 = Siswa::where('status_siswa', 'aktif')->where('kelas', 'LIKE', '10%')->get();
            foreach ($siswaKelas10 as $siswa) {
                $kelasBaru = preg_replace('/^10/', '11', $siswa->kelas);
                $siswa->update(['kelas' => $kelasBaru]);
            }

            return redirect()->route('admin.tahun-ajaran.index')
                ->with('success', "Berhasil lanjut ke Tahun Ajaran {$nextTahun} ({$nextSemester})! Siswa kelas 10 & 11 telah naik kelas, dan kelas 12 resmi Lulus.");
        }

        return redirect()->route('admin.tahun-ajaran.index')
            ->with('success', "Berhasil berganti ke Semester {$nextSemester} untuk Tahun Ajaran {$nextTahun}!");
    }

    /**
     * Menghapus tahun ajaran.
     */
    public function destroy(TahunAjaran $tahunAjaran)
    {
        if ($tahunAjaran->is_active) {
            return redirect()->route('admin.tahun-ajaran.index')
                ->with('error', 'Tahun Ajaran yang sedang aktif tidak boleh dihapus!');
        }

        $tahunAjaran->delete();

        return redirect()->route('admin.tahun-ajaran.index')
            ->with('success', 'Tahun Ajaran berhasil dihapus!');
    }
}