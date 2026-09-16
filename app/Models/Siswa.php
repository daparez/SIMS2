<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Siswa extends Model
{
    protected $fillable = [
        'uuid',
        'user_id',
        'nisn',
        'nis',
        'nik',
        'nama_lengkap',
        'nama_panggilan',
        'tempat_lahir',
        'tgl_lahir',
        'jenis_kelamin',
        'agama',
        'alamat',
        'no_hp',
        'foto',
        'status_siswa',
        'kelas',
        'jurusan',
        'angkatan',
        'tgl_diterima',
        'nama_ayah',
        'nama_ibu',
        'nama_wali',
        'nama_ortu',
        'no_hp_ortu',
        'pekerjaan_ortu',
        'file_kk',
        'file_ijazah',
        'file_akta',
        'file_ktp_ayah',
        'file_ktp_ibu',
    ];

    // Otomatis buat UUID saat data siswa pertama kali disimpan
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }

    // Relasi ke User (Akun Login Siswa)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Absensi Siswa
    public function absensis()
    {
        return $this->hasMany(Absensi::class);
    }

    // Relasi ke Pembayaran SPP / Keuangan Siswa
    public function pembayaranSpp()
    {
        return $this->hasMany(PembayaranSpp::class);
    }
}