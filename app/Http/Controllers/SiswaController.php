<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class SiswaController extends Controller
{
    /**
     * Menampilkan daftar seluruh siswa.
     */
public function index(Request $request)
{
    $query = Siswa::with('user');

    // 1. Fitur Pencarian (Nama / NISN / NIS)
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('nama_lengkap', 'like', '%' . $search . '%')
              ->orWhere('nisn', 'like', '%' . $search . '%')
              ->orWhere('nis', 'like', '%' . $search . '%');
        });
    }

    // 2. Fitur Filter Berdasarkan Kelas
    if ($request->filled('kelas')) {
        $query->where('kelas', $request->kelas);
    }

    // 3. Pagination 20 Data Per Halaman
    $siswas = $query->latest()->paginate(20)->withQueryString();

    // Ambil daftar kelas unik untuk pilihan dropdown filter
    $listKelas = Siswa::select('kelas')->distinct()->pluck('kelas');

    return view('admin.siswa.index', compact('siswas', 'listKelas'));
}

    /**
     * Menampilkan form tambah siswa baru.
     */
    public function create()
    {
        return view('admin.siswa.create');
    }

    /**
     * Menyimpan data siswa baru & membuatkan akun login secara otomatis.
     */
    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'nisn'           => 'required|numeric|digits:10|unique:siswas,nisn|unique:users,username',
            'nis'            => 'nullable|numeric|unique:siswas,nis',
            'nama_lengkap'   => 'required|string|max:255',
            'nama_panggilan' => 'nullable|string|max:50',
            'tempat_lahir'   => 'required|string|max:100',
            'tgl_lahir'      => 'required|date',
            'jenis_kelamin'  => 'required|in:L,P',
            'agama'          => 'nullable|string|max:20',
            'alamat'         => 'nullable|string',
            'no_hp'          => 'nullable|string|max:20',
            'email'          => 'nullable|email|unique:users,email',
            'foto'           => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status_siswa'   => 'required|in:aktif,lulus,mutasi,keluar',
            'kelas'          => 'required|string|max:50',
            'jurusan'        => 'nullable|string|max:100',
            'angkatan'       => 'required|digits:4',
            'tgl_diterima'   => 'nullable|date',
            'nama_ayah'      => 'nullable|string|max:255',
            'nama_ibu'       => 'nullable|string|max:255',
            'nama_wali'      => 'nullable|string|max:255',
            'no_hp_ortu'     => 'nullable|string|max:20',
            'pekerjaan_ortu' => 'nullable|string|max:100',
        ]);

        // 2. Upload Foto Profil jika ada
        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('siswa-foto', 'public');
        }

        // 3. Format Tanggal Lahir menjadi Password Default (DDMMYYYY)
        $rawPassword = Carbon::parse($request->tgl_lahir)->format('dmY');

        // 4. Buat Akun User Otomatis untuk Login Siswa
        $user = User::create([
            'name'     => $request->nama_lengkap,
            'username' => $request->nisn,            // NISN sebagai username
            'email'    => $request->email,
            'password' => Hash::make($rawPassword), // Pass DDMMYYYY
            'role'     => 'siswa',
        ]);

        // 5. Simpan Data Detail Siswa
        Siswa::create([
            'user_id'        => $user->id,
            'nisn'           => $request->nisn,
            'nis'            => $request->nis,
            'nama_lengkap'   => $request->nama_lengkap,
            'nama_panggilan' => $request->nama_panggilan,
            'tempat_lahir'   => $request->tempat_lahir,
            'tgl_lahir'      => $request->tgl_lahir,
            'jenis_kelamin'  => $request->jenis_kelamin,
            'agama'          => $request->agama,
            'alamat'         => $request->alamat,
            'no_hp'          => $request->no_hp,
            'foto'           => $fotoPath,
            'status_siswa'   => $request->status_siswa,
            'kelas'          => $request->kelas,
            'jurusan'        => $request->jurusan,
            'angkatan'       => $request->angkatan,
            'tgl_diterima'   => $request->tgl_diterima,
            'nama_ayah'      => $request->nama_ayah,
            'nama_ibu'       => $request->nama_ibu,
            'nama_wali'      => $request->nama_wali,
            'no_hp_ortu'     => $request->no_hp_ortu,
            'pekerjaan_ortu' => $request->pekerjaan_ortu,
        ]);

        return redirect()->route('admin.siswa.index')
            ->with('success', "Siswa berhasil ditambahkan! Akun otomatis dibuat (Username: {$request->nisn}, Password Default: {$rawPassword})");
    }

    /**
     * Menampilkan detail data siswa secara rinci.
     */
    public function show(Siswa $siswa)
    {
        $siswa->load('user');
        return view('admin.siswa.show', compact('siswa'));
    }

    /**
     * Menampilkan form edit data siswa.
     */
    public function edit(Siswa $siswa)
    {
        $siswa->load('user');
        return view('admin.siswa.edit', compact('siswa'));
    }

    /**
     * Memperbarui data siswa dan akun user terkait.
     */
    public function update(Request $request, Siswa $siswa)
    {
        // 1. Validasi Input (Ignore ID saat ini untuk NISN & NIS)
        $request->validate([
            'nisn'           => 'required|numeric|digits:10|unique:siswas,nisn,' . $siswa->id . '|unique:users,username,' . $siswa->user_id,
            'nis'            => 'nullable|numeric|unique:siswas,nis,' . $siswa->id,
            'nama_lengkap'   => 'required|string|max:255',
            'nama_panggilan' => 'nullable|string|max:50',
            'tempat_lahir'   => 'required|string|max:100',
            'tgl_lahir'      => 'required|date',
            'jenis_kelamin'  => 'required|in:L,P',
            'agama'          => 'nullable|string|max:20',
            'alamat'         => 'nullable|string',
            'no_hp'          => 'nullable|string|max:20',
            'email'          => 'nullable|email|unique:users,email,' . $siswa->user_id,
            'foto'           => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status_siswa'   => 'required|in:aktif,lulus,mutasi,keluar',
            'kelas'          => 'required|string|max:50',
            'jurusan'        => 'nullable|string|max:100',
            'angkatan'       => 'required|digits:4',
            'tgl_diterima'   => 'nullable|date',
            'nama_ayah'      => 'nullable|string|max:255',
            'nama_ibu'       => 'nullable|string|max:255',
            'nama_wali'      => 'nullable|string|max:255',
            'no_hp_ortu'     => 'nullable|string|max:20',
            'pekerjaan_ortu' => 'nullable|string|max:100',
        ]);

        // 2. Handle Upload Foto Baru jika ada
        $fotoPath = $siswa->foto;
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($siswa->foto && Storage::disk('public')->exists($siswa->foto)) {
                Storage::disk('public')->delete($siswa->foto);
            }
            $fotoPath = $request->file('foto')->store('siswa-foto', 'public');
        }

        // 3. Update Data Detail Siswa
        $siswa->update([
            'nisn'           => $request->nisn,
            'nis'            => $request->nis,
            'nama_lengkap'   => $request->nama_lengkap,
            'nama_panggilan' => $request->nama_panggilan,
            'tempat_lahir'   => $request->tempat_lahir,
            'tgl_lahir'      => $request->tgl_lahir,
            'jenis_kelamin'  => $request->jenis_kelamin,
            'agama'          => $request->agama,
            'alamat'         => $request->alamat,
            'no_hp'          => $request->no_hp,
            'foto'           => $fotoPath,
            'status_siswa'   => $request->status_siswa,
            'kelas'          => $request->kelas,
            'jurusan'        => $request->jurusan,
            'angkatan'       => $request->angkatan,
            'tgl_diterima'   => $request->tgl_diterima,
            'nama_ayah'      => $request->nama_ayah,
            'nama_ibu'       => $request->nama_ibu,
            'nama_wali'      => $request->nama_wali,
            'no_hp_ortu'     => $request->no_hp_ortu,
            'pekerjaan_ortu' => $request->pekerjaan_ortu,
        ]);

        // 4. Synchronize Data ke Tabel Users
        $siswa->user->update([
            'name'     => $request->nama_lengkap,
            'username' => $request->nisn,
            'email'    => $request->email,
        ]);

        return redirect()->route('admin.siswa.index')
            ->with('success', 'Data siswa berhasil diperbarui!');
    }

    /**
     * Menghapus data siswa beserta foto dan akun user-nya.
     */
    public function destroy(Siswa $siswa)
    {
        // 1. Hapus file foto dari storage jika ada
        if ($siswa->foto && Storage::disk('public')->exists($siswa->foto)) {
            Storage::disk('public')->delete($siswa->foto);
        }

        // 2. Hapus Akun User (Data di tabel siswas akan otomatis terhapus karena foreign key CASCADE)
        $siswa->user()->delete();

        return redirect()->route('admin.siswa.index')
            ->with('success', 'Data siswa dan akun login berhasil dihapus!');
    }
}