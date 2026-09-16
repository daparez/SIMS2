<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    protected $fillable = [
        'user_id',
        'nip',
        'nik',
        'nama_lengkap',
        'kode_guru',
        'jenis_kelamin',
        'tempat_lahir',
        'tgl_lahir',
        'agama',
        'alamat',
        'no_hp',
        'foto',
        'status_kepegawaian',
        'jenis_ptk',
        'tgl_masuk',
        'pangkat_golongan',
        'pendidikan_terakhir',
        'jurusan_pendidikan',
        'nama_kampus',
        'is_sertifikasi',
        'nuptk',
        'wali_kelas_id',
    ];

    /**
     * Accessor agar $guru->nama_guru tetap berfungsi jika dipanggil oleh sistem jadwal
     */
    public function getNamaGuruAttribute()
    {
        return $this->nama_lengkap;
    }

    // Relasi ke User (Kredensial Login)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Kelas jika ditunjuk sebagai Wali Kelas
    public function waliKelas()
    {
        return $this->belongsTo(Kelas::class, 'wali_kelas_id');
    }

    // Relasi ke Plotting Pengampu Pelajaran
    public function pengampus()
    {
        return $this->hasMany(Pengampu::class);
    }
}