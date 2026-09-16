<?php

namespace App\Http\Controllers;

use App\Models\MataPelajaran;
use Illuminate\Http\Request;

class MataPelajaranController extends Controller
{
    /**
     * Menampilkan daftar mata pelajaran.
     */
    public function index()
    {
        $mapels = MataPelajaran::latest()->get();
        return view('admin.mapel.index', compact('mapels'));
    }

    /**
     * Menyimpan mata pelajaran baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode_mapel' => 'required|string|max:20|unique:mata_pelajarans,kode_mapel',
            'nama_mapel' => 'required|string|max:255',
            'kelompok'   => 'required|in:A,B,C1,C2,C3',
            'sks_jam'    => 'required|numeric|min:1|max:10',
        ]);

        MataPelajaran::create([
            'kode_mapel' => strtoupper($request->kode_mapel),
            'nama_mapel' => $request->nama_mapel,
            'kelompok'   => $request->kelompok,
            'sks_jam'    => $request->sks_jam,
        ]);

        return redirect()->route('admin.mapel.index')
            ->with('success', 'Mata Pelajaran berhasil ditambahkan!');
    }

    /**
     * Memperbarui data mata pelajaran.
     */
    public function update(Request $request, MataPelajaran $mapel)
    {
        $request->validate([
            'kode_mapel' => 'required|string|max:20|unique:mata_pelajarans,kode_mapel,' . $mapel->id,
            'nama_mapel' => 'required|string|max:255',
            'kelompok'   => 'required|in:A,B,C1,C2,C3',
            'sks_jam'    => 'required|numeric|min:1|max:10',
        ]);

        $mapel->update([
            'kode_mapel' => strtoupper($request->kode_mapel),
            'nama_mapel' => $request->nama_mapel,
            'kelompok'   => $request->kelompok,
            'sks_jam'    => $request->sks_jam,
        ]);

        return redirect()->route('admin.mapel.index')
            ->with('success', 'Mata Pelajaran berhasil diperbarui!');
    }

    /**
     * Menghapus mata pelajaran.
     */
    public function destroy(MataPelajaran $mapel)
    {
        $mapel->delete();

        return redirect()->route('admin.mapel.index')
            ->with('success', 'Mata Pelajaran berhasil dihapus!');
    }
}