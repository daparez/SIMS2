<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ruangan extends Model
{
    use HasFactory;
    protected $table = 'ruangans';

    protected $fillable = [
        'kode_ruangan',
        'nama_ruangan',
        'kategori_ruangan',
        'luas_ruangan',
        'kapasitas',
        'penanggung_jawab_id',
        'status_kondisi',
        'keterangan'
    ];

    public function penanggungJawab()
    {
        return $this->belongsTo(Guru::class, 'penanggung_jawab_id');
    }

    public function barangs()
    {
        return $this->hasMany(Barang::class, 'ruangan_id');
    }
}
