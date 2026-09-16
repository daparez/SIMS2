<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Jurusan;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    /**
     * Menampilkan daftar kelas.
     */
    public function index()
    {
        $kelases = Kelas::with('jurusan')->latest()->get();
        $jurusans = Jurusan::all();
        return view('admin.kelas.index', compact('kelases', 'jurusans'));
    }

    /**
     * Menyimpan kelas baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:50|unique:kelas,nama_kelas',
            'tingkat'    => 'required|in:10,11,12',
            'jurusan_id' => 'nullable|exists:jurusans,id',
        ]);

        Kelas::create([
            'nama_kelas' => $request->nama_kelas,
            'tingkat'    => $request->tingkat,
            'jurusan_id' => $request->jurusan_id,
        ]);

        return redirect()->route('admin.kelas.index')
            ->with('success', 'Data Kelas berhasil ditambahkan!');
    }

    /**
     * Memperbarui data kelas.
     */
    public function update(Request $request, Kelas $kela)
    {
        // Catatan: Nama parameter $kela adalah penamaan otomatis dari Laravel resource
        $request->validate([
            'nama_kelas' => 'required|string|max:50|unique:kelas,nama_kelas,' . $kela->id,
            'tingkat'    => 'required|in:10,11,12',
            'jurusan_id' => 'nullable|exists:jurusans,id',
        ]);

        $kela->update([
            'nama_kelas' => $request->nama_kelas,
            'tingkat'    => $request->tingkat,
            'jurusan_id' => $request->jurusan_id,
        ]);

        return redirect()->route('admin.kelas.index')
            ->with('success', 'Data Kelas berhasil diperbarui!');
    }

    /**
     * Menghapus kelas.
     */
    public function destroy(Kelas $kela)
    {
        $kela->delete();

        return redirect()->route('admin.kelas.index')
            ->with('success', 'Data Kelas berhasil dihapus!');
    }
}