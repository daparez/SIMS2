<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\User;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class GuruController extends Controller
{
    /**
     * Tampilkan Daftar Guru (Urutan Terlama di Atas, Terbaru di Bawah)
     */
    public function index()
    {
        $gurus = Guru::with(['user', 'waliKelas'])
            ->orderBy('id', 'asc')
            ->get();

        return view('admin.guru.index', compact('gurus'));
    }

    /**
     * Tampilkan Form Tambah Guru Baru
     */
    public function create()
    {
        $kelases = Kelas::orderBy('nama_kelas')->get();
        return view('admin.guru.create', compact('kelases'));
    }

    /**
     * Simpan Data Guru Baru beserta Akun User
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'email'        => 'required|email|unique:users,email',
            'password'     => 'required|string|min:6',
            'nip'          => 'nullable|string|unique:gurus,nip',
            'kode_guru'    => 'nullable|string|max:10',
            'no_hp'        => 'nullable|string',
            'foto'         => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // 1. Buat Akun User untuk Login Guru
        $user = User::create([
            'name'     => $request->nama_lengkap,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'guru',
        ]);

        // Upload Foto jika ada
        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('guru-foto', 'public');
        }

        // 2. Buat Record Profile Guru
        Guru::create([
            'user_id'             => $user->id,
            'nip'                 => $request->nip,
            'nik'                 => $request->nik,
            'nama_lengkap'        => $request->nama_lengkap,
            'kode_guru'           => $request->kode_guru,
            'jenis_kelamin'       => $request->jenis_kelamin,
            'tempat_lahir'        => $request->tempat_lahir,
            'tgl_lahir'           => $request->tgl_lahir,
            'agama'               => $request->agama,
            'alamat'              => $request->alamat,
            'no_hp'               => $request->no_hp,
            'foto'                => $fotoPath,
            'status_kepegawaian'  => $request->status_kepegawaian,
            'jenis_ptk'           => $request->jenis_ptk,
            'tgl_masuk'           => $request->tgl_masuk,
            'pangkat_golongan'    => $request->pangkat_golongan,
            'pendidikan_terakhir' => $request->pendidikan_terakhir,
            'jurusan_pendidikan'  => $request->jurusan_pendidikan,
            'nama_kampus'         => $request->nama_kampus,
            'is_sertifikasi'      => $request->has('is_sertifikasi'),
            'nuptk'               => $request->nuptk,
            'wali_kelas_id'       => $request->wali_kelas_id,
        ]);

        return redirect()->route('admin.guru.index')->with('success', 'Data guru dan akun login berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     * Mengalihkan balik ke index karena detail sudah ditampilkan via Modal
     */
    public function show($id)
    {
        return redirect()->route('admin.guru.index');
    }

    /**
     * Tampilkan Form Edit Data Guru
     */
    public function edit($id)
    {
        $guru = Guru::with('user')->findOrFail($id);
        $kelases = Kelas::orderBy('nama_kelas')->get();
        return view('admin.guru.edit', compact('guru', 'kelases'));
    }

    /**
     * Update Data Guru
     */
    public function update(Request $request, $id)
    {
        $guru = Guru::findOrFail($id);
        
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'email'        => 'required|email|unique:users,email,' . $guru->user_id,
            'nip'          => 'nullable|string|unique:gurus,nip,' . $guru->id,
            'foto'         => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Update User Sesi Login
        if ($guru->user) {
            $userData = [
                'name'  => $request->nama_lengkap,
                'email' => $request->email,
            ];
            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }
            $guru->user->update($userData);
        }

        // Upload foto baru jika diganti
        if ($request->hasFile('foto')) {
            if ($guru->foto) {
                Storage::disk('public')->delete($guru->foto);
            }
            $guru->foto = $request->file('foto')->store('guru-foto', 'public');
        }

        // Update Data Guru
        $guru->update([
            'nip'                 => $request->nip,
            'nik'                 => $request->nik,
            'nama_lengkap'        => $request->nama_lengkap,
            'kode_guru'           => $request->kode_guru,
            'jenis_kelamin'       => $request->jenis_kelamin,
            'tempat_lahir'        => $request->tempat_lahir,
            'tgl_lahir'           => $request->tgl_lahir,
            'agama'               => $request->agama,
            'alamat'              => $request->alamat,
            'no_hp'               => $request->no_hp,
            'status_kepegawaian'  => $request->status_kepegawaian,
            'jenis_ptk'           => $request->jenis_ptk,
            'tgl_masuk'           => $request->tgl_masuk,
            'pangkat_golongan'    => $request->pangkat_golongan,
            'pendidikan_terakhir' => $request->pendidikan_terakhir,
            'jurusan_pendidikan'  => $request->jurusan_pendidikan,
            'nama_kampus'         => $request->nama_kampus,
            'is_sertifikasi'      => $request->has('is_sertifikasi'),
            'nuptk'               => $request->nuptk,
            'wali_kelas_id'       => $request->wali_kelas_id,
        ]);

        return redirect()->route('admin.guru.index')->with('success', 'Data guru berhasil diperbarui!');
    }

    /**
     * Hapus Data Guru beserta Akun User
     */
    public function destroy($id)
    {
        $guru = Guru::findOrFail($id);
        
        if ($guru->foto) {
            Storage::disk('public')->delete($guru->foto);
        }

        if ($guru->user) {
            $guru->user->delete();
        }

        $guru->delete();

        return redirect()->route('admin.guru.index')->with('success', 'Data guru dan akun login berhasil dihapus!');
    }

    /**
     * Cetak Rekap PDF Data Guru
     */
    public function cetakPdf()
    {
        $gurus = Guru::with(['user', 'waliKelas'])->orderBy('id', 'asc')->get();
        return view('admin.guru.cetak', compact('gurus'));
    }
}