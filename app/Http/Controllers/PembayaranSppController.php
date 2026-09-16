<?php

namespace App\Http\Controllers;

use App\Models\PembayaranSpp;
use App\Models\JenisPembayaran;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PembayaranSppController extends Controller
{
    /**
     * Menampilkan riwayat seluruh transaksi pembayaran
     */
    public function index()
    {
        $pembayarans = PembayaranSpp::with(['siswa', 'jenisPembayaran', 'admin'])->latest()->get();
        return view('admin.keuangan.index', compact('pembayarans'));
    }

    /**
     * Form Tambah Transaksi Pembayaran Baru
     */
    public function create()
    {
        $siswas = Siswa::where('status_siswa', 'aktif')->get();
        
        // Buat otomatis pilihan Jenis Pembayaran standar jika belum ada data di database
        if (JenisPembayaran::count() === 0) {
            JenisPembayaran::create(['nama_pembayaran' => 'SPP Bulanan', 'tipe' => 'bulanan', 'nominal_default' => 300000]);
            JenisPembayaran::create(['nama_pembayaran' => 'Uang Gedung / DSP', 'tipe' => 'bebas', 'nominal_default' => 1500000]);
            JenisPembayaran::create(['nama_pembayaran' => 'Seragam & Atribut', 'tipe' => 'bebas', 'nominal_default' => 500000]);
        }

        $jenisPembayarans = JenisPembayaran::all();
        $taAktif = TahunAjaran::where('is_active', true)->first();
        
        $bulans = [
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember',
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni'
        ];

        return view('admin.keuangan.create', compact('siswas', 'jenisPembayarans', 'taAktif', 'bulans'));
    }

    /**
     * Simpan Transaksi Pembayaran
     */
    public function store(Request $request)
    {
        $request->validate([
            'siswa_id'            => 'required|exists:siswas,id',
            'jenis_pembayaran_id' => 'required|exists:jenis_pembayarans,id',
            'jumlah_bayar'        => 'required|numeric|min:0',
            'tgl_bayar'           => 'required|date',
        ]);

        $taAktif = TahunAjaran::where('is_active', true)->first();

        if (!$taAktif) {
            return redirect()->back()->with('error', 'Tahun Ajaran Aktif belum ditentukan oleh Admin!');
        }

        // Cek jika ini SPP Bulanan, pastikan bulan tersebut belum dibayar
        $jenis = JenisPembayaran::find($request->jenis_pembayaran_id);
        if ($jenis && $jenis->tipe === 'bulanan' && $request->bulan_dibayar) {
            $cekBayar = PembayaranSpp::where('siswa_id', $request->siswa_id)
                ->where('jenis_pembayaran_id', $request->jenis_pembayaran_id)
                ->where('bulan_dibayar', $request->bulan_dibayar)
                ->where('tahun_dibayar', $request->tahun_dibayar)
                ->first();

            if ($cekBayar) {
                return redirect()->back()->with('error', "Pembayaran {$jenis->nama_pembayaran} bulan {$request->bulan_dibayar} {$request->tahun_dibayar} untuk siswa tersebut sudah LUNAS!");
            }
        }

        // Generate Kode Transaksi Unik (Contoh: TRX-20260814143500)
        $kodeTrx = 'TRX-' . Carbon::now()->format('YmdHis');

        PembayaranSpp::create([
            'kode_transaksi'      => $kodeTrx,
            'siswa_id'            => $request->siswa_id,
            'jenis_pembayaran_id' => $request->jenis_pembayaran_id,
            'tahun_ajaran_id'     => $taAktif->id,
            'bulan_dibayar'       => $request->bulan_dibayar,
            'tahun_dibayar'       => $request->tahun_dibayar,
            'tgl_bayar'           => $request->tgl_bayar,
            'jumlah_bayar'        => $request->jumlah_bayar,
            'status'              => 'Lunas',
            'admin_id'            => Auth::id(),
        ]);

        return redirect()->route('admin.keuangan.index')
            ->with('success', "Transaksi berhasil dicatat dengan Kode: {$kodeTrx}");
    }

    /**
     * Cetak Struk/Kuitansi Pembayaran Digital
     */
    public function cetakKuitansi(PembayaranSpp $pembayaran)
    {
        $pembayaran->load(['siswa', 'jenisPembayaran', 'admin', 'tahunAjaran']);
        return view('admin.keuangan.kuitansi', compact('pembayaran'));
    }

    /**
     * Batalkan Transaksi
     */
    public function destroy(PembayaranSpp $pembayaran)
    {
        $pembayaran->delete();

        return redirect()->route('admin.keuangan.index')
            ->with('success', 'Transaksi berhasil dibatalkan dan dihapus.');
    }
}