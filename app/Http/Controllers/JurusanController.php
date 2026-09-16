<?php

namespace App\Http\Controllers;

use App\Models\Jurusan;
use Illuminate\Http\Request;

class JurusanController extends Controller
{
    /**
     * Menampilkan daftar jurusan.
     */
    public function index()
    {
        $jurusans = Jurusan::latest()->get();
        return view('admin.jurusan.index', compact('jurusans'));
    }

    /**
     * Menyimpan jurusan baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode_jurusan' => 'required|string|max:20|unique:jurusans,kode_jurusan',
            'nama_jurusan' => 'required|string|max:255',
            'deskripsi'    => 'nullable|string',
        ]);

        Jurusan::create([
            'kode_jurusan' => strtoupper($request->kode_jurusan),
            'nama_jurusan' => $request->nama_jurusan,
            'deskripsi'    => $request->deskripsi,
        ]);

        return redirect()->route('admin.jurusan.index')
            ->with('success', 'Data Jurusan berhasil ditambahkan!');
    }

    /**
     * Memperbarui data jurusan.
     */
    public function update(Request $request, Jurusan $jurusan)
    {
        $request->validate([
            'kode_jurusan' => 'required|string|max:20|unique:jurusans,kode_jurusan,' . $jurusan->id,
            'nama_jurusan' => 'required|string|max:255',
            'deskripsi'    => 'nullable|string',
        ]);

        $jurusan->update([
            'kode_jurusan' => strtoupper($request->kode_jurusan),
            'nama_jurusan' => $request->nama_jurusan,
            'deskripsi'    => $request->deskripsi,
        ]);

        return redirect()->route('admin.jurusan.index')
            ->with('success', 'Data Jurusan berhasil diperbarui!');
    }

    /**
     * Menghapus jurusan.
     */
    public function destroy(Jurusan $jurusan)
    {
        $jurusan->delete();

        return redirect()->route('admin.jurusan.index')
            ->with('success', 'Data Jurusan berhasil dihapus!');
    }
}