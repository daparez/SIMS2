<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    protected $fillable = [
        'jurusan_id',
        'nama_kelas',
        'tingkat',
    ];

    // Relasi balik ke Jurusan
    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class);
    }
}