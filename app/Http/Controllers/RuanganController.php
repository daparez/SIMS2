<?php

namespace App\Http\Controllers;

use App\Models\Ruangan;
use App\Models\Guru;
use Illuminate\Http\Request;

class RuanganController extends Controller
{
    /**
     * Tampilkan Daftar Ruangan (Terlama di atas, Terbaru di bawah)
     */
    public function index()
    {
        $ruangans = Ruangan::with(['penanggungJawab', 'barangs'])
            ->orderBy('id', 'asc')
            ->get();
            
        $gurus = Guru::orderBy('nama_lengkap', 'asc')->get();

        return view('admin.ruangan.index', compact('ruangans', 'gurus'));
    }

    /**
     * Simpan Data Ruangan Baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode_ruangan'        => 'required|string|max:50|unique:ruangans,kode_ruangan',
            'nama_ruangan'        => 'required|string|max:255',
            'kategori_ruangan'    => 'required|string',
            'luas_ruangan'        => 'nullable|numeric',
            'kapasitas'           => 'required|integer|min:0',
            'penanggung_jawab_id' => 'nullable|exists:gurus,id',
            'status_kondisi'      => 'required|string',
            'keterangan'          => 'nullable|string',
        ]);

        Ruangan::create([
            'kode_ruangan'        => strtoupper($request->kode_ruangan),
            'nama_ruangan'        => $request->nama_ruangan,
            'kategori_ruangan'    => $request->kategori_ruangan,
            'luas_ruangan'        => $request->luas_ruangan,
            'kapasitas'           => $request->kapasitas,
            'penanggung_jawab_id' => $request->penanggung_jawab_id,
            'status_kondisi'      => $request->status_kondisi,
            'keterangan'          => $request->keterangan,
        ]);

        return redirect()->route('admin.ruangan.index')->with('success', 'Data ruangan berhasil ditambahkan!');
    }

    /**
     * Update Data Ruangan
     */
    public function update(Request $request, $id)
    {
        $ruangan = Ruangan::findOrFail($id);

        $request->validate([
            'kode_ruangan'        => 'required|string|max:50|unique:ruangans,kode_ruangan,' . $ruangan->id,
            'nama_ruangan'        => 'required|string|max:255',
            'kategori_ruangan'    => 'required|string',
            'luas_ruangan'        => 'nullable|numeric',
            'kapasitas'           => 'required|integer|min:0',
            'penanggung_jawab_id' => 'nullable|exists:gurus,id',
            'status_kondisi'      => 'required|string',
            'keterangan'          => 'nullable|string',
        ]);

        $ruangan->update([
            'kode_ruangan'        => strtoupper($request->kode_ruangan),
            'nama_ruangan'        => $request->nama_ruangan,
            'kategori_ruangan'    => $request->kategori_ruangan,
            'luas_ruangan'        => $request->luas_ruangan,
            'kapasitas'           => $request->kapasitas,
            'penanggung_jawab_id' => $request->penanggung_jawab_id,
            'status_kondisi'      => $request->status_kondisi,
            'keterangan'          => $request->keterangan,
        ]);

        return redirect()->route('admin.ruangan.index')->with('success', 'Data ruangan berhasil diperbarui!');
    }

    /**
     * Hapus Data Ruangan
     */
    public function destroy($id)
    {
        $ruangan = Ruangan::findOrFail($id);
        $ruangan->delete();

        return redirect()->route('admin.ruangan.index')->with('success', 'Data ruangan berhasil dihapus!');
    }
}