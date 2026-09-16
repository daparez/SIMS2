<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\KategoriBarang;
use App\Models\Ruangan;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    /**
     * Tampilkan Halaman Master Barang
     */
public function index()
{
    $barangs = Barang::with(['kategori', 'ruangan'])->orderBy('id', 'asc')->get();
    $kategoris = KategoriBarang::orderBy('nama_kategori', 'asc')->get();
    $ruangans = Ruangan::orderBy('nama_ruangan', 'asc')->get();
    
    // Generate kode otomatis untuk form tambah
    $autoKode = $this->generateKodeBarang();

    return view('admin.barang.index', compact('barangs', 'kategoris', 'ruangans', 'autoKode'));
}

    /**
     * Simpan Barang Baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode_barang'          => 'required|string|max:100|unique:barangs,kode_barang',
            'nama_barang'          => 'required|string|max:255',
            'kategori_barang_id'   => 'required|exists:kategori_barangs,id',
            'sumber_dana'          => 'required|string',
            'tahun_pengadaan'      => 'nullable|numeric|digits:4',
            'harga_perolehan'      => 'nullable|numeric|min:0',
            'kondisi_baik'         => 'required|integer|min:0',
            'kondisi_rusak_ringan' => 'required|integer|min:0',
            'kondisi_rusak_berat'  => 'required|integer|min:0',
            'ruangan_id'           => 'nullable|exists:ruangans,id',
            'status_aset'          => 'required|string',
        ]);

        $totalJumlah = $request->kondisi_baik + $request->kondisi_rusak_ringan + $request->kondisi_rusak_berat;

        Barang::create([
            'kode_barang'          => strtoupper($request->kode_barang),
            'nama_barang'          => $request->nama_barang,
            'kategori_barang_id'   => $request->kategori_barang_id,
            'merk_spesifikasi'     => $request->merk_spesifikasi,
            'sumber_dana'          => $request->sumber_dana,
            'tahun_pengadaan'      => $request->tahun_pengadaan ?? date('Y'),
            'harga_perolehan'      => $request->harga_perolehan ?? 0,
            'jumlah'               => $totalJumlah,
            'kondisi_baik'         => $request->kondisi_baik,
            'kondisi_rusak_ringan' => $request->kondisi_rusak_ringan,
            'kondisi_rusak_berat'  => $request->kondisi_rusak_berat,
            'ruangan_id'           => $request->ruangan_id,
            'status_aset'          => $request->status_aset,
        ]);

        return redirect()->route('admin.barang.index')->with('success', 'Data barang & aset berhasil ditambahkan!');
    }

    /**
     * Update Data Barang
     */
    public function update(Request $request, $id)
    {
        $barang = Barang::findOrFail($id);

        $request->validate([
            'kode_barang'          => 'required|string|max:100|unique:barangs,kode_barang,' . $barang->id,
            'nama_barang'          => 'required|string|max:255',
            'kategori_barang_id'   => 'required|exists:kategori_barangs,id',
            'sumber_dana'          => 'required|string',
            'tahun_pengadaan'      => 'nullable|numeric|digits:4',
            'harga_perolehan'      => 'nullable|numeric|min:0',
            'kondisi_baik'         => 'required|integer|min:0',
            'kondisi_rusak_ringan' => 'required|integer|min:0',
            'kondisi_rusak_berat'  => 'required|integer|min:0',
            'ruangan_id'           => 'nullable|exists:ruangans,id',
            'status_aset'          => 'required|string',
        ]);

        $totalJumlah = $request->kondisi_baik + $request->kondisi_rusak_ringan + $request->kondisi_rusak_berat;

        $barang->update([
            'kode_barang'          => strtoupper($request->kode_barang),
            'nama_barang'          => $request->nama_barang,
            'kategori_barang_id'   => $request->kategori_barang_id,
            'merk_spesifikasi'     => $request->merk_spesifikasi,
            'sumber_dana'          => $request->sumber_dana,
            'tahun_pengadaan'      => $request->tahun_pengadaan,
            'harga_perolehan'      => $request->harga_perolehan ?? 0,
            'jumlah'               => $totalJumlah,
            'kondisi_baik'         => $request->kondisi_baik,
            'kondisi_rusak_ringan' => $request->kondisi_rusak_ringan,
            'kondisi_rusak_berat'  => $request->kondisi_rusak_berat,
            'ruangan_id'           => $request->ruangan_id,
            'status_aset'          => $request->status_aset,
        ]);

        return redirect()->route('admin.barang.index')->with('success', 'Data barang berhasil diperbarui!');
    }

    /**
     * Hapus Data Barang
     */
    public function destroy($id)
    {
        $barang = Barang::findOrFail($id);
        $barang->delete();

        return redirect()->route('admin.barang.index')->with('success', 'Data barang berhasil dihapus!');
    }

    /**
     * Simpan Kategori Barang Baru (Quick Add)
     */
    public function storeKategori(Request $request)
    {
        $request->validate([
            'kode_kategori' => 'required|string|max:20|unique:kategori_barangs,kode_kategori',
            'nama_kategori' => 'required|string|max:100',
        ]);

        KategoriBarang::create([
            'kode_kategori' => strtoupper($request->kode_kategori),
            'nama_kategori' => $request->nama_kategori,
        ]);

        return redirect()->route('admin.barang.index')->with('success', 'Kategori barang berhasil ditambahkan!');
    }

    /**
 * Helper Generate Kode Barang Otomatis
 */
private function generateKodeBarang()
{
    $prefix = 'INV-' . date('Y');
    $random = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 4));
    return $prefix . '-' . $random;
}
}