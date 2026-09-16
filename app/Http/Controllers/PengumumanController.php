<?php

namespace App\Http\Controllers;

use App\Models\Pengumuman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengumumanController extends Controller
{
    public function index()
    {
        $pengumumans = Pengumuman::latest()->paginate(10);
        return view('admin.pengumuman.index', compact('pengumumans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul'  => 'required|string|max:255',
            'isi'    => 'required|string',
            'target' => 'required|in:semua,siswa,guru',
        ]);

        Pengumuman::create([
            'judul'      => $request->judul,
            'isi'        => $request->isi,
            'target'     => $request->target,
            'status'     => 'aktif',
            'created_by' => Auth::id(),
        ]);

        return redirect()->back()->with('success', 'Pengumuman baru berhasil diterbitkan!');
    }

    public function destroy($id)
    {
        $pengumuman = Pengumuman::findOrFail($id);
        $pengumuman->delete();

        return redirect()->back()->with('success', 'Pengumuman berhasil dihapus!');
    }
}